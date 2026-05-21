<?php
require_once 'conexao.php';
require 'verificarLogin.php';
verificarLogin();
$id = $_SESSION['id'];

?>


<a href="formCadastro.php?id=<?= $id ?>">editar perfil</a>
<a href="logout.php">sair da sessão</a>