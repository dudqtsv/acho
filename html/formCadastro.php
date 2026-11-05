<?php
session_start();
require_once "./conexao.php";

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $sql = "SELECT * FROM usuarios WHERE idUsuario = ?";
    $comando = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($comando, 'i', $id);
    mysqli_stmt_execute($comando);

    $resultados = mysqli_stmt_get_result($comando);
    $usuario = mysqli_fetch_assoc($resultados);

    if (!$usuario) {
        die("Usuário não encontrado.");
    } $nome = $usuario['nomeUsuario'];$email = $usuario['emailUsuario'];$data_nascimento = $usuario['dataNascimentoUsuario'];$cpf = $usuario['cpfUsuario'];$username = $usuario['usernameUsuario'];$senha = "";$municipio = $usuario['municipio_codigo'];$foto = $usuario['fotoUsuario'];

} else {$id = 0;$nome = "";$email = "";$data_nascimento = "";$cpf = "";$username = "";$senha = "";$municipio = ""; $foto = "";
}

$erro = isset($_GET['erro']) ? (int) $_GET['erro'] : 0;

// Lista de estados para o select
$estados = $conexao->query("SELECT uf, nome FROM estados ORDER BY nome")->fetch_all(MYSQLI_ASSOC);

// Na edição, descobre o estado e o nome do município já salvos
$ufAtual = "";
$municipioNome = "";
if (!empty($municipio)) {
    $stmtMun = $conexao->prepare("SELECT nome, uf FROM municipios WHERE codigo = ?");
    $stmtMun->bind_param("i", $municipio);
    $stmtMun->execute();
    $munAtual = $stmtMun->get_result()->fetch_assoc();
    if ($munAtual) {
        $municipioNome = $munAtual['nome'];
        $ufAtual = $munAtual['uf'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/cadastro.css">

    <style>
        .regras-senha {
            list-style: none;
            padding-left: 0;
            margin: 8px 0 18px;
            font-size: 14px;
        }

        .regras-senha li {
            margin: 4px 0;
        }

        .regras-senha .invalido {
            color: #dc3545;
        }

        .regras-senha .valido {
            color: #198754;
        }

        .senha-wrapper {
            position: relative;
        }

        #toggleSenha {
            cursor: pointer;
        }

        .btn-cadastrar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .erro-validacao {
            color: #dc3545;
            font-size: 13px;
        }

        /* Select de estado e campo de município no mesmo visual dos outros campos */
        .cadastro-form select {
            width: 100%;
            border: none;
            outline: none;
            background: var(--cinza-input);
            border-radius: 14px;
            padding: 13px 18px;
            font-size: 0.92rem;
            color: var(--texto-label);
            font-family: inherit;
            cursor: pointer;
        }

        .cadastro-form select:focus {
            background: var(--cinza-input-hover);
            box-shadow: 0 0 0 3px rgba(26, 31, 107, 0.15);
        }

        .cadastro-form input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <div class="cadastro-page">
        <div class="cadastro-card">

            <h1>
                <?php
                echo isset($_GET['id'])
                    ? "Editar Perfil"
                    : "Criar Nova Conta";
                ?>
            </h1>

            <?php
            if ($erro != 0) {
                echo "<p class='erro-msg'>Não deixe nenhum campo vazio!</p>";
            }
            ?>

            <form
                action="salvarUsuario.php?id=<?php echo $id; ?>"
                method="POST"
                enctype="multipart/form-data"
                class="cadastro-form">

                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" placeholder="Nome completo"
                    value="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>"
                    required>

                <label for="data_nascimento">Data de nascimento</label>
                <input
                    type="date" id="data_nascimento" name="data_nascimento"
                    value="<?php echo htmlspecialchars($data_nascimento, ENT_QUOTES, 'UTF-8'); ?>"
                    required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email"
                    value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                    required>
                <p id="erro-email" class="erro-validacao" hidden>
                    Digite um e-mail válido.
                </p>

                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf"
                    placeholder="000.000.000-00"
                    maxlength="14"
                    value="<?php echo htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'); ?>"
                    required>
                <p id="erro-cpf" class="erro-validacao" hidden>
                    Digite um CPF válido.
                </p>

                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Username"
                    value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
                    required>

                <label for="senha">Senha</label>
                <div class="senha-wrapper">
                    <input
                        type="password"
                        name="senha"
                        id="senha"
                        placeholder="Senha"
                        autocomplete="new-password"
                        required>
                    <i class="bi bi-eye-slash" id="toggleSenha"></i>
                </div>

                <ul class="regras-senha" aria-live="polite">
                    <li id="regra-quantidade" class="invalido">✗ No mínimo 8 caracteres</li>
                    <li id="regra-numero" class="invalido">✗ Pelo menos um número</li>
                    <li id="regra-maiuscula" class="invalido">✗ Pelo menos uma letra maiúscula</li>
                    <li id="regra-minuscula" class="invalido">✗ Pelo menos uma letra minúscula</li>
                </ul>

                <label for="estado">Estado</label>
                <select id="estado" required>
                    <option value="">Selecione o estado</option>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?php echo htmlspecialchars($e['uf']); ?>"
                            <?php echo $e['uf'] === $ufAtual ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($e['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="municipio_busca">Município</label>
                <input
                    type="text"
                    id="municipio_busca"
                    list="lista-municipios"
                    placeholder="Selecione o estado primeiro"
                    autocomplete="off"
                    value="<?php echo htmlspecialchars($municipioNome, ENT_QUOTES, 'UTF-8'); ?>"
                    <?php echo $ufAtual === '' ? 'disabled' : ''; ?>
                    required>
                <datalist id="lista-municipios"></datalist>
                <input type="hidden" id="municipio" name="municipio"
                    value="<?php echo htmlspecialchars($municipio, ENT_QUOTES, 'UTF-8'); ?>">

                <label for="foto">Foto de perfil</label>

                <?php if (isset($_GET['id']) && !empty($foto)): ?>
                    <img
                        src="../fotos/<?php echo rawurlencode(basename($foto)); ?>"
                        alt="Foto atual"
                        class="foto-atual"
                        width="120">
                <?php endif; ?>

                <input type="file" id="foto" name="foto" accept="image/*">

                <button
                    type="submit"
                    id="submit"
                    class="btn-cadastrar"
                    disabled>
                    Salvar alterações
                </button>

                <?php if (isset($_GET['id'])): ?>
                    <a href="./usuarioConta.php?id=<?php echo $id; ?>" class="cancelar-link">
                        Cancelar
                    </a>
                <?php else: ?>
                    <a href="./index.php" class="cancelar-link">
                        Cancelar
                    </a>
                <?php endif; ?>

            </form>
        </div>
    </div>

    <script>
        const senhaInput = document.getElementById("senha");
        const emailInput = document.getElementById("email");
        const cpfInput = document.getElementById("cpf");
        const toggleSenha = document.getElementById("toggleSenha");
        const formulario = document.querySelector(".cadastro-form");
        const botao = document.getElementById("submit");

        const erroCPF = document.getElementById("erro-cpf");
        const erroEmail = document.getElementById("erro-email");

        const regras = {
            quantidade: document.getElementById("regra-quantidade"),
            numero: document.getElementById("regra-numero"),
            maiuscula: document.getElementById("regra-maiuscula"),
            minuscula: document.getElementById("regra-minuscula")
        };

        // Valida as regras da senha
        function validar(regra, atende) {
            regra.classList.toggle("valido", atende);
            regra.classList.toggle("invalido", !atende);

            const texto = regra.textContent.replace(/^[✓✗]\s*/, "");
            regra.textContent = (atende ? "✓ " : "✗ ") + texto;
        }

        function validarSenha() {
            const valor = senhaInput.value;

            const resultados = {
                quantidade: valor.length >= 8,
                numero: /[0-9]/.test(valor),
                maiuscula: /[A-Z]/.test(valor),
                minuscula: /[a-z]/.test(valor)
            };

            Object.keys(resultados).forEach(chave => {
                validar(regras[chave], resultados[chave]);
            });

            return Object.values(resultados).every(Boolean);
        }

        // Valida o CPF
        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, "");

            if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
                return false;
            }

            let soma = 0;

            for (let i = 0; i < 9; i++) {
                soma += Number(cpf[i]) * (10 - i);
            }

            let resto = (soma * 10) % 11;
            let digito1 = resto === 10 ? 0 : resto;

            if (digito1 !== Number(cpf[9])) {
                return false;
            }

            soma = 0;

            for (let i = 0; i < 10; i++) {
                soma += Number(cpf[i]) * (11 - i);
            }

            resto = (soma * 10) % 11;
            let digito2 = resto === 10 ? 0 : resto;

            return digito2 === Number(cpf[10]);
        }

        // Formata o CPF enquanto digita
        cpfInput.addEventListener("input", () => {
            let valor = cpfInput.value.replace(/\D/g, "").slice(0, 11);

            if (valor.length > 9) {
                valor = valor.replace(
                    /^(\d{3})(\d{3})(\d{3})(\d{1,2})$/,
                    "$1.$2.$3-$4"
                );
            } else if (valor.length > 6) {
                valor = valor.replace(
                    /^(\d{3})(\d{3})(\d{1,3})$/,
                    "$1.$2.$3"
                );
            } else if (valor.length > 3) {
                valor = valor.replace(
                    /^(\d{3})(\d{1,3})$/,
                    "$1.$2"
                );
            }

            cpfInput.value = valor;
            atualizarBotao();
        });

        // Verifica o formato do e-mail
        function validarEmail() {
            const valido =
                emailInput.value.trim() !== "" &&
                emailInput.validity.valid;

            erroEmail.hidden =
                valido || emailInput.value.trim() === "";

            return valido;
        }

        // Verifica os campos e libera ou bloqueia o botão
        function atualizarBotao() {
            const campos = formulario.querySelectorAll(
                "input[required], select[required], textarea[required]"
            );

            let preenchidos = true;

            campos.forEach(campo => {
                if (
                    campo.value.trim() === "" ||
                    !campo.validity.valid
                ) {
                    preenchidos = false;
                }
            });

            const cpfValido = validarCPF(cpfInput.value);
            const emailValido = validarEmail();
            const senhaValida = validarSenha();

            erroCPF.hidden = cpfInput.value.trim() === "" || cpfValido;

            botao.disabled = !(
                preenchidos &&
                cpfValido &&
                emailValido &&
                senhaValida
            );
        }

        // Mostra ou esconde a senha
        toggleSenha.addEventListener("click", () => {
            const mostrar = senhaInput.type === "password";

            senhaInput.type = mostrar ? "text" : "password";

            toggleSenha.classList.toggle("bi-eye", mostrar);
            toggleSenha.classList.toggle("bi-eye-slash", !mostrar);
        });

        // Atualiza as validações quando os campos mudam
        formulario.querySelectorAll("input, select, textarea").forEach(campo => {
            campo.addEventListener("input", atualizarBotao);
            campo.addEventListener("change", atualizarBotao);
        });

        // Confere novamente antes do envio
        formulario.addEventListener("submit", evento => {
            atualizarBotao();

            if (botao.disabled) {
                evento.preventDefault();
                alert("Preencha todos os campos corretamente!");
            }
        });

        // ---------- Estado / Município ----------
        const estadoSelect = document.getElementById("estado");
        const municipioBusca = document.getElementById("municipio_busca");
        const municipioCodigo = document.getElementById("municipio");
        const listaMunicipios = document.getElementById("lista-municipios");

        let municipiosDoEstado = [];

        // Ignora acentos e maiúsculas ao comparar nomes
        function normalizar(texto) {
            return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        }

        // Confere se o texto digitado é um município da lista do estado
        function validarMunicipio() {
            const digitado = normalizar(municipioBusca.value);
            const achado = municipiosDoEstado.find(m => normalizar(m.nome) === digitado);

            if (achado) {
                municipioCodigo.value = achado.codigo;
                municipioBusca.value = achado.nome; // acerta acentos e maiúsculas
                municipioBusca.setCustomValidity("");
            } else {
                municipioCodigo.value = "";
                municipioBusca.setCustomValidity(
                    municipioBusca.disabled ? "" : "Escolha um município da lista."
                );
            }
        }

        // Busca os municípios do estado escolhido
        async function carregarMunicipios(uf, manterTexto) {
            municipiosDoEstado = [];
            listaMunicipios.innerHTML = "";

            if (!manterTexto) {
                municipioBusca.value = "";
                municipioCodigo.value = "";
            }

            if (!uf) {
                municipioBusca.disabled = true;
                municipioBusca.placeholder = "Selecione o estado primeiro";
                municipioBusca.setCustomValidity("");
                atualizarBotao();
                return;
            }

            municipioBusca.disabled = false;
            municipioBusca.placeholder = "Digite para pesquisar o município";

            try {
                const resposta = await fetch("municipios.php?uf=" + encodeURIComponent(uf));
                municipiosDoEstado = await resposta.json();
            } catch (erro) {
                municipiosDoEstado = [];
            }

            municipiosDoEstado.forEach(m => {
                const opcao = document.createElement("option");
                opcao.value = m.nome;
                listaMunicipios.appendChild(opcao);
            });

            if (municipiosDoEstado.length === 0) {
                municipioBusca.placeholder = "Nenhum município cadastrado para este estado";
            }

            validarMunicipio();
            atualizarBotao();
        }

        estadoSelect.addEventListener("change", () => carregarMunicipios(estadoSelect.value, false));
        municipioBusca.addEventListener("input", () => {
            validarMunicipio();
            atualizarBotao();
        });

        // Na edição, já carrega a lista do estado salvo mantendo o município
        if (estadoSelect.value) {
            carregarMunicipios(estadoSelect.value, true);
        }

        // Verificação inicial
        atualizarBotao();
    </script>

</body>

</html>