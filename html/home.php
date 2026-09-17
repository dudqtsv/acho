<?php
require 'funcoes.php';

verificarLogin();

$id = $_SESSION['id'];
$resultado = listarProdutos($conexao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achô — Página inicial</title>

    <style>
        :root {
            --azul-marca: #2f3a8f;
            --azul-marca-escuro: #232a6b;
            --azul-claro: #eef0fb;
            --texto-principal: #1c1c1c;
            --texto-secundario: #6b6b6b;
            --preco: #1c1c1c;
            --branco: #ffffff;
            --cinza-fundo: #f4f5f7;
            --cinza-borda: #e4e4e7;
            --vermelho-coracao: #e63950;
            --badge-seminovo: #6b7280;
            --badge-novo: #2f3a8f;
            --sombra-card: 0 1px 3px rgba(0, 0, 0, 0.08);
            --sombra-card-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
            --raio: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--texto-principal);
            background: var(--cinza-fundo);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ---------- Cabeçalho ---------- */

        .topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 14px 40px;
            background: var(--branco);
            border-bottom: 1px solid var(--cinza-borda);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--azul-marca);
            color: var(--branco);
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .busca {
            flex: 1;
            max-width: 520px;
            position: relative;
        }

        .busca input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid var(--cinza-borda);
            border-radius: 999px;
            background: var(--cinza-fundo);
            font-size: 14px;
            outline: none;
        }

        .busca input:focus {
            border-color: var(--azul-marca);
            background: var(--branco);
        }

        .acoes {
            display: flex;
            align-items: center;
            gap: 24px;
            white-space: nowrap;
        }

        .acoes .item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--texto-principal);
        }

        .btn-anunciar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--azul-marca);
            color: var(--branco);
            border: none;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-anunciar:hover {
            background: var(--azul-marca-escuro);
        }

        /* ---------- Banner ---------- */

        .banner {
            margin: 24px 40px 0;
            padding: 36px 40px;
            border-radius: 16px;
            background: linear-gradient(
                135deg,
                var(--azul-marca),
                var(--azul-marca-escuro)
            );
            color: var(--branco);
            position: relative;
            overflow: hidden;
        }

        .banner .selo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            opacity: 0.85;
            margin-bottom: 12px;
        }

        .banner h1 {
            margin: 0 0 8px;
            font-size: 32px;
            line-height: 1.2;
            font-weight: 700;
        }

        .banner p {
            margin: 0 0 20px;
            font-size: 15px;
            opacity: 0.85;
            max-width: 360px;
        }

        .btn-anuncie-agora {
            display: inline-block;
            padding: 12px 22px;
            background: var(--branco);
            color: var(--azul-marca);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
        }

        /* ---------- Menu ---------- */

        .menu-usuario {
            padding: 24px 40px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .menu-usuario a {
            color: var(--azul-marca);
            background: var(--branco);
            border: 1px solid var(--cinza-borda);
            padding: 9px 14px;
            border-radius: 999px;
            font-size: 13px;
            transition: 0.15s;
        }

        .menu-usuario a:hover {
            background: var(--azul-marca);
            color: var(--branco);
            border-color: var(--azul-marca);
        }

        .menu-usuario .sair {
            color: #d62839;
        }

        .menu-usuario .sair:hover {
            background: #d62839;
            color: var(--branco);
            border-color: #d62839;
        }

        /* ---------- Categorias ---------- */

        .categorias {
            margin: 32px 40px 0;
        }

        .categorias h2 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 16px;
        }

        .lista-categorias {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pill {
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid var(--cinza-borda);
            background: var(--branco);
            font-size: 13px;
            color: var(--texto-principal);
        }

        /* ---------- Grid de produtos ---------- */

        .grade-produtos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            padding: 24px 40px 48px;
        }

        .produto-card {
            background: var(--branco);
            border: 1px solid var(--cinza-borda);
            border-radius: var(--raio);
            overflow: hidden;
            box-shadow: var(--sombra-card);
            transition: box-shadow 0.15s ease, transform 0.15s ease;
        }

        .produto-card:hover {
            box-shadow: var(--sombra-card-hover);
            transform: translateY(-2px);
        }

        .imagem-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: var(--cinza-fundo);
            overflow: hidden;
        }

        .imagem-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sem-imagem {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--texto-secundario);
            font-size: 14px;
        }

        .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            color: var(--branco);
            background: var(--badge-seminovo);
        }

        .info {
            padding: 14px 16px 16px;
        }

        .info h3 {
            margin: 0 0 6px;
            font-size: 15px;
            font-weight: 600;
        }

        .descricao {
            margin: 0 0 10px;
            font-size: 13px;
            color: var(--texto-secundario);
            line-height: 1.4;
        }

        .preco {
            margin: 0 0 6px;
            font-size: 16px;
            font-weight: 700;
            color: var(--preco);
        }

        .local {
            margin: 0 0 14px;
            font-size: 13px;
            color: var(--texto-secundario);
        }

        .ver-produto {
            display: inline-block;
            padding: 9px 16px;
            background: var(--azul-marca);
            color: var(--branco);
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            transition: 0.15s;
        }

        .ver-produto:hover {
            background: var(--azul-marca-escuro);
        }

        /* ---------- Responsivo ---------- */

        @media (max-width: 960px) {
            .grade-produtos {
                grid-template-columns: repeat(2, 1fr);
            }

            .acoes {
                gap: 10px;
            }
        }

        @media (max-width: 640px) {
            .topo {
                flex-wrap: wrap;
                padding: 12px 16px;
            }

            .busca {
                order: 3;
                flex-basis: 100%;
                max-width: 100%;
            }

            .acoes {
                gap: 8px;
            }

            .acoes .item {
                font-size: 12px;
            }

            .banner {
                margin: 16px;
                padding: 28px 24px;
            }

            .banner h1 {
                font-size: 26px;
            }

            .categorias {
                margin-left: 16px;
                margin-right: 16px;
            }

            .menu-usuario {
                padding-left: 16px;
                padding-right: 16px;
            }

            .grade-produtos {
                grid-template-columns: 1fr;
                padding: 24px 16px 40px;
            }
        }
    </style>
