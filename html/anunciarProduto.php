<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idUsuario'])) {
    $_SESSION['idUsuario'] = 1; // trocar depois pelo login de verdade
}

$idUsuario = $_SESSION['idUsuario'];
$erro = "";
$sucesso = "";

if (isset($_POST['publicar'])) {

    $categoria = $_POST['categoria'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $condicao = $_POST['condicao'];
    $preco = str_replace(",", ".", $_POST['preco']);

    $foto = null;

    if (isset($_FILES['fotos']) && $_FILES['fotos']['name'][0] != "") {

        $pasta = "../fotos/produto/";

        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $nomeArquivo = time() . "_" . $_FILES['fotos']['name'][0];
        $destino = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['fotos']['tmp_name'][0], $destino)) {
            $foto = $destino;
        }
    }

    $sql = "INSERT INTO produtos (nomeProduto, categoriaProduto, descricaoProduto, precoProduto, statusProduto, usuario_idUsuario, fotoProduto)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssdsis", $titulo, $categoria, $descricao, $preco, $condicao, $idUsuario, $foto);

    if ($stmt->execute()) {
        $sucesso = "Anúncio publicado com sucesso!";
    } else {
        $erro = "Erro ao publicar: " . $conexao->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Postar anúncio</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    :root {
        --azul-escuro: #1B2A6B;
        --azul-hover: #14205a;
        --cinza-borda: #d9dcE3;
        --cinza-fundo: #f4f5f8;
        --cinza-texto: #6b7280;
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        background: #ececec;
        font-family: -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        color: #1f2430;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 18px rgba(0,0,0,0.08);
        padding: 40px 48px;
    }

    h1 {
        color: var(--azul-escuro);
        font-size: 28px;
        margin: 0 0 6px 0;
    }

    .subtitulo {
        color: var(--cinza-texto);
        margin: 0 0 18px 0;
        font-size: 14px;
    }

    hr {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 0 0 30px 0;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1px 380px;
        gap: 40px;
    }

    .divider {
        background: #e5e7eb;
    }

    label {
        display: block;
        font-weight: 600;
        margin: 22px 0 8px 0;
        font-size: 15px;
    }

    label.primeiro { margin-top: 0; }

    select, input[type=text], input[type=number], textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--cinza-borda);
        border-radius: 8px;
        background: var(--cinza-fundo);
        font-size: 14px;
        color: #1f2430;
    }

    textarea {
        resize: vertical;
        min-height: 90px;
        font-family: inherit;
    }

    .dica {
        display: inline-block;
        margin-top: 10px;
        background: #dceefc;
        color: #1d6fa5;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
    }

    .preco-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid var(--cinza-borda);
        border-radius: 8px;
        background: var(--cinza-fundo);
        overflow: hidden;
    }

    .preco-wrapper span {
        padding: 12px 14px;
        color: var(--cinza-texto);
        border-right: 1px solid var(--cinza-borda);
    }

    .preco-wrapper input {
        border: none;
        background: transparent;
    }

    .fotos-box {
        border: 2px dashed var(--cinza-borda);
        border-radius: 12px;
        background: var(--cinza-fundo);
        height: 380px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-align: center;
        color: var(--cinza-texto);
        position: relative;
        overflow: hidden;
    }

    .fotos-box .mais {
        font-size: 42px;
        color: #7b86c9;
        margin-bottom: 12px;
        font-weight: 300;
    }

    .fotos-box input[type=file] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .rodape {
        display: flex;
        justify-content: flex-end;
        gap: 14px;
        margin-top: 34px;
    }

    button {
        padding: 13px 28px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .btn-cancelar {
        background: #e9e9ee;
        color: #333;
    }

    .btn-publicar {
        background: var(--azul-escuro);
        color: #fff;
    }

    .btn-publicar:hover { background: var(--azul-hover); }

    .msg {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .msg.erro { background: #fdecec; color: #b3261e; }
    .msg.sucesso { background: #e6f6ec; color: #1b7a3d; }

    @media (max-width: 860px) {
        .grid { grid-template-columns: 1fr; }
        .divider { display: none; }
    }
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h1>Postar anúncio</h1>
    <p class="subtitulo">Preencha as informações abaixo para anunciar seu produto</p>
    <hr>

    <?php if ($sucesso) echo "<div class='msg sucesso'>$sucesso</div>"; ?>
    <?php if ($erro) echo "<div class='msg erro'>$erro</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="grid">
            <div>
                <label class="primeiro">Categoria</label>
                <select name="categoria" required>
                    <option value="">Selecionar categoria</option>
                    <option value="Eletrônico">Eletrônico</option>
                    <option value="Esporte">Esporte</option>
                    <option value="Móvel">Móvel</option>
                    <option value="Beleza">Beleza</option>
                    <option value="Roupas">Roupas</option>
                    <option value="Outros">Outros</option>
                </select>
                <span class="dica">Ex: Eletrônico, Esporte, Móvel, Beleza</span>

                <label>Título do Anúncio</label>
                <input type="text" name="titulo" placeholder="Ex: IPhone 12 256GB" required>

                <label>Descrição</label>
                <textarea name="descricao" placeholder="Descreva detalhes, características e condições do produto." required></textarea>

                <label>Condição do Produto</label>
                <select name="condicao" required>
                    <option value="">Ex: Usado, Novo, Seminovo</option>
                    <option value="Novo">Novo</option>
                    <option value="Seminovo">Seminovo</option>
                    <option value="Usado">Usado</option>
                </select>

                <label>Preço</label>
                <div class="preco-wrapper">
                    <span>R$</span>
                    <input type="text" name="preco" placeholder="Defina o preço do produto" required>
                </div>
            </div>

            <div class="divider"></div>

            <div>
                <label class="primeiro">Fotos</label>
                <div class="fotos-box">
                    <span class="mais">+</span>
                    <span>Enviar fotos do produto</span>
                    <input type="file" name="fotos[]" accept="image/*">
                </div>
            </div>
        </div>

        <div class="rodape">
            <button type="button" class="btn-cancelar" onclick="window.history.back()">Cancelar</button>
            <button type="submit" name="publicar" class="btn-publicar">Publicar Anúncio</button>
        </div>
    </form>
</div>

</body>
</html>
