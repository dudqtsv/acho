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
     $id = 0; $nome = ""; $email = ""; $data_nascimento = ""; $cpf = ""; $username = ""; $senha = ""; $municipio = ""; $foto = "";
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
    <link rel="stylesheet" href="../css/cadastro.css">
</head>

<body>
    <div class="cadastro-page">
        <div class="cadastro-card">
            <h1>
                <?php
                if (isset($_GET['id'])) {
                    echo "Editar Perfil";
                } else {
                    echo "Criar Nova Conta";
                }
                ?>
            </h1>

            <form action="salvarUsuario.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="cadastro-form">

                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" placeholder="Nome completo" value="<?php echo $nome; ?>">

                <label for="data_nascimento">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $data_nascimento; ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>">

                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="CPF" value="<?php echo $cpf; ?>">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>">

                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input type="password" name="senha" id="senha" placeholder="Senha" value="<?= $senha ?>" required>
                    <i class="bi bi-eye-slash" id="toggleSenha"></i>
                </div>

                <label for="municipio">Cidade / Estado</label>
                <input type="text" id="municipio" name="municipio" placeholder="Município" value="<?php echo $municipio; ?>">

                <?php
                if (isset($_GET['id'])) {
                    echo "<label>Foto de perfil</label>
                    <img src='../fotos/$foto' alt='Foto atual' class='foto-atual' width='120'>
                    <input type='file' name='foto'>";
                } else {
                    echo "<label>Foto de perfil</label>
                    <input type='file' name='foto'>";
                }
                ?>

                <button type="submit" id="submit" class="btn-cadastrar">Salvar alterações</button>
                <?php if (isset($_GET['id'])) {
                    echo "<a href='./usuarioConta.php?id=$id' class='cancelar-link'>Cancelar</a>";
                } else {
                    echo "<a href='./index.php?id=$id' class='cancelar-link'>Cancelar</a>";
                }
                ?>
            </form>
        </div>
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