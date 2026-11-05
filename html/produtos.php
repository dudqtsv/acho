<?php
require 'funcoes.php';
verificarLogin();

if (isset($_GET['prodid'])) {
    $idUsuario = (int) $_GET['prodid'];

    echo "<h2>Meus produtos</h2>";

    $produtos = listarProdutosUsuario($conexao, $idUsuario);

    if (empty($produtos)) {
        echo "<p>Nenhum produto cadastrado.</p>";
    }

    foreach ($produtos as $produto) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($produto['nomeProduto']) . "</h3>";
        echo "<p>Categoria: " . htmlspecialchars($produto['categoriaProduto']) . "</p>";
        echo "<p>" . htmlspecialchars($produto['descricaoProduto']) . "</p>";
        echo "<p>Condição: " . htmlspecialchars($produto['condicaoProduto']) . "</p>";
        echo "<p>Preço: R$ " . number_format($produto['precoProduto'], 2, ',', '.') . "</p>";
        echo "<p>Status: " . htmlspecialchars($produto['statusProduto']) . "</p>";
        echo "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/meusProdutos.css">
</head>
<body>
    
</body>
</html>