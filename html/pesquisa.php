<?php
require_once "./funcoes.php";
verificarLogin();

$idUsuario = (int) $_SESSION['id'];

$termo = trim($_GET['pesquisa'] ?? '');
$like  = "%" . $termo . "%";

$sql = "
SELECT
    idProduto, nomeProduto, categoriaProduto, descricaoProduto, condicaoProduto,
    precoProduto, statusProduto, dataPublicacaoProduto, fotoProduto, idUsuario, nomeUsuario, usernameUsuario
    FROM produtos
    INNER JOIN usuarios
    ON produtos.usuario_idUsuario = usuarios.idUsuario
    WHERE
    produtos.nomeProduto LIKE ?
    OR produtos.descricaoProduto LIKE ?
    ORDER BY produtos.dataPublicacaoProduto DESC
";

$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, "ss", $like, $like);
mysqli_stmt_execute($comando);
$produtos = mysqli_stmt_get_result($comando)->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($comando);

$total = count($produtos);

// ids dos produtos que o usuário já favoritou (uma consulta só)
$favoritados = [];
$stmtFav = $conexao->prepare("SELECT idProduto FROM favoritos WHERE idUsuario = ?");
$stmtFav->bind_param("i", $idUsuario);
$stmtFav->execute();
foreach ($stmtFav->get_result()->fetch_all(MYSQLI_ASSOC) as $f) {
    $favoritados[(int) $f['idProduto']] = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesquisa<?= $termo !== '' ? ' — ' . htmlspecialchars($termo) : '' ?> | Achô</title>
<style>
    :root {
        --azul-marca: #2f3a8f;
        --azul-marca-escuro: #232a6b;
        --texto-principal: #1c1c1c;
        --texto-secundario: #6b6b6b;
        --branco: #ffffff;
        --cinza-fundo: #f4f5f7;
        --cinza-borda: #e4e4e7;
        --vermelho-coracao: #e63950;
        --badge-seminovo: #6b7280;
        --sombra-card: 0 1px 3px rgba(0, 0, 0, 0.08);
        --sombra-card-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
        --raio: 10px;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--texto-principal);
        background: var(--cinza-fundo);
    }
    a { text-decoration: none; color: inherit; }

    /* ---------- Cabeçalho ---------- */
    .topo {
        display: flex; align-items: center; justify-content: space-between;
        gap: 24px; padding: 14px 40px;
        background: var(--branco); border-bottom: 1px solid var(--cinza-borda);
    }
    .logo {
        display: flex; align-items: center; justify-content: center;
        width: 42px; height: 42px; border-radius: 50%;
        background: var(--azul-marca); color: var(--branco);
        font-weight: 700; font-size: 15px; flex-shrink: 0;
    }
    .busca { flex: 1; max-width: 520px; position: relative; }
    .busca input {
        width: 100%; padding: 10px 16px;
        border: 1px solid var(--cinza-borda); border-radius: 999px;
        background: var(--cinza-fundo); font-size: 14px; outline: none;
    }
    .busca input:focus { border-color: var(--azul-marca); background: var(--branco); }
    .acoes { display: flex; align-items: center; gap: 24px; white-space: nowrap; }
    .acoes .item { display: flex; align-items: center; gap: 6px; font-size: 14px; }
    .btn-anunciar {
        display: flex; align-items: center; gap: 8px; padding: 10px 20px;
        background: var(--azul-marca); color: var(--branco); border: none;
        border-radius: 999px; font-size: 14px; font-weight: 600; cursor: pointer;
    }
    .btn-anunciar:hover { background: var(--azul-marca-escuro); }

    /* ---------- Conteúdo ---------- */
    .container { max-width: 1200px; margin: 40px auto; padding: 0 24px; }
    .cabecalho-pagina {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 10px; margin-bottom: 8px;
    }
    .cabecalho-pagina h1 { margin: 0; color: var(--azul-marca); font-size: 26px; word-break: break-word; }
    .voltar { color: var(--azul-marca); font-weight: 600; }
    .voltar:hover { text-decoration: underline; }
    .contador { margin: 0 0 24px; color: var(--texto-secundario); font-size: 14px; }

    /* ---------- Grade de produtos ---------- */
    .grade { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; }
    .produto-card {
        background: var(--branco); border: 1px solid var(--cinza-borda);
        border-radius: var(--raio); overflow: hidden; box-shadow: var(--sombra-card);
        display: flex; flex-direction: column;
        transition: box-shadow 0.15s ease, transform 0.15s ease;
    }
    .produto-card:hover { box-shadow: var(--sombra-card-hover); transform: translateY(-2px); }
    .imagem-wrap {
        position: relative; width: 100%; aspect-ratio: 4 / 3;
        background: var(--cinza-fundo); overflow: hidden;
    }
    .imagem-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .sem-imagem {
        width: 100%; height: 100%; display: flex; align-items: center;
        justify-content: center; color: var(--texto-secundario); font-size: 14px;
    }
    .badge {
        position: absolute; top: 12px; left: 12px; padding: 4px 10px;
        border-radius: 6px; font-size: 11px; font-weight: 700;
        color: var(--branco); background: var(--badge-seminovo);
    }
    .produto-card.vendido .imagem-wrap img { filter: grayscale(1); opacity: .7; }

    .btn-favorito {
        position: absolute; top: 10px; right: 10px; width: 36px; height: 36px; border: none;
        border-radius: 50%; background: rgba(255, 255, 255, 0.92); box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;
        transition: transform 0.12s ease;
    }
    .btn-favorito:hover { transform: scale(1.12); }
    .btn-favorito svg { width: 20px; height: 20px; display: block; fill: none; stroke: var(--vermelho-coracao); stroke-width: 2; stroke-linejoin: round; }
    .btn-favorito.ativo svg { fill: var(--vermelho-coracao); }

    .info { padding: 14px 16px 16px; display: flex; flex-direction: column; flex: 1; }
    .info h3 { margin: 0 0 4px; font-size: 15px; font-weight: 600; }
    .categoria { margin: 0 0 6px; font-size: 12px; color: var(--azul-marca); font-weight: 600; }
    .descricao {
        margin: 0 0 10px; font-size: 13px; color: var(--texto-secundario); line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .preco { margin: 0 0 6px; font-size: 18px; font-weight: 700; }
    .vendedor { margin: 0 0 14px; font-size: 13px; color: var(--texto-secundario); }
    .ver-produto {
        margin-top: auto; align-self: flex-start; padding: 9px 16px;
        background: var(--azul-marca); color: var(--branco);
        border-radius: 999px; font-size: 13px; font-weight: 600; transition: 0.15s;
    }
    .ver-produto:hover { background: var(--azul-marca-escuro); }

    .vazio {
        background: var(--branco); border: 1px dashed var(--cinza-borda); border-radius: 12px;
        padding: 40px 20px; text-align: center; color: var(--texto-secundario);
    }
    .vazio a { color: var(--azul-marca); font-weight: 600; }

    a:focus-visible, button:focus-visible { outline: 3px solid var(--azul-marca); outline-offset: 2px; }

    /* ---------- Responsivo ---------- */
    @media (max-width: 960px) {
        .acoes { gap: 10px; }
    }
    @media (max-width: 640px) {
        .topo { flex-wrap: wrap; padding: 12px 16px; }
        .busca { order: 3; flex-basis: 100%; max-width: 100%; }
        .acoes { gap: 8px; }
        .acoes .item { font-size: 12px; }
        .container { margin: 24px auto; padding: 0 16px; }
    }
</style>
</head>
<body>

<!-- CABEÇALHO -->
<header class="topo">
    <a href="home.php" class="logo">Achô</a>

    <form class="busca" action="pesquisa.php" method="GET">
        <input type="text" name="pesquisa" placeholder="Pesquisar produto..."
               value="<?= htmlspecialchars($termo) ?>">
    </form>

    <div class="acoes">
        <a href="favoritos.php" class="item">♥︎ Favoritos</a>
        <a href="meusAnuncios.php" class="item">📦 Meus anúncios</a>
        <a href="anunciarProduto.php" class="btn-anunciar">+ Anunciar</a>
    </div>
</header>

<div class="container">

    <div class="cabecalho-pagina">
        <h1>
            <?= $termo !== '' ? 'Resultados para “' . htmlspecialchars($termo) . '”' : 'Todos os produtos' ?>
        </h1>
        <a class="voltar" href="home.php">← Voltar para o início</a>
    </div>

    <p class="contador">
        <?= $total === 1 ? '1 produto encontrado' : $total . ' produtos encontrados' ?>
    </p>

    <?php if ($total === 0): ?>
        <div class="vazio">
            Nenhum produto encontrado para sua pesquisa.<br>
            Tente outras palavras ou volte para a <a href="home.php">página inicial</a>.
        </div>
    <?php else: ?>
        <div class="grade">
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $vendido   = mb_strtolower((string) $produto['statusProduto']) === 'vendido';
                    $favoritou = isset($favoritados[(int) $produto['idProduto']]);
                    $rotulo    = !empty($produto['statusProduto'])
                        ? $produto['statusProduto']
                        : ($produto['condicaoProduto'] ?? 'Produto');
                ?>
                <article class="produto-card<?= $vendido ? ' vendido' : '' ?>">
                    <div class="imagem-wrap">
                        <?php if (!empty($produto['fotoProduto'])): ?>
                            <img src="fotos/<?= htmlspecialchars(basename($produto['fotoProduto'])) ?>"
                                 alt="Foto de <?= htmlspecialchars($produto['nomeProduto']) ?>">
                        <?php else: ?>
                            <div class="sem-imagem">Sem imagem</div>
                        <?php endif; ?>

                        <span class="badge"><?= htmlspecialchars($rotulo) ?></span>

                        <button type="button" class="btn-favorito<?= $favoritou ? ' ativo' : '' ?>"
                                data-id="<?= (int) $produto['idProduto'] ?>"
                                title="<?= $favoritou ? 'Remover dos favoritos' : 'Favoritar' ?>"
                                aria-label="Favoritar">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 20.5l-1.4-1.3C5.6 14.7 2.5 11.9 2.5 8.5 2.5 5.7 4.7 3.5 7.5 3.5c1.6 0 3.1.7 4.5 2.2 1.4-1.5 2.9-2.2 4.5-2.2 2.8 0 5 2.2 5 5 0 3.4-3.1 6.2-8.1 10.7L12 20.5z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="info">
                        <h3><?= htmlspecialchars($produto['nomeProduto']) ?></h3>
                        <?php if (!empty($produto['categoriaProduto'])): ?>
                            <p class="categoria"><?= htmlspecialchars($produto['categoriaProduto']) ?></p>
                        <?php endif; ?>
                        <p class="descricao"><?= htmlspecialchars($produto['descricaoProduto'] ?? '') ?></p>
                        <p class="preco">R$ <?= number_format($produto['precoProduto'], 2, ',', '.') ?></p>
                        <p class="vendedor">Vendedor: <?= htmlspecialchars($produto['nomeUsuario']) ?></p>
                        <a class="ver-produto" href="produto.php?id=<?= (int) $produto['idProduto'] ?>">Ver produto</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    document.querySelectorAll('.btn-favorito').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var dados = new URLSearchParams({ idProduto: btn.dataset.id });
            fetch('favoritar.php', { method: 'POST', body: dados })
                .then(function (r) { return r.text(); })
                .then(function (estado) {
                    var favoritou = estado.trim() === '1';
                    btn.classList.toggle('ativo', favoritou);
                    btn.title = favoritou ? 'Remover dos favoritos' : 'Favoritar';
                });
        });
    });
</script>

</body>
</html>