<?php
require_once "./conexao.php";

session_start();

function verificarLogin()
{
    if (!isset($_SESSION['id'])) {
        header('Location: index.php?erro=1');
        exit();
    }
}

function inserirUsuarios($conexao, $nome, $email, $nascimento, $cpf, $usernameUsuario, $senha, $datacriacao, $municipio, $fotousuario)
{
    $sql = "INSERT INTO usuarios (nome, email, nascimento, cpf, usernameUsuarios, senha, datacriacao, municipio, fotousuario)
            VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssssss", $nome, $email, $nascimento, $cpf, $usernameUsuario, $senha, $datacriacao, $municipio, $fotousuario);
    return $stmt->execute();
}

function listarUsuario($conexao)
{
    return $conexao->query("SELECT * FROM usuarios");
}

function buscarUsuario($conexao, $id)
{
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarUsuariosPorNome($conexao, $nome)
{
    $sql = "SELECT * FROM usuarios WHERE nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $nomeBusca = "%" . $nome . "%";
    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();
    return $stmt->get_result();
}

function atualizarUsuario($conexao, $id, $nome, $email, $nascimento, $cpf, $usernameUsuario, $senha, $datacriacao, $municipio, $fotousuario)
{
    $sql = "UPDATE usuarios SET nome = ?, email = ?, nascimento = ?, cpf = ?, usernameUsuarios = ?, senha = ?, datacriacao = ?, municipio = ?, fotousuario = ?, tipo = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssssi", $nome, $email, $nascimento, $cpf, $usernameUsuario, $senha, $datacriacao, $municipio, $fotousuario, $id);
    return $stmt->execute();
}

function deletarUsuario($conexao, $id)
{
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}





function inserirMunicipio($conexao, $codigo, $nome, $uf, $estado, $usuarios_idUsuarios, $usuarios_estado_codigoUf, $tipo)
{
    $sql = "INSERT INTO municipio (nome, uf, estado, usuarios_idusuarios, usuarios_estado_codigoUf, tipo)
            VALUES (?,?,?,?,?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssss", $codigo, $nome, $uf, $estado, $usuarios_idUsuarios, $usuarios_estado_codigoUf, $tipo);
    return $stmt->execute();
}

function listarProdutos($conexao)
{
    return $conexao->query("SELECT * FROM produtos");
}


function buscarLeitores($conexao, $id)
{
    $sql = "SELECT * FROM leitores WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function buscarLeitoresPorNome($conexao, $nome)
{
    $sql = "SELECT * FROM leitores WHERE nome LIKE ?";
    $stmt = $conexao->prepare($sql);
    $nomeBusca = "%" . $nome . "%";
    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();
    return $stmt->get_result();
}

function atualizarLeitor($conexao, $id, $nome, $senha, $cpf, $telefone, $nascimento, $tipo)
{
    $sql = "UPDATE leitores SET nome = ?, senha = ?, cpf = ?, telefone = ?, nascimento = ?, tipo = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssi", $nome, $senha, $cpf, $telefone, $nascimento, $tipo, $id);
    return $stmt->execute();
}

function deletarLeitor($conexao, $id)
{
    $sql = "DELETE FROM leitores WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
