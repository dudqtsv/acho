<?php
session_start();
require_once "./conexao.php";

if (isset($_GET['id'])) {

    // editar...
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
    $username = $usuario['usernameUsuario'];
    $senha = $usuario['senhaUsuario'];
    $municipio = $usuario['municipio_codigo'];
    $foto = $usuario['fotoUsuario'];
} else {
    // criar conta...
    $id = 0; $nome = ""; $email = ""; $data_nascimento = ""; $cpf = ""; $username = ""; $senha = ""; $municipio = ""; $foto = "";
}

// lista de estados para o select (GROUP BY evita estados repetidos na tabela)
$resultadoEstados = mysqli_query($conexao, "SELECT uf, MIN(nome) AS nome FROM estados GROUP BY uf ORDER BY nome");

// descobre a UF da cidade atual (para a edição de perfil)
$uf_atual = "";
if ($municipio != "") {
    $sqlEstado = "SELECT uf FROM municipios WHERE codigo = ?";
    $cmdEstado = mysqli_prepare($conexao, $sqlEstado);
    mysqli_stmt_bind_param($cmdEstado, 'i', $municipio);
    mysqli_stmt_execute($cmdEstado);
    $resEstado = mysqli_stmt_get_result($cmdEstado);
    if ($linhaEstado = mysqli_fetch_assoc($resEstado)) {
        $uf_atual = $linhaEstado['uf'];
    }
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
<html lang="pt-BR">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <style>
        /* select igual aos inputs do formulário */
        .cadastro-form select {
            width: 100%;
        }

        .estado-info {
            display: block;
            margin-top: 4px;
            color: #666;
        }
    </style>
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
                <input type="text" id="nome" name="nome" placeholder="Nome completo" value="<?= htmlspecialchars($nome) ?>">

                <label for="data_nascimento">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($data_nascimento) ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">

                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="CPF" value="<?= htmlspecialchars($cpf) ?>">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>">

                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input type="password" name="senha" id="senha" placeholder="Senha" value="<?= htmlspecialchars($senha) ?>" required>
                    <i class="bi bi-eye-slash" id="toggleSenha"></i>
                </div>

                <label for="estado">Estado</label>
                <select id="estado">
                    <option value="">Selecione o estado</option>
                    <?php while ($e = mysqli_fetch_assoc($resultadoEstados)) { ?>
                        <option value="<?= $e['uf'] ?>" <?= $e['uf'] == $uf_atual ? 'selected' : '' ?>>
                            <?= $e['nome'] ?> (<?= $e['uf'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <label for="municipio">Cidade</label>
                <select id="municipio" name="municipio" required>
                    <option value="">Selecione primeiro o estado</option>
                </select>
                <small id="estadoSelecionado" class="estado-info"></small>

                <?php
                if (isset($_GET['id'])) {
                    echo "<label>Foto de perfil</label>
                    <img src='fotos/$foto' alt='Foto atual' class='foto-atual' width='120'>
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

    <!-- estado e cidade -->
    <script>
        const estadoSelect = document.getElementById("estado");
        const cidadeSelect = document.getElementById("municipio");
        const estadoInfo = document.getElementById("estadoSelecionado");
        const cidadeAtual = "<?= $municipio ?>";

        function mostrarEstado() {
            const opcao = estadoSelect.options[estadoSelect.selectedIndex];
            estadoInfo.textContent = estadoSelect.value ? "Estado: " + opcao.text.trim() : "";
        }

        async function carregarCidades(estadoId, selecionada = "") {
            if (!estadoId) {
                cidadeSelect.innerHTML = '<option value="">Selecione primeiro o estado</option>';
                return;
            }

            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';

            try {
                const resposta = await fetch("buscarMunicipios.php?uf=" + encodeURIComponent(estadoId));
                const cidades = await resposta.json();

                if (cidades.length === 0) {
                    cidadeSelect.innerHTML = '<option value="">Nenhuma cidade cadastrada para este estado</option>';
                    return;
                }

                cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
                cidades.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.codigo;
                    opt.textContent = c.nome;
                    if (String(c.codigo) === String(selecionada)) opt.selected = true;
                    cidadeSelect.appendChild(opt);
                });
            } catch (erro) {
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            }
        }

        estadoSelect.addEventListener("change", () => {
            mostrarEstado();
            carregarCidades(estadoSelect.value);
        });

        // ao abrir a página (principalmente na edição de perfil)
        if (estadoSelect.value) {
            mostrarEstado();
            carregarCidades(estadoSelect.value, cidadeAtual);
        }
    </script>

</body>

</html>