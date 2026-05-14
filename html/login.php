<?php

$id = 0;
$nomeusuario = "";
$emailUsuario = "";
$dataNascimentoUsuario = "";
$cpfUsuario = "";
$usernameUsuario = "";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>

<body id="cadastro">

<div class="container_cadastro">
    <div class="form-section">

        <form action="saida.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">

            <h2>Área de Cadastro</h2>

            Nome de usuário:
            <input 
                type="text" 
                name="nick" 
                id="nick" 
                placeholder="Nome único"
                value="<?php echo $nomeusuario; ?>"
            >

            <br><br>

            Email:
            <input 
                type="email" 
                name="email" 
                id="email" 
                required 
                placeholder="Seu email"
                value="<?php echo $emailUsuario; ?>"
            >

            <br><br>

            Data de nascimento:
            <input 
                type="date" 
                name="nascimento" 
                id="nascimento"
                value="<?php echo $dataNascimentoUsuario; ?>"
            >

            <br><br>

            CPF:
            <input 
                type="text" 
                name="CPF" 
                id="CPF" 
                placeholder="Informe o seu CPF" 
                required
                value="<?php echo $cpfUsuario; ?>"
            >

            <br><br>

            Username Usuário:
            <input 
                type="text" 
                name="username" 
                id="username"
                placeholder="Informe o seu username" 
                required
                value="<?php echo $usernameUsuario; ?>" 
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