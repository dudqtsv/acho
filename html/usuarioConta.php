<?php 
require_once './conexao.php';
require './verificarLogin.php';
verificarLogin();
$id = $_SESSION['idUsuario'];
echo 'id: ';
echo $id;;
echo '<br>';
$sql = "SELECT u.*, m.nome AS nomeMunicipio, m.uf AS uf
        FROM usuarios u 
        JOIN municipios m ON u.municipio_codigo = m.codigo 
        WHERE u.idUsuario = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$nome = $usuario['nomeUsuario'];
$email = $usuario['emailUsuario'];
$data_nascimento = $usuario['dataNascimentoUsuario'];
$cpf = $usuario['cpfUsuario'];
$username = $usuario['usernameUsuario'];
$senha = $usuario['senhaUsuario'];
$municipio = $usuario['nomeMunicipio'];
$uf = $usuario['uf'];
$foto = $usuario['fotoUsuario'];
if (!$usuario) die("Usuário não encontrado.");
echo 'nome: ';
echo $nome ;
echo '<br>';
echo 'email: ';
echo $email;
echo '<br>';
echo 'data de nascimento: ';
echo $data_nascimento;
echo '<br>';
echo 'cpf: ';
echo $cpf;
echo '<br>';
echo 'username: ';
echo $username;
echo '<br>';
echo 'senha: ';
echo $senha;
echo '<br>';
echo 'municipio: ';
echo $municipio . ', ' . $uf;
echo '<br>';
echo 'foto: ';
echo $foto;
echo '<br>';
?>