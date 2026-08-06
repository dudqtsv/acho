<?php
require_once 'conexao.php';
require 'verificarLogin.php';
verificarLogin();
$id = $_SESSION['id'];

include "header.php";
?>


<form action="pesquisa.php" method="GET">
    <input type="text" name="pesquisa" placeholder="Pesquisar produto...">
    <button type="submit">Pesquisar</button>
</form>


<a href="formCadastro.php?id=<?= $id ?>">Editar perfil</a> <br>
<a href="logout.php">Sair da sessão</a> <br>
<a href="usuarioConta.php">Perfil</a> <br>
<a href="anunciarProduto.php">Anunciar produto</a> <br>
<a href="favoritos.php">Favoritos</a> <br>