<?php
session_start();
require_once "conexao.php";

if (!isset($_SESSION['idUsuario'])) {
    $_SESSION['idUsuario'] = 1; // trocar depois pelo login de verdade
}

$idUsuario = $_SESSION['idUsuario'];

// dados do usuário
$sql = "SELECT nomeUsuario, emailUsuario, dataCriacao, fotoUsuario FROM usuarios WHERE idUsuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

$nome = $usuario['nomeUsuario'];
$email = $usuario['emailUsuario'];
$anoCadastro = date("Y", strtotime($usuario['dataCriacao']));
$foto = $usuario['fotoUsuario'] ? $usuario['fotoUsuario'] : "../fotos/generico.png";

// total de produtos do usuário
$sql = "SELECT COUNT(*) as total FROM produtos WHERE usuario_idUsuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$totalProdutos = $stmt->get_result()->fetch_assoc()['total'];

// total de anúncios já publicados (aqui é o mesmo total de produtos cadastrados)
$totalAnuncios = $totalProdutos;

// média das avaliações recebidas nos produtos do usuário
$sql = "SELECT AVG(a.nota) as media FROM avaliacoes a
        INNER JOIN produtos p ON a.idProduto = p.idProduto
        WHERE p.usuario_idUsuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$mediaAvaliacao = $stmt->get_result()->fetch_assoc()['media'];
$mediaAvaliacao = $mediaAvaliacao ? number_format($mediaAvaliacao, 1) : "0.0";

// % de avaliações boas (nota >= 4)
$sql = "SELECT
            (SELECT COUNT(*) FROM avaliacoes a INNER JOIN produtos p ON a.idProduto = p.idProduto WHERE p.usuario_idUsuario = ? AND a.nota >= 4) as boas,
            (SELECT COUNT(*) FROM avaliacoes a INNER JOIN produtos p ON a.idProduto = p.idProduto WHERE p.usuario_idUsuario = ?) as total";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $idUsuario, $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();
$satisfacao = $resultado['total'] > 0 ? round(($resultado['boas'] / $resultado['total']) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Minha Conta</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    :root {
        --azul-escuro: #1B2A6B;
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
    }

    .card-perfil {
        background: var(--cinza-fundo);
        border-radius: 12px;
        padding: 30px;
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .card-perfil img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
    }

    .card-perfil .info {
        flex: 1;
    }

    .card-perfil h2 {
        margin: 0 0 4px 0;
        font-style: italic;
    }

    .card-perfil p {
        margin: 2px 0;
        color: var(--cinza-texto);
        font-size: 14px;
    }

    .card-perfil .botoes button {
        padding: 12px 20px;
        border-radius: 8px;
        border: 1px solid var(--cinza-borda);
        background: #fff;
        font-weight: 600;
        cursor: pointer;
        margin-left: 10px;
    }

    .conteudo {
        display: flex;
        gap: 30px;
    }

    .menu-lateral {
        width: 280px;
        background: var(--cinza-fundo);
        border-radius: 12px;
        padding: 20px;
    }

    .menu-lateral a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 10px;
        text-decoration: none;
        color: #1f2430;
        font-weight: 600;
        margin-bottom: 10px;
        background: #fff;
    }

    .menu-lateral a.ativo {
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .menu-lateral svg {
        width: 20px;
        height: 20px;
    }

    .estatisticas {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .estatisticas .box {
        background: var(--cinza-fundo);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
    }

    .estatisticas .box svg {
        width: 36px;
        height: 36px;
        margin-bottom: 10px;
    }

    .estatisticas .box .numero {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .estrela {
        color: #f5a623;
        font-size: 30px;
    }
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">

    <div class="card-perfil">
        <img src="<?= $foto ?>" alt="foto de perfil">
        <div class="info">
            <h2><?= $nome ?></h2>
            <p><?= $email ?></p>
            <p>Membro desde <?= $anoCadastro ?></p>
        </div>
        <div class="botoes">
            <button>Editar Perfil</button>
            <button>Alterar Senha</button>
        </div>
    </div>

    <div class="conteudo">
        <div class="menu-lateral">
            <a href="usuarioConta.php" class="ativo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>
                </svg>
                Dados pessoais
            </a>
            <a href="produtos.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 8h12l-1 12H7L6 8z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>
                Produtos
            </a>
            <a href="favoritos.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 3h12v18l-6-4-6 4V3z"/>
                </svg>
                Favoritos
            </a>
            <a href="#">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="6" width="18" height="13" rx="2"/>
                    <path d="M8 6V4h8v2"/>
                </svg>
                Meus Anúncios
            </a>
        </div>

        <div class="estatisticas">
            <div class="box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 8h12l-1 12H7L6 8z"/>
                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                </svg>
                <div class="numero"><?= $totalProdutos ?></div>
                <div>Produtos</div>
            </div>

            <div class="box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                    <path d="M8 2v4M16 2v4M4 10h16"/>
                </svg>
                <div class="numero"><?= $totalAnuncios ?></div>
                <div>Anúncios</div>
            </div>

            <div class="box">
                <div class="estrela">★</div>
                <div class="numero"><?= $mediaAvaliacao ?></div>
                <div>Avaliação média</div>
            </div>

            <div class="box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="8" r="3"/>
                    <path d="M2 20c0-3 3-5 7-5s7 2 7 5"/>
                    <circle cx="17" cy="9" r="2.5"/>
                    <path d="M16 15c2.5 0 5 1.5 5 4"/>
                </svg>
                <div class="numero"><?= $satisfacao ?>%</div>
                <div>clientes satisfeitos</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
