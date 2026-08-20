<?php

require_once 'conexao.php';


// =====================================================
// LOGIN
// =====================================================

function verificarLogin(){
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }
}


function verificarAdmin(){
    return (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin');
}


function logout(){
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit;
}


function login($conexao, $cpf, $senha){

    $sql = "SELECT * FROM usuarios
            WHERE cpfUsuario = ?
            AND senhaUsuario = ?";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        return "erro";
    }

    $stmt->bind_param("ss", $cpf, $senha);

    if (!$stmt->execute()) {
        return "erro";
    }

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        $_SESSION['usuario'] = $usuario['nomeUsuario'];
        $_SESSION['id'] = $usuario['idUsuario'];

        // O banco atual não possui coluna tipo.
        $_SESSION['tipo'] = 'usuario';

        return true;
    }

    return false;
}


// =====================================================
// CRUD - USUÁRIOS
// =====================================================

function inserirUsuario(
    $conexao,
    $nome,
    $email,
    $nascimento,
    $cpf,
    $usernameUsuario,
    $senha,
    $dataCriacao,
    $municipio,
    $fotoUsuario
){

    $sql = "INSERT INTO usuarios
            (
                nomeUsuario,
                emailUsuario,
                dataNascimentoUsuario,
                cpfUsuario,
                usernameUsuario,
                senhaUsuario,
                dataCriacao,
                municipio_codigo,
                fotoUsuario
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssssssis",
        $nome,
        $email,
        $nascimento,
        $cpf,
        $usernameUsuario,
        $senha,
        $dataCriacao,
        $municipio,
        $fotoUsuario
    );

    return $stmt->execute();
}


function listarUsuarios($conexao){
    return $conexao->query("SELECT * FROM usuarios");
}


function buscarUsuario($conexao, $id){

    $sql = "SELECT * FROM usuarios
            WHERE idUsuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}


function buscarUsuariosPorNome($conexao, $nome){

    $sql = "SELECT * FROM usuarios
            WHERE nomeUsuario LIKE ?";

    $stmt = $conexao->prepare($sql);

    $nomeBusca = "%" . $nome . "%";

    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizarUsuario(
    $conexao,
    $id,
    $nome,
    $email,
    $nascimento,
    $cpf,
    $usernameUsuario,
    $senha,
    $dataCriacao,
    $municipio,
    $fotoUsuario
){

    $sql = "UPDATE usuarios
            SET nomeUsuario = ?,
                emailUsuario = ?,
                dataNascimentoUsuario = ?,
                cpfUsuario = ?,
                usernameUsuario = ?,
                senhaUsuario = ?,
                dataCriacao = ?,
                municipio_codigo = ?,
                fotoUsuario = ?
            WHERE idUsuario = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssssssisi",
        $nome,
        $email,
        $nascimento,
        $cpf,
        $usernameUsuario,
        $senha,
        $dataCriacao,
        $municipio,
        $fotoUsuario,
        $id
    );

    return $stmt->execute();
}


function deletarUsuario($conexao, $id){

    $sql = "DELETE FROM usuarios
            WHERE idUsuario = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


// =====================================================
// CRUD - ESTADOS
// =====================================================

function inserirEstado(
    $conexao,
    $codigoUf,
    $nome,
    $uf,
    $regiao
){

    $sql = "INSERT INTO estados
            (codigoUf, nome, uf, regiao)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "issi",
        $codigoUf,
        $nome,
        $uf,
        $regiao
    );

    return $stmt->execute();
}


function listarEstados($conexao){
    return $conexao->query(
        "SELECT * FROM estados ORDER BY nome ASC"
    );
}


function buscarEstado($conexao, $id){

    $sql = "SELECT * FROM estados
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}


function buscarEstadosPorNome($conexao, $nome){

    $sql = "SELECT * FROM estados
            WHERE nome LIKE ?";

    $stmt = $conexao->prepare($sql);

    $nomeBusca = "%" . $nome . "%";

    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizarEstado(
    $conexao,
    $id,
    $codigoUf,
    $nome,
    $uf,
    $regiao
){

    $sql = "UPDATE estados
            SET codigoUf = ?,
                nome = ?,
                uf = ?,
                regiao = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "issii",
        $codigoUf,
        $nome,
        $uf,
        $regiao,
        $id
    );

    return $stmt->execute();
}


function deletarEstado($conexao, $id){

    $sql = "DELETE FROM estados
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


// =====================================================
// CRUD - MUNICÍPIOS
// =====================================================

function inserirMunicipio(
    $conexao,
    $codigo,
    $nome,
    $uf,
    $estados_id
){

    $sql = "INSERT INTO municipios
            (codigo, nome, uf, estados_id)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "issi",
        $codigo,
        $nome,
        $uf,
        $estados_id
    );

    return $stmt->execute();
}


function listarMunicipios($conexao){

    return $conexao->query(
        "SELECT
            m.codigo,
            m.nome,
            m.uf,
            m.estados_id,
            e.nome AS nomeEstado
         FROM municipios m
         INNER JOIN estados e
         ON m.estados_id = e.id
         ORDER BY m.nome ASC"
    );
}


function buscarMunicipio($conexao, $codigo){

    $sql = "SELECT * FROM municipios
            WHERE codigo = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();

    return $stmt->get_result();
}


function buscarMunicipiosPorNome($conexao, $nome){

    $sql = "SELECT * FROM municipios
            WHERE nome LIKE ?";

    $stmt = $conexao->prepare($sql);

    $nomeBusca = "%" . $nome . "%";

    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizarMunicipio(
    $conexao,
    $codigo,
    $nome,
    $uf,
    $estados_id
){

    $sql = "UPDATE municipios
            SET nome = ?,
                uf = ?,
                estados_id = ?
            WHERE codigo = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssii",
        $nome,
        $uf,
        $estados_id,
        $codigo
    );

    return $stmt->execute();
}


