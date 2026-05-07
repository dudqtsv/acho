<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>

    </style>
</head>
<body id="login">

    <div class="container_login">
        <div class="form-section">
            <h2>Log in</h2>

            <form action="saida.php" method="POST">
                Nome de usuário:
                <input type="text" name="nick" id="nick" required>

                Senha:
                <input type="password" name="senha" id="senha" required>

                <div class="show-password">
                    <input type="checkbox" id="mostrar_senha">
                    <span>mostrar senha</span>
                </div>

                <?php require_once "../erro_login.php"; ?>

                <input type="submit" value="Logar" id="submeter">

                <div class="register">
                    Não possui uma conta? <a href="../cadastro/index.php">Cadastre-se</a>
                </div>
            </form>
        
    </script>
