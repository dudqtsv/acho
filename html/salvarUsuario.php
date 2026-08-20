<?php
session_start();
require_once "./conexao.php";

$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$username = $_POST['username'];
$senha = $_POST['senha'];
$municipio = $_POST['municipio'];
$foto = $_FILES['foto'];

$_SESSION['nome_usuario'] = $nome;

if (empty($email) || empty($senha) || empty($nome) || empty($data_nascimento) || empty($cpf) || empty($username) || empty($municipio)) {
    header("Location: formCadastro.php?erro=vazio");
    exit();
}

$nome_arquivo = $_FILES['foto']['name'];
$id = $_GET['id'];

if ($id == 0) {
    // criar conta
    if (empty($nome_arquivo)) {
        $novo_nome = "../fotos/generico.png";
    } else {
        $caminho_temporario = $_FILES['foto']['tmp_name'];
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino = "../fotos/" . $novo_nome;
        move_uploaded_file($caminho_temporario, $caminho_destino);
    }

    $sql = "INSERT INTO usuarios (nomeUsuario, emailUsuario, dataNascimentoUsuario, cpfUsuario, usernameUsuario, senhaUsuario, municipio_codigo, fotoUsuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ssssssis', $nome, $email, $data_nascimento, $cpf, $username, $senha, $municipio, $novo_nome);
    mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);

    $_SESSION['foto_usuario'] = $novo_nome;

    $sql = "SELECT * FROM usuarios WHERE usernameUsuario = ? AND senhaUsuario = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'ss', $username, $senha);
    mysqli_stmt_execute($comando);
    $resultados = mysqli_stmt_get_result($comando);
    $usuario = mysqli_fetch_assoc($resultados);
    $id = $usuario['idUsuario'];

    $_SESSION['id_usuario'] = $id;
    header("Location: index.php?id=$id");

} else {
    // editar
    if (empty($nome_arquivo)) {
        // Sem nova foto — atualiza todos os campos incluindo municipio_codigo
        $sql = "UPDATE usuarios SET nomeUsuario = ?, emailUsuario = ?, dataNascimentoUsuario = ?, cpfUsuario = ?, usernameUsuario = ?, senhaUsuario = ?, municipio_codigo = ? WHERE idUsuario = ?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'sssssisi', $nome, $email, $data_nascimento, $cpf, $username, $senha, $municipio, $id);
    } else {
        // Com nova foto
        $caminho_temporario = $_FILES['foto']['tmp_name'];
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = uniqid() . "." . $extensao;
        $caminho_destino = "../fotos/usuario/" . $novo_nome;
        move_uploaded_file($caminho_temporario, $caminho_destino);

        $sql = "UPDATE usuarios SET nomeUsuario = ?, emailUsuario = ?, dataNascimentoUsuario = ?, cpfUsuario = ?, usernameUsuario = ?, senhaUsuario = ?, municipio_codigo = ?, fotoUsuario = ? WHERE idUsuario = ?";
        $comando = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($comando, 'ssssssisi', $nome, $email, $data_nascimento, $cpf, $username, $senha, $municipio, $novo_nome, $id);

        $_SESSION['foto_usuario'] = $novo_nome;
    }

    mysqli_stmt_execute($comando);
    mysqli_stmt_close($comando);
    header("Location: ./home.php?id=$id");
}