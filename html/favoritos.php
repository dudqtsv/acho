<?php

require_once "./conexao.php";
require_once './funcoes.php';

verificarLogin();

$idUsuario = $_SESSION['id'];
$idProduto = $_GET['idProduto'] ?? null;

if (!$idProduto) {
    die("Produto não informado.");
}

// verifica se já favoritou
$sql = "SELECT * FROM favoritos WHERE idProduto = ? AND idUsuario = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idProduto, $idUsuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ja_favoritou = mysqli_num_rows($result) > 0;

$sql_total = "SELECT COUNT(*) AS total FROM favoritos WHERE idProduto = ?";
$stmt_total = mysqli_prepare($conexao, $sql_total);
mysqli_stmt_bind_param($stmt_total, "i", $idProduto);
mysqli_stmt_execute($stmt_total);
$result_total = mysqli_stmt_get_result($stmt_total);
$total = mysqli_fetch_assoc($result_total)['total'];

echo "
  <form method='get' action='favoritar.php'>
    <input type='hidden' name='idProduto' value='$idProduto'>
    <button type='submit' style='background: none; border: none; font-size: 20px; cursor: pointer;'>
      " . ($ja_favoritou ? '♥' : '♡') . "
    </button>
    <span style='font-size: 14px;'>$total</span>
  </form>
";

mysqli_close($conexao);
?>