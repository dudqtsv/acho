<?php
require_once "./conexao.php";

session_start();

function verificarLogin() {
    if (!isset($_SESSION['id'])) {
        header('Location: index.php?erro=1');
        exit();
    }
}
/////// USUARIOS //////
 function inserirusuario($conexao, $nomeUsuario, $emailUsuario, $dataNascimentoUsuario, $cpfUsuario, $usernameUsuario, $senhaUsuario, $datacriacao, $municipio_codigo,$fotoUsuario){
        $sql = "INSERT INTO usuarios (nomeUsuario, emailUsuario, dataNascimentoUsuario, cpfUsuario, usernameUsuarios, senhaUsuario, datacriacao, municipio_codigo, fotoUsuario)
            VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssssis", $nomeUsuario, $emailUsuario, $dataNascimentoUsuario, $cpfUsuario, $usernameUsuario, $senhaUsuario, $datacriacao, $municipio_codigo, $fotoUsuario);
        return $stmt->execute();

    }

    function listarUsuario($conexao){
        return $conexao->query("SELECT * FROM usuarios");
    }

   function buscarUsuario($conexao, $idUsuario){
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscarUsuariosPorNome($conexao, $nome){
        $sql = "SELECT * FROM usuarios WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarUsuario($conexao, $id, $nomeUsuario, $emailUsuario, $dataNascimentoUsuario, $cpfUsuario, $usernameUsuario, $senhaUsuario, $datacriacao, $municipio_codigo, $fotoUsuario){
        $sql = "UPDATE usuarios SET nomeUsuario = ?, emailUsuario = ?, dataNascimentoUsuario = ?, cpfUsuario = ?, usernameUsuarios = ?, senhaUsuario = ?, datacriacao = ?, municipio_codigo = ?, fotoUsuario = ?WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssssssi", $nomeUsuario, $emailUsuario, $dataNascimentoUsuario, $cpfUsuario, $usernameUsuario, $senhaUsuario, $datacriacao, $municipio_codigo, $fotoUsuario, $id);
        return $stmt->execute();
    }

    function deletarUsuario($conexao, $id){
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }



