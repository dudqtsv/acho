```php
<?php

session_start();

require_once "./conexao.php";

$id = $_GET['id'] ?? 0;

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$nascimento = trim($_POST['nascimento'] ?? '');
//*sso garante que o CPF fique apenas com números, 
// sem pontos ou traços, o que facilita validação e inserção no banco
$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
$username = trim($_POST['username'] ?? '');
$senha = trim($_POST['senha'] ?? '');

$estado = $_POST['estado_codigoUf'] ?? null;
$municipio = $_POST['municipio_codigo'] ?? null;

$tempo = strtotime($nascimento);
$hoje = time();

/*VALIDAÇÕES*/

if (
    $nome == "" ||
    $email == "" ||
    $nascimento == "" ||
    $cpf == "" ||
    $username == "" ||
    ($id == 0 && $senha == "")
) {

    header("Location: index.php?e=1");
    exit();
}

if (strlen($nome) > 255) {

    header("Location: index.php?e=2");
    exit();
}

if ($senha != "" && strlen($senha) > 30) {

    header("Location: index.php?e=3");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: index.php?e=5");
    exit();
}

if (
    $tempo === false ||
    $tempo > $hoje ||
    $tempo < strtotime("1901-01-01")
) {

    header("Location: index.php?e=6");
    exit();
}

if (strlen($cpf) != 11) {

    header("Location: index.php?e=7");
    exit();
}

/*EDITAR USUÁRIO */

if ($id != 0) {

    // verifica email duplicado
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE emailUsuario = ?
            AND idUsuario != ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "si",
        $email,
        $id
    );

    mysqli_stmt_execute($comando);

    $resultado = mysqli_stmt_get_result($comando);

    if (mysqli_fetch_assoc($resultado)) {

        header("Location: index.php?e=13");
        exit();
    }

    // verifica username duplicado
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE usernameUsuario = ?
            AND idUsuario != ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "si",
        $username,
        $id
    );

    mysqli_stmt_execute($comando);

    $resultado = mysqli_stmt_get_result($comando);

    if (mysqli_fetch_assoc($resultado)) {

        header("Location: index.php?e=12");
        exit();
    }

    // verifica CPF duplicado
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE cpfUsuario = ?
            AND idUsuario != ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "si",
        $cpf,
        $id
    );

    mysqli_stmt_execute($comando);

    $resultado = mysqli_stmt_get_result($comando);

    if (mysqli_fetch_assoc($resultado)) {

        header("Location: index.php?e=14");
        exit();
    }

    /*UPDATE COM SENHA */

    if ($senha != "") {

        $senha_hash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE usuarios SET

                    nomeUsuario = ?,
                    emailUsuario = ?,
                    dataNascimentoUsuario = ?,
                    cpfUsuario = ?,
                    usernameUsuario = ?,
                    senhaUsuario = ?,
                    estado_codigoUf = ?,
                    municipio_codigo = ?

                WHERE idUsuario = ?";

        $comando = mysqli_prepare(
            $conexao,
            $sql
        );

        mysqli_stmt_bind_param(
            $comando,
            "ssssssiii",
            $nome,
            $email,
            $nascimento,
            $cpf,
            $username,
            $senha,
            $estado,
            $municipio,
            $id
        );

    } else {

        /*UPDATE SEM SENHA*/

        $sql = "UPDATE usuarios SET

                    nomeUsuario = ?,
                    emailUsuario = ?,
                    dataNascimentoUsuario = ?,
                    cpfUsuario = ?,
                    usernameUsuario = ?,
                    estado_codigoUf = ?,
                    municipio_codigo = ?

                WHERE idUsuario = ?";

        $comando = mysqli_prepare(
            $conexao,
            $sql
        );

        mysqli_stmt_bind_param(
            $comando,
            "sssssiii",
            $nome,
            $email,
            $nascimento,
            $cpf,
            $username,
            $estado,
            $municipio,
            $id
        );
    }

    mysqli_stmt_execute($comando);
}

/*CADASTRAR USUÁRIO*/

else {

    // verifica username
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE usernameUsuario = ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "s",
        $username
    );

    mysqli_stmt_execute($comando);

    mysqli_stmt_store_result($comando);

    if (mysqli_stmt_num_rows($comando) > 0) {

        header("Location: index.php?e=12");
        exit();
    }

    // verifica email
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE emailUsuario = ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "s",
        $email
    );

    mysqli_stmt_execute($comando);

    mysqli_stmt_store_result($comando);

    if (mysqli_stmt_num_rows($comando) > 0) {

        header("Location: index.php?e=13");
        exit();
    }

    // verifica cpf
    $sql = "SELECT idUsuario
            FROM usuarios
            WHERE cpfUsuario = ?";

    $comando = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param(
        $comando,
        "s",
        $cpf
    );

    mysqli_stmt_execute($comando);

    mysqli_stmt_store_result($comando);

    if (mysqli_stmt_num_rows($comando) > 0) {

        header("Location: index.php?e=14");
        exit();
    }

    // criptografa senha
    $senha_hash = password_hash(
        $senha,
        PASSWORD_DEFAULT
    );

    /*INSERT*/

    $sql = "INSERT INTO usuarios (

                nomeUsuario,
                emailUsuario,
                dataNascimentoUsuario,
                cpfUsuario,
                usernameUsuario,
                senhaUsuario,
                estado_codigoUf,
                municipio_codigo

            )

            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $comando = mysqli_prepare(
        $conexao,
        $sql
    );

    mysqli_stmt_bind_param(
        $comando,
        "ssssssii",
        $nome,
        $email,
        $nascimento,
        $cpf,
        $username,
        $senha_hash,
        $estado,
        $municipio
    );

    mysqli_stmt_execute($comando);

    $id = mysqli_insert_id($conexao);

    $_SESSION['logado'] = true;
    $_SESSION['id'] = $id;
    $_SESSION['nome'] = $nome;
}

/*FOTO DE PERFIL */

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] == 0
) {

    $nome_arquivo = $_FILES['foto']['name'];

    $tmp = $_FILES['foto']['tmp_name'];

    $extensao = strtolower(
        pathinfo(
            $nome_arquivo,
            PATHINFO_EXTENSION
        )
    );

    $permitidas = ['png'];

    if (in_array($extensao, $permitidas)) {

        $novo_nome = uniqid() . "." . $extensao;

        move_uploaded_file(
            $tmp,
            "./fotos/" . $novo_nome
        );

        $sql = "UPDATE usuarios
                SET fotoUsuario = ?
                WHERE idUsuario = ?";

        $comando = mysqli_prepare(
            $conexao,
            $sql
        );

        mysqli_stmt_bind_param(
            $comando,
            "si",
            $novo_nome,
            $id
        );

        mysqli_stmt_execute($comando);
    }
}

header("Location: ./index.php");
exit();

?>
