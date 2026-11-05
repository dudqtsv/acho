<?php
require_once "./conexao.php";
require_once "./funcoes.php";
verificarLogin();

$idUsuario = (int)$_SESSION['id'];

$sql = "SELECT a.id, a.comentario, a.nota, a.dataAvaliacao,
               p.idProduto, p.nomeProduto, p.fotoProduto
        FROM avaliacoes a
        INNER JOIN produtos p ON a.idProduto = p.idProduto
        WHERE a.idUsuario = ?
        ORDER BY a.dataAvaliacao DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$avaliacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total = count($avaliacoes);
$media = $total > 0 ? number_format(array_sum(array_column($avaliacoes, 'nota')) / $total, 1, ',', '') : "0,0";

function estrelas($nota) {
    $nota = max(0, min(5, (int)$nota));
    return str_repeat("★", $nota) . str_repeat("☆", 5 - $nota);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Minhas avaliações</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    :root {
        --azul-escuro: #1B2A6B;
        --cinza-borda: #d9dce3;
        --cinza-fundo: #f4f5f8;
        --cinza-texto: #6b7280;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        background: #ececec;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1f2430;
    }
    .container { max-width: 900px; margin: 40px auto; padding: 0 16px; }
    .topo {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
    }
    .topo h1 { margin: 0; color: var(--azul-escuro); font-size: 26px; }
    .voltar { color: var(--azul-escuro); text-decoration: none; font-weight: 600; }
    .voltar:hover { text-decoration: underline; }
    .resumo {
        background: var(--cinza-fundo); border-radius: 12px; padding: 18px 24px;
        margin-bottom: 24px; display: flex; gap: 40px; flex-wrap: wrap;
    }
    .resumo strong { display: block; font-size: 24px; color: var(--azul-escuro); }
    .resumo span { color: var(--cinza-texto); font-size: 14px; }
    .card {
        background: #fff; border: 1px solid var(--cinza-borda); border-radius: 12px;
        padding: 16px; margin-bottom: 16px; display: flex; gap: 16px;
    }
    .card img {
        width: 110px; height: 110px; object-fit: cover; border-radius: 8px;
        background: var(--cinza-fundo); flex-shrink: 0;
    }
    .card .conteudo { flex: 1; min-width: 0; }
    .card h3 { margin: 0 0 4px; font-size: 18px; }
    .card h3 a { color: var(--azul-escuro); text-decoration: none; }
    .card h3 a:hover { text-decoration: underline; }
    .estrelas { color: #f5a623; font-size: 18px; letter-spacing: 2px; }
    .data { color: var(--cinza-texto); font-size: 13px; margin-left: 8px; }
    .comentario { margin: 8px 0 0; line-height: 1.5; word-wrap: break-word; }
    .vazio {
        background: #fff; border: 1px dashed var(--cinza-borda); border-radius: 12px;
        padding: 40px 20px; text-align: center; color: var(--cinza-texto);
    }
    .vazio a { color: var(--azul-escuro); font-weight: 600; }
    @media (max-width: 520px) {
        .card { flex-direction: column; }
        .card img { width: 100%; height: 180px; }
    }
</style>
</head>
<body>
<div class="container">
    <div class="topo">
        <h1>Minhas avaliações</h1>
        <a class="voltar" href="home.php">← Voltar para o início</a>
    </div>

    <div class="resumo">
        <div><strong><?= $total ?></strong><span>avaliações feitas</span></div>
        <div><strong><?= $media ?> ★</strong><span>nota média que você deu</span></div>
    </div>

    <?php if ($total === 0): ?>
        <div class="vazio">
            Você ainda não avaliou nenhum produto.<br>
            <a href="home.php">Ver produtos</a>
        </div>
    <?php else: ?>
        <?php foreach ($avaliacoes as $av): ?>
            <div class="card">
                <img src="fotos/<?= htmlspecialchars(basename($av['fotoProduto'] ?: 'generico.png')) ?>"
                     alt="<?= htmlspecialchars($av['nomeProduto']) ?>">
                <div class="conteudo">
                    <h3>
                        <a href="produto.php?id=<?= (int)$av['idProduto'] ?>">
                            <?= htmlspecialchars($av['nomeProduto']) ?>
                        </a>
                    </h3>
                    <div>
                        <span class="estrelas" title="Nota <?= (int)$av['nota'] ?> de 5"><?= estrelas($av['nota']) ?></span>
                        <span class="data"><?= date("d/m/Y", strtotime($av['dataAvaliacao'])) ?></span>
                    </div>
                    <p class="comentario"><?= nl2br(htmlspecialchars($av['comentario'] ?? '')) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>