<?php
require_once "conexao.php";
require "funcoes.php";
verificarLogin();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id'];

if (!isset($_POST['idProduto'], $_POST['comentario'], $_POST['nota'])) {
    die("Dados incompletos.");
}

$idProduto  = $_POST['idProduto'];
$comentario = trim($_POST['comentario']);
$nota       = $_POST['nota'];

if (!is_numeric($idProduto) || !is_numeric($nota)) {
    die("Dados inválidos.");
}

if ($nota < 1 || $nota > 5) {
    die("A nota deve ser entre 1 e 5.");
}

if (empty($comentario)) {
    die("O comentário não pode estar vazio.");
}


$sql = "INSERT INTO avaliacoes (idUsuario, idProduto, comentario, nota, dataAvaliacao)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            comentario = VALUES(comentario),
            nota = VALUES(nota),
            dataAvaliacao = NOW()";

$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    die("Erro ao preparar a query: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param(
    $stmt, "iisi", $idUsuario, $idProduto, $comentario, $nota
);

if (!mysqli_stmt_execute($stmt)) {
    die("Erro ao salvar avaliação: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conexao);

header("Location: produtos.php?id=$idProduto");
exit();
?>