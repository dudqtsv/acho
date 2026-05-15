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
    $data_nascimento = $usuario['dataNascimentoUsuario'];
    $email = $usuario['emailUsuario'];
    $cpf = $usuario['cpfUsuario'];
    $senha = $usuario['senhaUsuario'];
    $username = $usuario['usernameUsuario'];
    $foto = $usuario['fotoUsuario'];
    
} else {
    // echo "criar conta...";
    $id = 0;
    $nome = "";
    $data_nascimento = "";
    $email = "";
    $cpf = "";
    $senha = "";
    $username = "";
    $foto = "";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <a href="index.php"></a>

</head>

<body id="cadastro">

<div class="container_cadastro">
    <div class="form-section">

        <form action="saida.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

            <h2>Área de Cadastro</h2>

            Nome de usuário:
            <input 
                type="text" 
                name="nome" 
                id="nome" 
                placeholder="Nome Completo"
                value="<?php echo $nome; ?>"
            >

            <br><br>

            Email:
            <input 
                type="email" 
                name="email" 
                id="email" 
                required 
                placeholder="Seu email"
                value="<?php echo $email; ?>"
            >

            <br><br>

            Data de nascimento:
            <input 
                type="date" 
                name="nascimento" 
                id="nascimento"
                value="<?php echo $data_nascimento; ?>"
            >

            <br><br>

            CPF:
            <input 
                type="text" 
                name="cpf" 
                id="cpf" 
                placeholder="Informe o seu CPF" 
                required
                value="<?php echo $cpf; ?>"
            >

            <br><br>

            Username Usuário:
            <input 
                type="text" 
                name="username" 
                id="username"
                placeholder="Informe o seu username" 
                required
                value="<?php echo $username; ?>" 
            >

            <br><br>

            Senha:
            <input 
                type="password" 
                name="senha" 
                id="senha" 
                required
            >

            <div class="show-password">
                <input type="checkbox" id="mostrar_senha">
                <span>Mostrar senha</span>
            </div>

            <br><br>

            Selecione sua foto de perfil:
            <input type="file" name="foto">

            <br><br>

            <input type="submit" value="Salvar" id="submeter">

        </form>

    </div>
</div>

<script>

    const senhaInput = document.getElementById('senha');

    const mostrarSenhaCheckbox = document.getElementById('mostrar_senha');

    mostrarSenhaCheckbox.addEventListener('change', function () {

        senhaInput.type = this.checked ? 'text' : 'password';

    });

</script>

</body>
</html>