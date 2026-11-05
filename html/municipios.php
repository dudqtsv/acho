<?php
// Devolve em JSON os municípios de um estado: municipios.php?uf=GO
require_once "./conexao.php";

header('Content-Type: application/json; charset=utf-8');

$uf = strtoupper(trim($_GET['uf'] ?? ''));

if (!preg_match('/^[A-Z]{2}$/', $uf)) {
    echo '[]';
    exit;
}

$sql = "SELECT codigo, nome FROM municipios WHERE uf = ? ORDER BY nome";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $uf);
$stmt->execute();
$lista = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($lista, JSON_UNESCAPED_UNICODE);