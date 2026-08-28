<?php

require_once "funcoes.php";

$idProdutos = $_GET['id'] ?? null;

$idUsuarioLogado = $_SESSION['id'] ?? null;

// Busca o produto + dados do autor (dono do anúncio)
$sql_produto = "
    SELECT produtos.*, usuarios.nomeUsuario, usuarios.usernameUsuario, usuarios.fotoUsuario
    FROM produtos
    JOIN usuarios ON produtos.usuario_idUsuario = usuarios.idUsuario
    WHERE produtos.idProduto = ?
";

$stmt = mysqli_prepare($conexao, $sql_produto);
mysqli_stmt_bind_param($stmt, "i", $idProdutos);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($resultado);

if (!$produto) {
  echo "Produto não encontrado.";
  exit;
}

// Busca as avaliações (nota + comentário) desse produto, com dados de quem avaliou
$sql_aval = "
    SELECT comentario, nota, dataAvaliacao, nomeUsuario, usernameUsuario, fotoUsuario
    FROM avaliacoes
    JOIN usuarios ON avaliacoes.idUsuario = usuarios.idUsuario
    WHERE avaliacoes.idProduto = ?
    ORDER BY avaliacoes.id ASC
";
$stmt2 = mysqli_prepare($conexao, $sql_aval);
mysqli_stmt_bind_param($stmt2, "i", $idProdutos);
mysqli_stmt_execute($stmt2);
$avaliacoes = mysqli_stmt_get_result($stmt2);
$totalAvaliacoes = mysqli_num_rows($avaliacoes);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Produto — <?php echo htmlspecialchars($produto['nomeProduto']); ?></title>
  <link rel="stylesheet" href="../css/produtos.css">
  <link rel="stylesheet" href="../css/feed.css">
  <link rel="stylesheet" href="../css/telas-menores.css">
</head>

<body>

  <div class="caxona">

    <div class="produtos_autor">
      <div class='autor'>
        <img src='../fotos/<?php echo htmlspecialchars($produto['fotoUsuario'] ?? 'default.png'); ?>' class='foto_perfil'>
        <span class='n_autor'><?php echo htmlspecialchars($produto['usernameUsuario']); ?></span>
        <span id='ponto'>•</span>
        <span class='data-produtosagem'><?php echo isset($produto['dataPublicacaoProduto']) ? date('d/m/Y H:i', strtotime($produto['dataPublicacaoProduto'])) : ''; ?></span>
      </div>
      <a href="feed.php" class="voltar">
        <svg viewBox="0 0 24 24" width="20" height="20">
          <path d="M5 5l14 14m0-14L5 19" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" />
        </svg>
      </a>
    </div>

    <div class="conteudo">
      <h2><?php echo htmlspecialchars($produto['nomeProduto']); ?></h2>
      <p class="preco">R$ <?php echo htmlspecialchars(number_format((float) $produto['precoProduto'], 2, ',', '.')); ?></p>
      <p class="categoria-condicao">
        <?php echo htmlspecialchars($produto['categoriaProduto'] ?? ''); ?>
        &middot;
        <?php echo htmlspecialchars($produto['condicaoProduto'] ?? ''); ?>
      </p>
      <p><?php echo nl2br(htmlspecialchars($produto['descricaoProduto'])); ?></p>
      <?php if (!empty($produto['fotoProduto'])) { ?>
        <img src="../<?php echo htmlspecialchars($produto['fotoProduto']); ?>" alt="Imagem do produto" />
      <?php } ?>
    </div>

    <div class="comentarios">
      <h3>Avaliações (<?php echo $totalAvaliacoes; ?>)</h3>

      <div class="comentario">
        <form action="avaliar.php" method="POST" class="campo-comentario">
          <input type="hidden" name="idProduto" value="<?php echo htmlspecialchars($produto['idProduto']); ?>">

          <label for="nota">Nota</label>
          <select name="nota" required>
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
          </select>


          <input type="text" name="comentario" class="entrada" placeholder="Escreva sua avaliação..." required />
          <button type="submit" class="botao-enviar" aria-label="Enviar avaliação">
            <svg viewBox="0 0 24 24" width="20" height="20">
              <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
            </svg>
          </button>
        </form>
      </div>

      <?php while ($av = mysqli_fetch_assoc($avaliacoes)) { ?>
        <div class="comentario">
          <p>
            <img src='../fotos/<?php echo htmlspecialchars($av['fotoUsuario'] ?? 'default.png'); ?>' class='foto_coment'>
            <strong><?php echo htmlspecialchars($av['usernameUsuario']); ?></strong>
            <span class="nota-avaliacao">(Nota: <?php echo htmlspecialchars($av['nota']); ?>/5)</span>
            : <?php echo htmlspecialchars($av['comentario']); ?>
          </p>
        </div>
      <?php } ?>
    </div>

  </div>

</body>

</html>