/////// MUNICIPIOS//////


       function inserirmunicipios($conexao, $nome, $uf, $estado, $usuarios_idUsuarios, $usuarios_estado_codigoUf){
        $sql = "INSERT INTO municipio (nome, uf, estado, usuarios_idusuarios, usuarios_estado_codigoUf, tipo)
            VALUES (?,?,?,?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("issiii", $nome, $uf, $estado, $usuarios_idUsuarios, $usuarios_estado_codigoUf);
        return $stmt->execute();

    }

    function listarmunicipos($conexao){
        return $conexao->query("SELECT * FROM municipio");
    }

    function buscarmunicipios($conexao, $id){
        $sql = "SELECT * FROM municipio WHERE id = ?";
        $stmt = $conexao->prepar
        ($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscarmunicipiosPorNome($conexao, $nome){
        $sql = "SELECT * FROM municipio WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarMunicipios($conexao, $codigo, $nome, $uf, $estado, $usuarios_idusuarios, $usuarios_estado_codigoUf){
        $sql = "UPDATE municipio SET codigo = ?, nome = ?, uf = ?, estado = ?, usuarios_idusuarios = ?, usuarios_estado_codigoUf = ? WHERE codigo = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssssi", $nome, $uf, $estado, $usuarios_idusuarios, $usuarios_estado_codigoUf, $codigo);
        return $stmt->execute();
    }

    function deletarMunicipios($conexao, $codigo){
        $sql = "DELETE FROM municipio WHERE codigo = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codigo);
        return $stmt->execute();
    }



//////ESTADO/////



     function inserirestados($conexao, $codigoUf, $nome, $uf, $regiao){
        $sql = "INSERT INTO estado (codigoUf, nome, uf, regiao, tipo)
            VALUES (?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssss", $codigoUf, $nome, $uf, $regiao);
        return $stmt->execute();

    }

    function listarestados($conexao){
        return $conexao->query("SELECT * FROM estado");
    }

    function buscarestados($conexao, $codigoUf){
        $sql = "SELECT * FROM estado WHERE codigoUf = ?";
        $stmt = $conexao->prepar
        ($sql);
        $stmt->bind_param("i", $codigoUf);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscarestadosPorNome($conexao, $nome){
        $sql = "SELECT * FROM estado WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarEstados($conexao, $codigoUf, $nome, $uf, $regiao){
        $sql = "UPDATE estado SET codigoUf = ?, uf = ?, regiao = ?  WHERE codigoUf = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssi",$nome, $uf, $regiao, $codigoUf);
        return $stmt->execute();
    }

    function deletarEstados($conexao, $codigoUf){
        $sql = "DELETE FROM estado WHERE codigoUf = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $codigoUf);
        return $stmt->execute();
    }



    //////avaliacoes//////

     function inseriravaliacoes($conexao, $idUsuario, $idProduto, $comentario, $nota, $dataAvaliacao){
        $sql = "INSERT INTO avaliacoes (idUsuario, idProduto, comentario, nota, dataAvaliacao)
            VALUES (?,?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iisis", $idUsuario, $idProduto, $comentario, $nota, $dataAvaliacao);
        return $stmt->execute();

    }

    function listaravaliacoes($conexao){
        return $conexao->query("SELECT * FROM avaliacoes");
    }

    function buscaravaliacoes($conexao, $id){
        $sql = "SELECT * FROM avaliacoes WHERE id = ?";
        $stmt = $conexao->prepar
        ($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscaravaliacoesPorNome($conexao, $nome){
        $sql = "SELECT * FROM avaliacoes WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarAvaliacao($conexao, $id, $idUsuario, $idProduto, $comentario, $nota, $dataAvaliacao){
        $sql = "UPDATE avaliacoes SET idUsuario = ?, idProduto = ?, comentario = ?, nota = ?, dataAvaliacao = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iisis", $idUsuario, $idProduto, $comentario, $nota, $dataAvaliacao);
        return $stmt->execute();
    }

    function deletarAvaliacao($conexao, $id){
        $sql = "DELETE FROM avaliacoes WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /////////produtos////////

     function inserirproduto($conexao, $nomeProduto, $descricaoProduto, $precoProduto, $statusProduto, $dataPublicacaoProduto, $usuarios_idUsuario, $fotoProduto){
        $sql = "INSERT INTO produtos (nomeProduto, descricaoProduto, precoProduto, statusProduto, dataPublicacaoProduto, usuarios_idUsuario, fotoProduto)
            VALUES (?,?,?,?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssdssis", $nomeProduto, $descricaoProduto, $precoProduto, $statusProduto, $dataPublicacaoProduto, $usuarios_idUsuario, $fotoProduto);
        return $stmt->execute();

    }

    function listarprodutos($conexao){
        return $conexao->query("SELECT * FROM produtos");
    }

    function buscarprodutos($conexao, $id){
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $conexao->prepar
        ($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscarprodutosPorNome($conexao, $nome){
        $sql = "SELECT * FROM produtos WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarProduto($conexao, $id, $nomeProduto, $descricaoProduto, $precoProduto, $statusProduto, $dataPublicacaoProduto, $usuarios_idUsuario, $fotoProduto){
        $sql = "UPDATE produtos SET nomeProduto = ?, descricaoProduto = ?, precoProduto = ?, statusProduto = ?, dataPublicacaoProduto = ?, usuarios_idUsuario = ?, fotoProduto = ?WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssssssi", $nomeProduto, $descricaoProduto, $precoProduto, $statusProduto, $dataPublicacaoProduto, $usuarios_idUsuario, $fotoProduto);
        return $stmt->execute();
    }

    function deletarProduto($conexao, $id){
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }




    //////favoritos//////// 

     function inserirfavoritos($conexao, $idUsuario, $idProduto){
        $sql = "INSERT INTO favoritos (idUsuario, idProduto)
            VALUES (?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iii", $idUsuario, $idProduto);
        return $stmt->execute();

    }

    function listarfavoritos($conexao){
        return $conexao->query("SELECT * FROM favoritos");
    }

    function buscarfavoritos($conexao, $id){
        $sql = "SELECT * FROM favoritos WHERE id = ?";
        $stmt = $conexao->prepar
        ($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    function buscarfavoritosPorNome($conexao, $nome){
        $sql = "SELECT * FROM favoritos WHERE nome LIKE ?";
        $stmt = $conexao->prepare($sql);
        $nomeBusca = "%".$nome."%";
        $stmt->bind_param("s", $nomeBusca);
        $stmt->execute();
        return $stmt->get_result();
    }

    function atualizarFavorito($conexao, $id, $idUsuario, $idProduto){
        $sql = "UPDATE favoritos SET idUsuario = ?, idProduto = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iiisi", $idUsuario, $idProduto, $id);
        return $stmt->execute();
    }

    function deletarFavorito($conexao, $id){
        $sql = "DELETE FROM favoritos WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }