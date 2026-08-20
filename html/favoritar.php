<?php
require_once "./conexao.php";
require 'funcoes.php';
verificarLogin();
$id = $_SESSION['id'];

$idProduto = $_GET['idProduto'];
$idUsuario = $_GET['idUsuario'];



//verifica se ja favoritou
$sql = "SELECT * FROM favoritos WHERE idProduto = ? AND idUsuario = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idProduto, $idUsuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ja_favoritou = mysqli_num_rows($result) > 0;

if ($ja_favoritou) {
    $sql = "DELETE FROM favoritos WHERE idProduto = ? AND idUsuario = ?";
} else {
    $sql = "INSERT INTO favoritos (idProduto, idUsuario) VALUES (?, ?)";
}
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idProduto, $idUsuario);
mysqli_stmt_execute($stmt);

header("Location: favoritos.php?idProduto=$idProduto&idUsuario=$idUsuario");
