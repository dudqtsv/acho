<?php

require_once "funcoes.php";

$idProdutos = $_GET['idProdutos'];

$idUsuario = $_SESSION['id'];

$sql_produto = "
SELECT 
    idProduto,
    nomeProduto,
    descricaoProduto,
    precoProduto,
    statusProduto,
    dataPublicacaoProduto,
    usuario_idUsuario,
    fotoProduto
FROM produtos
WHERE idProduto = ?
";

$stmt = mysqli_prepare($conexao, $sql_produto);

mysqli_stmt_bind_param($stmt, "i", $idProduto);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$produto = mysqli_fetch_assoc($resultado);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

$sql_coment = "
SELECT c.conteudo, u.nick_name, u.foto_perfil
FROM comentario c
JOIN usuario u ON c.usuario_id = u.id
WHERE c.produtos_id = ?
ORDER BY c.id ASC
";
$stmt2 = mysqli_prepare($conexao, $sql_coment);
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$comentarios = mysqli_stmt_get_result($stmt2);

$foto_perfil = $produtos['foto_perfil'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>produtos completo com comentários</title>
  <link rel="stylesheet" href="../css/produtos.css">
  <link rel="stylesheet" href="../css/feed.css">
  <link rel="stylesheet" href="../css/telas-menores.css">
</head>
<body>



<div class="caxona">

  <div class="produtos_autor">
    <div class='autor'>
      <img src='../imagens/<?php echo $produtos['foto_perfil']; ?>' class='foto_perfil'>
      <span class='n_autor'><?php echo $produtos['nick_name']; ?></span>
      <span id='ponto'>•</span> 
      <span class='data-produtosagem'><?php echo date('d/m/Y H:i', strtotime($produtos['data_hora'])); ?></span>
    </div>
    <a href="feed.php" class="voltar">
<svg viewBox="0 0 24 24" width="20" height="20">
    <path d="M5 5l14 14m0-14L5 19" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>
  </svg>
</a>
  </div>

  <div class="conteudo">
    <p><?php echo htmlspecialchars($produtos['conteudo']); ?></p>
    <?php if ($produtos['imagem']) { ?>
      <img src="../imagens/<?php echo $produtos['imagem']; ?>" alt="Imagem do produtos" />
    <?php } ?>
    </div>


  <div class="comentarios">
    <h3>Comentários (<?php echo $produtos['comentarios']; ?>)</h3>


    <div class="comentario">
  <form action="comentar.php" method="produtos" class="campo-comentario">
    <input type="hidden" name="produtos_id" value="<?php echo $produtos['id_produtos']; ?>">
    <input type="text" name="comentario" class="entrada" placeholder="Escreva seu comentário..." required />
    <button type="submit" class="botao-enviar" aria-label="Enviar comentário">
      <svg viewBox="0 0 24 24" width="20" height="20">
        <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
      </svg>
    </button>
  </form>
  </div>


    <?php while ($coment = mysqli_fetch_assoc($comentarios)) { ?>
      <div class="comentario">
      <p><img src='../imagens/<?php echo $coment['foto_perfil']; ?>' class='foto_coment'><strong><?php echo $coment['nick_name']; ?></strong>: <?php echo htmlspecialchars($coment['conteudo']); ?></p>
      </div>
    <?php } ?>
  </div>


</div>


</body>
</html>
