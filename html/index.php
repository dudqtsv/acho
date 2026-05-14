<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
 /* ===== Ícone do olho ===== */
        #toggleSenha {
            margin-left: 8px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s ease;
        }

        #toggleSenha:hover {
            color: #f1c40f;
        }
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



                <?php require_once "./erro_login.php"; ?>

                <input type="submit" value="Logar" id="submeter">

                <div class="register">
                    Não possui uma conta? <a href="./login.php">Cadastre-se</a>
                </div>
            </form>

            </script>
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