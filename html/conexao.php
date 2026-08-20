<?php

$host = "mysql_lab";
$user = "root";
$password = "123";
$bd = "banco";

$conexao = new mysqli($host, $user, $password, $bd);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}