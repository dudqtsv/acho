<?php


require 'funcoes.php';

verificarLogin();

$id = $_SESSION['id'];

$resultado = listarProdutos($conexao);


while ($produto = mysqli_fetch_assoc($resultado)) {

    echo "<h2>" . htmlspecialchars($produto['nomeProduto']) . "</h2>";

    echo "<p>" . htmlspecialchars($produto['descricaoProduto']) . "</p>";

    echo "<p>R$ " . number_format($produto['precoProduto'], 2, ',', '.') . "</p>";

    if (!empty($produto['fotoProduto'])) {
        echo "<img src='" . htmlspecialchars($produto['fotoProduto']) . "' 
                   width='200' 
                   alt='Foto do produto'>";
    }
echo "<br>" ;

    echo "<a href='produtos.php?id=" . $produto [ 'idProduto' ] . "'>" ;
    echo "Ver produto" ;
    echo "</a>" ;
    echo "<hr>" ;
 }
?>


<link rel="stylesheet" href="../css/home.css">

<form action="pesquisa.php" method="GET">
    <input type="text" name="pesquisa" placeholder="Pesquisar produto...">
    <button type="submit">Pesquisar</button>
</form>


<a href="formCadastro.php?id=<?= $id ?>">Editar perfil</a> <br>
<a href="usuarioConta.php">Perfil</a> <br>
<a href="anunciarProduto.php">Anunciar produto</a> <br>
<a href="favoritos.php">Favoritos</a> <br>
<a href="avaliacoes.php">Avaliações</a> <br>
<a href="produtos.php">produtos</a> <br>



<br><br><br><br><br><br><br>



<a href="logout.php">Sair da sessão</a> <br>
