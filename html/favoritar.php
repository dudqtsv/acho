<?php
require_once "./conexao.php";
require 'funcoes.php';
verificarLogin();

$idUsuario = (int) $_SESSION['id'];
$idProduto = isset($_POST['idProduto']) ? (int) $_POST['idProduto'] : 0;

if ($idProduto <= 0) {
    http_response_code(400);
    echo "erro";
    exit;
}

// alterna: se já favoritou, remove; senão, adiciona
if (jaFavoritou($conexao, $idProduto, $idUsuario)) {
    $sql = "DELETE FROM favoritos WHERE idProduto = ? AND idUsuario = ?";
    $novoEstado = "0";
} else {
    $sql = "INSERT INTO favoritos (idProduto, idUsuario) VALUES (?, ?)";
    $novoEstado = "1";
}

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ii", $idProduto, $idUsuario);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conexao);

// devolve o NOVO estado: "1" = favoritado, "0" = não favoritado
echo $novoEstado;