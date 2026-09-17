<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../login.css">
</head>

<body id="login">
    <div class="login-page">
        <div class="login-card">
            <h1>Login</h1>

            <?php
            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
            } else {
                $msg = 0;
            }
            if ($msg != 0) {
                echo "<p class='login-msg'>Usuário inexistente</p>";
            }
            if (isset($_GET['erro'])) {
                $erro1 = $_GET['erro'];
            } else {
                $erro1 = 0;
            }
            if ($erro1 != 0) {
                echo "<p class='login-msg'>Não é possível acessar essa página sem uma conta logada.</p>";
            }
            ?>

            <form action="./login.php" method="post" class="login-form">
                <label for="username">Nome de usuário</label>
                <input type="text" id="username" name="username" placeholder="Nome do Usuário" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha" required>

                <button type="submit" id="submit" class="btn-entrar">Entrar</button>
            </form>

            <p class="cadastro-link">Não tem conta? <a href="./formCadastro.php">Cadastre-se</a></p>
        </div>
    </div>
</body>

</html>