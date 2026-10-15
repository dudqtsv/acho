<?php
require_once "./conexao.php";
header('Content-Type: application/json; charset=utf-8');

$uf = isset($_GET['uf']) ? strtoupper(substr($_GET['uf'], 0, 2)) : "";

$sql = "SELECT codigo, nome FROM municipios WHERE uf = ? ORDER BY nome";
$comando = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($comando, 's', $uf);
mysqli_stmt_execute($comando);
$resultados = mysqli_stmt_get_result($comando);

$cidades = [];
while ($linha = mysqli_fetch_assoc($resultados)) {
    $cidades[] = $linha;
}

echo json_encode($cidades, JSON_UNESCAPED_UNICODE);