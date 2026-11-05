<?php

session_start();

require './conexao.php';

$username = $_POST['username'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE usernameUsuario = ? AND senhaUsuario = ?";

$comando = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($comando, 'ss', $username, $senha);

mysqli_stmt_execute($comando);

$resultados = mysqli_stmt_get_result($comando);

$quantidade = mysqli_num_rows($resultados);

if ($quantidade == 0) {
    header('Location: ./index.php?msg=erro');
    exit;
}

$usuario = mysqli_fetch_assoc($resultados);

$id = $usuario['idUsuario'];

$_SESSION['idUsuario'] = $id;
$_SESSION['id'] = $usuario['idUsuario'];
$_SESSION['nome'] = $usuario['nomeUsuario'];
$_SESSION['foto'] = $usuario['fotoUsuario'];

header('Location: ./home.php');
exit;