<?php
require 'funcoes.php';

verificarLogin();

$id = $_SESSION['id'];
?>


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