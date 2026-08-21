<?php
session_start();
require_once "./conexao.php";
//
if (isset($_GET['id'])) {

    // echo "editar...";
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE idUsuario = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    mysqli_stmt_execute($comando);
    $resultados = mysqli_stmt_get_result($comando);
    $usuario = mysqli_fetch_assoc($resultados);
    $nome = $usuario['nomeUsuario'];
    $email = $usuario['emailUsuario'];
    $data_nascimento = $usuario['dataNascimentoUsuario'];
    $cpf = $usuario['cpfUsuario'];
    $username = $usuario ['usernameUsuario'];
    $senha = $usuario['senhaUsuario'];
    $municipio = $usuario['municipio_codigo'];
    $foto = $usuario['fotoUsuario'];
} else {
    // echo "criar conta...";
    $id = 0;
    $nome = "";
    $email = "";
    $data_nascimento = "";
    $cpf = "";
    $username = "";
    $senha = "";
    $municipio = "";
    $foto = "";
}
?>
<?php
if (isset($_GET['erro'])) {
    $erro = $_GET['erro'];
} else {
    $erro = 0;
}
if ($erro != 0) {
    echo "<p class='erro-msg'>Não deixe nenhum campo vazio!</p>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

</head>

<body>
    <form action="salvarUsuario.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <h1>
            <?php
            if (isset($_GET['id'])) {
                echo "Editar Perfil";
            } else {
                echo "Criar Nova Conta";
            }
            ?>
        </h1>

        <p>Nome</p><input type="text" name="nome" value="<?php echo $nome; ?>">
        <p>Data de nascimento</p><input type="date" name="data_nascimento" value="<?php echo $data_nascimento; ?>">
        <p>E-mail</p><input type="email" name="email" value="<?php echo $email; ?>">
        <p>CPF</p><input type="text" name="cpf" value="<?php echo $cpf; ?>">
        <p>Username</p><input type="text" name="username" value="<?php echo $username; ?>">
        <p>Senha <i class="bi bi-eye-slash" id="toggleSenha"></i></p>
        <input type="password" name="senha" id="senha" value="<?= $senha ?>" required>
        <p>Município</p><input type="text" name="municipio" value="<?php echo $municipio; ?>">

        <?php
        if (isset($_GET['id'])) {
            echo "<p>Foto de perfil</p>
            <img src='../fotos/$foto'alt='Foto atual' class='img-thumbnail mb-2' width='120'>
            <input type='file' name='foto'>";
        } else {
            echo "<input type='file' name='foto'>";
        }
        ?>

        <button type="submit" id="submit">Salvar alterações</button>
        <?php if (isset($_GET['id'])) {
            echo "<a href='./usuarioConta.php?id=$id'>Cancelar</a>";
        } else {
            echo "<a href='./index.php?id=$id'>Cancelar</a>";
        }
        ?>
    </form>
    </div>


    <!-- olhinhoooooooooo da senha :D -->
    <script>
        const senhaInput = document.getElementById("senha");
        const toggleSenha = document.getElementById("toggleSenha");

        toggleSenha.addEventListener("click", () => {
            const isPassword = senhaInput.type === "password";
            senhaInput.type = isPassword ? "text" : "password";
            toggleSenha.classList.toggle("bi-eye");
            toggleSenha.classList.toggle("bi-eye-slash");
        });
    </script>

</body>

</html>