function deletarMunicipio($conexao, $codigo){

    $sql = "DELETE FROM municipios
            WHERE codigo = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);

    return $stmt->execute();
}


// =====================================================
// UPLOAD DE FOTO DO USUÁRIO
// =====================================================

function uploadFotoUsuario($arquivo){

    $diretorio = 'uploads/usuarios/';

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $extensao = strtolower(
        pathinfo($arquivo['name'], PATHINFO_EXTENSION)
    );

    $permitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array($extensao, $permitidas)) {
        return false;
    }

    if ($arquivo['size'] > 2 * 1024 * 1024) {
        return false;
    }

    $nomeArquivo = uniqid() . "." . $extensao;

    $caminho = $diretorio . $nomeArquivo;

    if (move_uploaded_file(
        $arquivo['tmp_name'],
        $caminho
    )) {
        return $caminho;
    }

    return false;
}


// =====================================================
// CRUD - PRODUTOS
// =====================================================

function inserirProduto(
    $conexao,
    $nome,
    $descricao,
    $preco,
    $status,
    $usuario,
    $foto
){

    $sql = "INSERT INTO produtos
            (
                nomeProduto,
                descricaoProduto,
                precoProduto,
                statusProduto,
                usuario_idUsuario,
                fotoProduto
            )
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssd sis",
        $nome,
        $descricao,
        $preco,
        $status,
        $usuario,
        $foto
    );

    return $stmt->execute();
}


function listarProdutos($conexao){

    return $conexao->query(
        "SELECT
            p.*,
            u.nomeUsuario
         FROM produtos p
         LEFT JOIN usuarios u
         ON p.usuario_idUsuario = u.idUsuario
         ORDER BY p.idProduto DESC"
    );
}


function buscarProduto($conexao, $id){

    $sql = "SELECT * FROM produtos
            WHERE idProduto = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}


function buscarProdutosPorNome($conexao, $nome){

    $sql = "SELECT * FROM produtos
            WHERE nomeProduto LIKE ?";

    $stmt = $conexao->prepare($sql);

    $nomeBusca = "%" . $nome . "%";

    $stmt->bind_param("s", $nomeBusca);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizarProduto(
    $conexao,
    $id,
    $nome,
    $descricao,
    $preco,
    $status,
    $usuario,
    $foto
){

    $sql = "UPDATE produtos
            SET nomeProduto = ?,
                descricaoProduto = ?,
                precoProduto = ?,
                statusProduto = ?,
                usuario_idUsuario = ?,
                fotoProduto = ?
            WHERE idProduto = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssdsisi",
        $nome,
        $descricao,
        $preco,
        $status,
        $usuario,
        $foto,
        $id
    );

    return $stmt->execute();
}


function deletarProduto($conexao, $id){

    $sql = "DELETE FROM produtos
            WHERE idProduto = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


// =====================================================
// CRUD - AVALIAÇÕES
// =====================================================

function inserirAvaliacao(
    $conexao,
    $idUsuario,
    $idProduto,
    $comentario,
    $nota
){

    $sql = "INSERT INTO avaliacoes
            (idUsuario, idProduto, comentario, nota)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iisi",
        $idUsuario,
        $idProduto,
        $comentario,
        $nota
    );

    return $stmt->execute();
}


function listarAvaliacoes($conexao){

    return $conexao->query(
        "SELECT
            a.*,
            u.nomeUsuario,
            p.nomeProduto
         FROM avaliacoes a
         INNER JOIN usuarios u
         ON a.idUsuario = u.idUsuario
         INNER JOIN produtos p
         ON a.idProduto = p.idProduto
         ORDER BY a.dataAvaliacao DESC"
    );
}


function buscarAvaliacao($conexao, $id){

    $sql = "SELECT * FROM avaliacoes
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}


function atualizarAvaliacao(
    $conexao,
    $id,
    $comentario,
    $nota
){

    $sql = "UPDATE avaliacoes
            SET comentario = ?,
                nota = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sii",
        $comentario,
        $nota,
        $id
    );

    return $stmt->execute();
}


function deletarAvaliacao($conexao, $id){

    $sql = "DELETE FROM avaliacoes
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


function inserirFavorito(
    $conexao,
    $idUsuario,
    $idProduto
){

    $sql = "INSERT INTO favoritos
            (idUsuario, idProduto)
            VALUES (?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ii",
        $idUsuario,
        $idProduto
    );

    return $stmt->execute();
}


function listarFavoritos($conexao, $idUsuario){

    $sql = "SELECT
                f.id,
                f.idUsuario,
                f.idProduto,
                p.nomeProduto,
                p.descricaoProduto,
                p.precoProduto,
                p.statusProduto,
                p.fotoProduto
            FROM favoritos f
            INNER JOIN produtos p
            ON f.idProduto = p.idProduto
            WHERE f.idUsuario = ?
            ORDER BY f.id DESC";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param("i", $idUsuario);

    $stmt->execute();

    return $stmt->get_result();
}


function buscarFavorito(
    $conexao,
    $idUsuario,
    $idProduto
){

    $sql = "SELECT * FROM favoritos
            WHERE idUsuario = ?
            AND idProduto = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ii",
        $idUsuario,
        $idProduto
    );

    $stmt->execute();

    return $stmt->get_result();
}


function deletarFavorito(
    $conexao,
    $idUsuario,
    $idProduto
){

    $sql = "DELETE FROM favoritos
            WHERE idUsuario = ?
            AND idProduto = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ii",
        $idUsuario,
        $idProduto
    );

    return $stmt->execute();
}

?>
