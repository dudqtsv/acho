<?php
require_once "./conexao.php";
require_once "./funcoes.php";
verificarLogin();

$idUsuario = (int) $_SESSION['id'];

$sql = "SELECT p.idProduto, p.nomeProduto, p.descricaoProduto, p.precoProduto, p.fotoProduto
        FROM favoritos f
        INNER JOIN produtos p ON f.idProduto = p.idProduto
        WHERE f.idUsuario = ?
        ORDER BY f.id DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$favoritos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meus favoritos</title>
<style>
    :root {
        --azul-marca: #2f3a8f;
        --texto-secundario: #6b6b6b;
        --cinza-fundo: #f4f5f7;
        --cinza-borda: #e4e4e7;
        --vermelho-coracao: #e63950;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        background: var(--cinza-fundo);
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1c1c1c;
    }
    .container { max-width: 1200px; margin: 40px auto; padding: 0 24px; }
    .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 10px; }
    .topo h1 { margin: 0; color: var(--azul-marca); font-size: 26px; }
    .voltar { color: var(--azul-marca); text-decoration: none; font-weight: 600; }
    .voltar:hover { text-decoration: underline; }
    .grade { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; }
    .produto-card {
        background: #fff; border: 1px solid var(--cinza-borda); border-radius: 10px;
        overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08);
        transition: opacity .2s ease, transform .2s ease;
    }
    .produto-card.saindo { opacity: 0; transform: scale(.95); }
    .imagem-wrap { position: relative; width: 100%; aspect-ratio: 4 / 3; background: var(--cinza-fundo); overflow: hidden; }
    .imagem-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .sem-imagem { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--texto-secundario); font-size: 14px; }
    .info { padding: 14px 16px 16px; }
    .info h3 { margin: 0 0 6px; font-size: 15px; }
    .descricao {
        margin: 0 0 10px; font-size: 13px; color: var(--texto-secundario);
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .preco { margin: 0 0 12px; font-weight: 700; font-size: 18px; }
    .ver-produto {
        display: inline-block; padding: 8px 14px; background: var(--azul-marca);
        color: #fff; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600;
    }
    .btn-favorito {
        position: absolute; top: 10px; right: 10px; width: 36px; height: 36px; border: none;
        border-radius: 50%; background: rgba(255,255,255,.92); box-shadow: 0 1px 4px rgba(0,0,0,.2);
        display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;
        transition: transform .12s ease;
    }
    .btn-favorito:hover { transform: scale(1.12); }
    .btn-favorito svg { width: 20px; height: 20px; display: block; fill: none; stroke: var(--vermelho-coracao); stroke-width: 2; stroke-linejoin: round; }
    .btn-favorito.ativo svg { fill: var(--vermelho-coracao); }
    .vazio {
        background: #fff; border: 1px dashed var(--cinza-borda); border-radius: 12px;
        padding: 40px 20px; text-align: center; color: var(--texto-secundario);
    }
    .vazio a { color: var(--azul-marca); font-weight: 600; }
</style>
</head>
<body>
<div class="container">
    <div class="topo">
        <h1>Meus favoritos</h1>
        <a class="voltar" href="home.php">← Voltar para o início</a>
    </div>

    <?php if (count($favoritos) === 0): ?>
        <div class="vazio">
            Você ainda não favoritou nenhum produto.<br>
            Toque no coração de um produto na <a href="home.php">página inicial</a> para salvá-lo aqui.
        </div>
    <?php else: ?>
        <div class="grade" id="grade">
            <?php foreach ($favoritos as $produto): ?>
                <article class="produto-card">
                    <div class="imagem-wrap">
                        <?php if (!empty($produto['fotoProduto'])): ?>
                            <img src="fotos/<?= htmlspecialchars(basename($produto['fotoProduto'])) ?>"
                                 alt="Foto de <?= htmlspecialchars($produto['nomeProduto']) ?>">
                        <?php else: ?>
                            <div class="sem-imagem">Sem imagem</div>
                        <?php endif; ?>

                        <button type="button" class="btn-favorito ativo"
                                data-id="<?= (int)$produto['idProduto'] ?>"
                                title="Remover dos favoritos" aria-label="Remover dos favoritos">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 20.5l-1.4-1.3C5.6 14.7 2.5 11.9 2.5 8.5 2.5 5.7 4.7 3.5 7.5 3.5c1.6 0 3.1.7 4.5 2.2 1.4-1.5 2.9-2.2 4.5-2.2 2.8 0 5 2.2 5 5 0 3.4-3.1 6.2-8.1 10.7L12 20.5z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="info">
                        <h3><?= htmlspecialchars($produto['nomeProduto']) ?></h3>
                        <p class="descricao"><?= htmlspecialchars($produto['descricaoProduto'] ?? '') ?></p>
                        <p class="preco">R$ <?= number_format($produto['precoProduto'], 2, ',', '.') ?></p>
                        <a class="ver-produto" href="produto.php?id=<?= (int)$produto['idProduto'] ?>">Ver produto</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Ao desfavoritar, remove o card da lista
    document.querySelectorAll('.btn-favorito').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var dados = new URLSearchParams({ idProduto: btn.dataset.id });
            fetch('favoritar.php', { method: 'POST', body: dados })
                .then(function (r) { return r.text(); })
                .then(function (estado) {
                    if (estado.trim() !== '0') return;
                    var card = btn.closest('.produto-card');
                    card.classList.add('saindo');
                    setTimeout(function () {
                        card.remove();
                        if (!document.querySelector('.produto-card')) location.reload();
                    }, 200);
                });
        });
    });
</script>
</body>
</html>