<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body id="login">

            <?php
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
            } else {
                $msg = 0;
            }
            if ($msg != 0) {
                echo "<p>Usuário inexistente</p>";
            }
            if (isset($_GET['erro'])) {
                $erro1 = $_GET['erro'];
            } else {
                $erro1 = 0;
            }
            if ($erro1 != 0) {
                echo "<p>Não é possível acessar essa página sem uma conta logada.</p>";
            }
            ?>


            <form action="./login.php" method="post">
                <h1>Área de Login</h1>
                <p>Username</p>
                <input type="text" name="username" required>
                <p>Senha</p>
                <input type="password" name="senha" required>
                <p><input type="submit" id="submit" value="Entrar"></p>
                <p>Ainda não possui acesso?
                    <a href="./formCadastro.php">Cadastre-se</a>!
                </p>
            </form>
</body>

</html>