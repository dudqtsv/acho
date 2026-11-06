<?php
require_once "./funcoes.php";
verificarLogin();

$idUsuario = (int) $_SESSION['id'];

$sql = "SELECT idProduto, nomeProduto, categoriaProduto, descricaoProduto, condicaoProduto,
               precoProduto, statusProduto, dataPublicacaoProduto, fotoProduto
        FROM produtos
        WHERE usuario_idUsuario = ?
        ORDER BY dataPublicacaoProduto DESC, idProduto DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$anuncios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$total = count($anuncios);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meus anúncios</title>
<style>
    :root {
        --azul-marca: #2f3a8f;
        --azul-marca-escuro: #232a6b;
        --texto-secundario: #6b6b6b;
        --cinza-fundo: #f4f5f7;
        --cinza-borda: #e4e4e7;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        background: var(--cinza-fundo);
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1c1c1c;
    }
    a { text-decoration: none; }
    .container { max-width: 1200px; margin: 40px auto; padding: 0 24px; }

    .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 10px; }
    .topo h1 { margin: 0; color: var(--azul-marca); font-size: 26px; }
    .links { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .voltar { color: var(--azul-marca); font-weight: 600; }
    .voltar:hover { text-decoration: underline; }
    .btn-anunciar {
        background: var(--azul-marca); color: #fff; padding: 9px 16px;
        border-radius: 8px; font-size: 14px; font-weight: 600; transition: background .15s;
    }
    .btn-anunciar:hover { background: var(--azul-marca-escuro); }
    .contador { margin: 0 0 24px; color: var(--texto-secundario); font-size: 14px; }

    .grade { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; }
    .produto-card {
        background: #fff; border: 1px solid var(--cinza-borda); border-radius: 10px;
        overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08);
        display: flex; flex-direction: column;
    }
    .imagem-wrap { position: relative; width: 100%; aspect-ratio: 4 / 3; background: var(--cinza-fundo); overflow: hidden; }
    .imagem-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .sem-imagem { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--texto-secundario); font-size: 14px; }
    .badge {
        position: absolute; top: 10px; left: 10px; padding: 3px 11px; border-radius: 999px;
        background: rgba(255,255,255,.92); color: #1c1c1c; font-size: 12px; font-weight: 500;
    }
    .badge.vendido { left: auto; right: 10px; background: var(--azul-marca-escuro); color: #fff; }
    .produto-card.vendido .imagem-wrap img { filter: grayscale(1); opacity: .7; }

    .info { padding: 14px 16px 16px; display: flex; flex-direction: column; flex: 1; }
    .info h3 { margin: 0 0 4px; font-size: 15px; }
    .categoria { margin: 0 0 6px; font-size: 12px; color: var(--azul-marca); font-weight: 600; }
    .descricao {
        margin: 0 0 10px; font-size: 13px; color: var(--texto-secundario);
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .preco { margin: 0 0 6px; font-weight: 700; font-size: 18px; }
    .data { margin: 0 0 12px; font-size: 12px; color: var(--texto-secundario); }
    .ver-produto {
        margin-top: auto; align-self: flex-start; padding: 8px 14px; background: var(--azul-marca);
        color: #fff; border-radius: 8px; font-size: 13px; font-weight: 600;
    }
    .ver-produto:hover { background: var(--azul-marca-escuro); }

    .vazio {
        background: #fff; border: 1px dashed var(--cinza-borda); border-radius: 12px;
        padding: 40px 20px; text-align: center; color: var(--texto-secundario);
    }
    .vazio a { color: var(--azul-marca); font-weight: 600; }

    a:focus-visible { outline: 3px solid var(--azul-marca); outline-offset: 2px; }
</style>
</head>
<body>
<div class="container">

    <div class="topo">
        <h1>Meus anúncios</h1>
        <div class="links">
            <a class="voltar" href="home.php">← Voltar para o início</a>
            <a class="btn-anunciar" href="anunciarProduto.php">+ Anunciar</a>
        </div>
    </div>

    <p class="contador">
        <?= $total === 1 ? '1 anúncio publicado' : $total . ' anúncios publicados' ?>
    </p>

    <?php if ($total === 0): ?>
        <div class="vazio">
            Você ainda não publicou nenhum anúncio.<br>
            <a href="anunciarProduto.php">Anuncie seu primeiro produto</a>
        </div>
    <?php else: ?>
        <div class="grade">
            <?php foreach ($anuncios as $anuncio): ?>
                <?php $vendido = mb_strtolower((string) $anuncio['statusProduto']) === 'vendido'; ?>
                <article class="produto-card<?= $vendido ? ' vendido' : '' ?>">
                    <div class="imagem-wrap">
                        <?php if (!empty($anuncio['fotoProduto'])): ?>
                            <img src="fotos/<?= htmlspecialchars(basename($anuncio['fotoProduto'])) ?>"
                                 alt="Foto de <?= htmlspecialchars($anuncio['nomeProduto']) ?>">
                        <?php else: ?>
                            <div class="sem-imagem">Sem imagem</div>
                        <?php endif; ?>

                        <?php if (!empty($anuncio['condicaoProduto'])): ?>
                            <span class="badge"><?= htmlspecialchars($anuncio['condicaoProduto']) ?></span>
                        <?php endif; ?>
                        <?php if ($vendido): ?>
                            <span class="badge vendido">Vendido</span>
                        <?php endif; ?>
                    </div>

                    <div class="info">
                        <h3><?= htmlspecialchars($anuncio['nomeProduto']) ?></h3>
                        <?php if (!empty($anuncio['categoriaProduto'])): ?>
                            <p class="categoria"><?= htmlspecialchars($anuncio['categoriaProduto']) ?></p>
                        <?php endif; ?>
                        <p class="descricao"><?= htmlspecialchars($anuncio['descricaoProduto'] ?? '') ?></p>
                        <p class="preco">R$ <?= number_format($anuncio['precoProduto'], 2, ',', '.') ?></p>
                        <?php if (!empty($anuncio['dataPublicacaoProduto'])): ?>
                            <p class="data">Publicado em <?= date('d/m/Y', strtotime($anuncio['dataPublicacaoProduto'])) ?></p>
                        <?php endif; ?>
                        <a class="ver-produto" href="produto.php?id=<?= (int) $anuncio['idProduto'] ?>">Ver anúncio</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
</body>
</html>