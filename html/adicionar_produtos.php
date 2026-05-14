<?php
require_once "../conexao.php";
session_start();
$id = $_SESSION['id'];


$produtos = $_POST['produtos'];
$idUsuario = $id; 


    $sql = "INSERT INTO produtos (nomeProduto, descricaoProduto, precoProduto, statusProduto, data) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "si", $conteudo, $usuario_id);
    mysqli_stmt_execute($stmt);
    $post_id = mysqli_insert_id($conexao);


    if (!empty($_FILES['imagem']['name'])) {
        $pasta = "../imagens/";
        $nome_arquivo = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $caminho = $pasta . $nome_arquivo;

        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);

        $sql_img = "INSERT INTO imagem (imagem, posts_id) VALUES (?, ?)";
        $stmt_img = mysqli_prepare($conexao, $sql_img);
        mysqli_stmt_bind_param($stmt_img, "si", $nome_arquivo, $post_id);
        mysqli_stmt_execute($stmt_img);
    }
    header("Location: principal.php");
    exit;
?>
