<?php
require_once "conexao.php";
require "funcoes.php";
verificarLogin();

$id = $_SESSION['id'];
// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id'];

// Dados enviados pelo formulário
$idProduto = $_POST['idProduto'];
$comentario = $_POST['comentario'];
$nota = $_POST['nota'];
$dataAvaliacao = $_POST['dataAvaliacao'];

$sql = "INSERT INTO avaliacoes (idUsuario, idProduto, comentario, nota, dataAvaliacao)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt, "iisid", $idUsuario, $idProduto, $comentario, $nota, $dataAvaliacao);

mysqli_stmt_execute($stmt);

// Volta para a página do produto
header("Location: produto.php?id=$idProduto");

mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>




//form q enviara as avaliacoes
<form action="salvar_avaliacao.php" method="POST">

    <input type="hidden" name="idProduto" value="<?php echo $idProduto['idProduto']; ?>">

    <label>Nota:</label>
    <select name="nota" required>
        <option value="1">⭐</option>
        <option value="2">⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
    </select>

    <label>Comentário:</label>
    <textarea name="comentario" required></textarea>

    <button type="submit">
        Avaliar
    </button>

</form>

mudar no banco 

 impedir avaliações duplicadas

ALTER TABLE avaliacoes 
ADD UNIQUE (idUsuario, idProduto);