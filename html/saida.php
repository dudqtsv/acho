<?php

session_start();

require_once "index.php";

$id = $_GET['id'] ?? 0;

$nick = trim($_POST['nick'] ?? '');
$email = trim($_POST['email'] ?? '');
$nascimento = trim($_POST['nascimento'] ?? '');
$cpf = trim($_POST['CPF'] ?? '');
$username = trim($_POST['username'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$tempo = strtotime($nascimento);
$hoje = time();


if (
    $nick == "" ||
    $email == "" ||
    $nascimento == "" ||
    $cpf == "" ||
    $username == "" ||
    $senha == ""
) {
    header("Location: index.php?e=1");
    exit();
}

if (strlen($nick) > 22) {
    header("Location: index.php?e=2");
    exit();
}

if (strlen($senha) > 30) {
    header("Location: index.php?e=3");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?e=5");
    exit();
}

if ($tempo === false || $tempo > $hoje || $tempo < strtotime("1901-01-01")) {
    header("Location: index.php?e=6");
    exit();
}


if ($id != 0) {

    $sql = "UPDATE usuario 
            SET usuario_nick = ?, 
                usuario_email = ?, 
                usuario_data_nasc = ?, 
                usuario_cpf = ?, 
                usuario_username = ?
            WHERE usuario_id = ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "sssssi",
        $nick,
        $email,
        $nascimento,
        $cpf,
        $username,
        $id
    );

    mysqli_stmt_execute($comando);

} else {

$sql = "SELECT usuario_id FROM usuario WHERE usuario_nick = ?";

$comando = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param($comando, "s", $nick);

mysqli_stmt_execute($comando);

mysqli_stmt_store_result($comando);

if (mysqli_stmt_num_rows($comando) > 0) {

    header("Location: index.php?e=12");

    exit();

}

}

    $sql = "SELECT usuario_id FROM usuario WHERE usuario_email = ?";
    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($comando, "s", $email);

    mysqli_stmt_execute($comando);

    $resultado = mysqli_stmt_get_result($comando);

    if (mysqli_fetch_assoc($resultado)) {
        header("Location: index.php?e=13");
        exit();
    }


    $senha = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario 
    (
        usuario_nick,
        usuario_email,
        usuario_data_nasc,
        usuario_cpf,
        usuario_username,
        usuario_senha
    )
    VALUES (?, ?, ?, ?, ?, ?)";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "ssssss",
        $nick,
        $email,
        $nascimento,
        $cpf,
        $username,
        $senha
    );

    mysqli_stmt_execute($comando);

    $id = mysqli_insert_id($conexao);

    $_SESSION['logado'] = true;
    $_SESSION['id'] = $id;
    $_SESSION['nick'] = $nick;


if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

    $nome_arquivo = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];

    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));

    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($extensao, $permitidas)) {

        $novo_nome = uniqid() . "." . $extensao;

        move_uploaded_file($tmp, "../fotos/" . $novo_nome);

        $sql = "UPDATE usuario SET usuario_foto = ? WHERE usuario_id = ?";

        $comando = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param($comando, "si", $novo_nome, $id);

        mysqli_stmt_execute($comando);
    }
}

header("Location: ../index.php");

?>