</head>

<body>

    <!-- CABEÇALHO -->
    <header class="topo">

        <a href="index.php" class="logo">
            Achô
        </a>

        <form class="busca" action="pesquisa.php" method="GET">
            <input
                type="text"
                name="pesquisa"
                placeholder="Pesquisar produto..."
            >
        </form>

        <div class="acoes">

            <a href="favoritos.php" class="item">
                ❤️ Favoritos
            </a>

            <a href="anunciarProduto.php" class="btn-anunciar">
                + Anunciar
            </a>

        </div>

    </header>


    <!-- BANNER -->
    <section class="banner">

        <div class="selo">
            🛍️ Marketplace Achô
        </div>

        <h1>
            Encontre o que você procura.
        </h1>

        <p>
            Compre e venda produtos de forma simples, rápida e segura.
        </p>

        <a href="anunciarProduto.php" class="btn-anuncie-agora">
            Anuncie agora
        </a>

    </section>


    <!-- MENU DO USUÁRIO -->
    <nav class="menu-usuario">

        <a href="formCadastro.php?id=<?= htmlspecialchars($id) ?>">
            Editar perfil
        </a>

        <a href="usuarioConta.php">
            Perfil
        </a>

        <a href="anunciarProduto.php">
            Anunciar produto
        </a>

        <a href="favoritos.php">
            Favoritos
        </a>

        <a href="avaliacoes.php">
            Avaliações
        </a>

        <a href="produtos.php">
            Produtos
        </a>

        <a href="logout.php" class="sair">
            Sair
        </a>

    </nav>


    <!-- CATEGORIAS -->
    <section class="categorias">

        <h2>
            Categorias
        </h2>

        <div class="lista-categorias">

            <a href="#" class="pill">
                Todos
            </a>

            <a href="#" class="pill">
                Eletrônicos
            </a>

            <a href="#" class="pill">
                Roupas
            </a>

            <a href="#" class="pill">
                Casa
            </a>

            <a href="#" class="pill">
                Esportes
            </a>

            <a href="#" class="pill">
                Outros
            </a>

        </div>

    </section>


    <!-- PRODUTOS -->
    <main class="grade-produtos">

        <?php if (mysqli_num_rows($resultado) > 0): ?>

            <?php while ($produto = mysqli_fetch_assoc($resultado)): ?>

                <article class="produto-card">

                    <div class="imagem-wrap">

                        <?php if (!empty($produto['fotoProduto'])): ?>

                            <img
                                src="<?= htmlspecialchars($produto['fotoProduto']) ?>"
                                alt="Foto de <?= htmlspecialchars($produto['nomeProduto']) ?>"
                            >

                        <?php else: ?>

                            <div class="sem-imagem">
                                Sem imagem
                            </div>

                        <?php endif; ?>

                        <span class="badge">
                            Produto
                        </span>

                    </div>


                    <div class="info">

                        <h3>
                            <?= htmlspecialchars($produto['nomeProduto']) ?>
                        </h3>

                        <p class="descricao">
                            <?= htmlspecialchars($produto['descricaoProduto']) ?>
                        </p>

                        <p class="preco">
                            R$
                            <?= number_format(
                                $produto['precoProduto'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </p>

                        <p class="local">
                            📍 Disponível para compra
                        </p>

                        <a
                            href="produtos.php?id=<?= (int)$produto['idProduto'] ?>"
                            class="ver-produto"
                        >
                            Ver produto
                        </a>

                    </div>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="produto-card">

                <div class="info">

                    <h3>
                        Nenhum produto encontrado
                    </h3>

                    <p class="descricao">
                        Ainda não existem produtos cadastrados.
                    </p>

                    <a
                        href="anunciarProduto.php"
                        class="ver-produto"
                    >
                        Anunciar produto
                    </a>

                </div>

            </div>

        <?php endif; ?>

    </main>

</body>
</html>
