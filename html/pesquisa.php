<?php
require_once "conexao.php";

$pesquisa = $_GET['pesquisa'] ?? '';
$pesquisa = "%" . $pesquisa . "%";

$sql = "
SELECT
    idProduto, nomeProduto, descricaoProduto, precoProduto, statusProduto, dataPublicacaoProduto, fotoProduto, idUsuario, nomeUsuario, usernameUsuario
    FROM produtos
    INNER JOIN usuarios
    ON produtos.usuario_idUsuario = usuarios.idUsuario
    WHERE
    produtos.nomeProduto LIKE ?
    OR produtos.descricaoProduto LIKE ?
    ORDER BY produtos.dataPublicacaoProduto DESC
";

$comando = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($comando, "ss", $pesquisa, $pesquisa);

mysqli_stmt_execute($comando);

$resultado = mysqli_stmt_get_result($comando);

while ($produto = mysqli_fetch_assoc($resultado)) {
    echo "<div class='produto'>";
    echo "<h2>" . $produto['nomeProduto'] . "</h2>";

    if (!empty($produto['fotoProduto'])) {
        echo "<img src='imagens/" . $produto['fotoProduto'] . "' width='200'><br>";
    }

    echo "<p>" . $produto['descricaoProduto'] . "</p>";
    echo "<p><strong>R$ " . number_format($produto['precoProduto'], 2, ",", ".") . "</strong></p>";
    echo "<p>Vendedor: " . $produto['nomeUsuario'] . "</p>";
    echo "<hr>";
}

mysqli_stmt_close($comando);
