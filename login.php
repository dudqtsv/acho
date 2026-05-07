
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
            <form action="saida.php?id=<?php echo $id; ?>" method='POST' enctype="multipart/form-data">

            <h2>Área de Cadastro</h2>

            Nome de usuário:
            <input type="text" name="nick" id="nick" placeholder='Nome único' 
                   value="<?php echo $nomeusuario; ?>">

            Email:
            <input type="text" name="email" id="email" required placeholder='Seu email'
                   value="<?php echo $emailUsuario; ?>">

            Data de nascimento:

            <input type="date" name="nascimento" id="nascimento" 
                   value="<?php echo $dataNascimentoUsuario; ?>">

            CPF:

            <input type="text" name="CPF" id="CPF" placeholder="Informe o seu CPF" required 
                   value="<?php echo $cpfUsuario; ?>">
    
            Username Usuario:

            <input type="text" name="username" id="username"  placeholder="Informe o seu username" required 
                   value="<?php echo $usernameUsuario; ?>"> 

               Senha:
                <input type="password" name="senha" id="senha" required>

                <div class="show-password">
                    <input type="checkbox" id="mostrar_senha">
                    <span>mostrar senha</span>

            <?php 
    
            ?>
            <br>
            <br>
            Selecione sua foto de perfil:

            <input type="file" name="foto">
            <br>
            <br>

            <input type="submit" value="Salvar" id="submeter">



            <?php
            require_once "../erro_login.php";
            ?>
            </div>
        </form>
    </div>


    <script> 
         // puxa os elementos por id
         const senhaInput = document.getElementById('senha');
        const mostrarSenhaCheckbox = document.getElementById('mostrar_senha');

        // Adiciona um evento de clique na checkbox
        mostrarSenhaCheckbox.addEventListener('click', function() {
            if (mostrarSenhaCheckbox.checked) {
                // Se a checkbox estiver marcada, mostra a senha
                senhaInput.type = 'text';
            } else {
                // Se a checkbox estiver desmarcada, oculta a senha
                senhaInput.type = 'password';
            }
        });
    </script>
</body>
</html>