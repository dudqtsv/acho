<?php
require_once "conexao.php";

$pesquisa = $_GET['pesquisa'] ?? '';
$pesquisa = "%".$pesquisa."%";

$sql = "
SELECT
    p.idProduto,
    p.nomeProduto,
    p.descricaoProduto,
    p.precoProduto,
    p.statusProduto,
    p.dataPublicacaoProduto,
    p.fotoProduto,
    u.idUsuario,
    u.nomeUsuario,
    u.usernameUsuario
FROM produtos p
INNER JOIN usuarios u
    ON p.usuario_idUsuario = u.idUsuario
WHERE
    p.nomeProduto LIKE ?
    OR p.descricaoProduto LIKE ?
ORDER BY p.dataPublicacaoProduto DESC
";

$comando = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($comando, "ss", $pesquisa, $pesquisa);

mysqli_stmt_execute($comando);

$resultado = mysqli_stmt_get_result($comando);

while($produto = mysqli_fetch_assoc($resultado)){
    echo "<div class='produto'>";
    echo "<h2>".$produto['nomeProduto']."</h2>";

    if(!empty($produto['fotoProduto'])){
        echo "<img src='imagens/".$produto['fotoProduto']."' width='200'><br>";
    }

    echo "<p>".$produto['descricaoProduto']."</p>";
    echo "<p><strong>R$ ".number_format($produto['precoProduto'],2,",",".")."</strong></p>";
    echo "<p>Vendedor: ".$produto['nomeUsuario']."</p>";
    echo "<hr>";
}

mysqli_stmt_close($comando);
?>
