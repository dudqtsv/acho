<?php

require_once "funcoes.php";

$idProdutos = $_GET['id'] ?? null;

$idUsuarioLogado = $_SESSION['id'] ?? null;

if ($_GET['prodid'] ?? null) {
    $idUsuario = $_GET['prodid'];

    listarProdutosUsuario($conexao, $idUsuario);
}

else {

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

      <style>

        :root {
            --azul-marca: #2f3a8f;
            --azul-marca-escuro: #232a6b;
            --azul-claro: #eef0fb;
            --texto-principal: #1c1c1c;
            --texto-secundario: #6b6b6b;
            --branco: #ffffff;
            --cinza-fundo: #f4f5f7;
            --cinza-borda: #e4e4e7;
            --vermelho-coracao: #e63950;
            --sombra-card: 0 1px 3px rgba(0, 0, 0, 0.08);
            --sombra-card-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
            --raio: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--texto-principal);
            background: var(--cinza-fundo);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .caxona {
            max-width: 720px;
            margin: 32px auto 64px;
            background: var(--branco);
            border: 1px solid var(--cinza-borda);
            border-radius: var(--raio);
            overflow: hidden;
            box-shadow: var(--sombra-card);
        }

        /* Cabeçalho do autor */

        .produtos_autor {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--cinza-borda);
        }

        .produtos_autor .autor {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .foto_perfil {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--cinza-borda);
        }

        .n_autor {
            font-weight: 600;
            font-size: 14px;
            color: var(--texto-principal);
        }

        #ponto {
            color: var(--texto-secundario);
            font-size: 12px;
        }

        .data-produtosagem {
            font-size: 13px;
            color: var(--texto-secundario);
        }

        .voltar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: var(--texto-secundario);
            transition: background 0.15s ease, color 0.15s ease;
        }

        .voltar:hover {
            background: var(--cinza-fundo);
            color: var(--azul-marca);
        }

        /* Conteúdo do produto */

        .conteudo {
            padding: 24px;
        }

        .conteudo h2 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 700;
            color: var(--texto-principal);
        }

        .conteudo .preco {
            margin: 0 0 8px;
            font-size: 20px;
            font-weight: 700;
            color: var(--azul-marca);
        }

        .conteudo .categoria-condicao {
            margin: 0 0 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--texto-secundario);
        }

        .conteudo p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--texto-principal);
        }

        .conteudo img {
            width: 100%;
            border-radius: var(--raio);
            margin-top: 16px;
            display: block;
            object-fit: cover;
        }

        /* Avaliações */

        .comentarios {
            padding: 24px;
            border-top: 1px solid var(--cinza-borda);
            background: var(--cinza-fundo);
        }

        .comentarios h3 {
            margin: 0 0 16px;
            font-size: 17px;
            font-weight: 700;
            color: var(--texto-principal);
        }

        .comentario {
            background: var(--branco);
            border: 1px solid var(--cinza-borda);
            border-radius: var(--raio);
            padding: 14px 16px;
            margin-bottom: 12px;
        }

        /* Formulário */

        .campo-comentario {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .campo-comentario label {
            font-size: 13px;
            color: var(--texto-secundario);
        }

        .campo-comentario select {
            padding: 8px 10px;
            border: 1px solid var(--cinza-borda);
            border-radius: 8px;
            font-size: 14px;
            background: var(--branco);
        }

        .entrada {
            flex: 1;
            min-width: 160px;
            padding: 10px 14px;
            border: 1px solid var(--cinza-borda);
            border-radius: 999px;
            font-size: 14px;
            outline: none;
        }

        .entrada:focus {
            border-color: var(--azul-marca);
        }

        .botao-enviar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: var(--azul-marca);
            color: var(--branco);
            cursor: pointer;
            flex-shrink: 0;
        }

        .botao-enviar:hover {
            background: var(--azul-marca-escuro);
        }

        /* Avaliações publicadas */

        .comentario p {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .foto_coment {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--cinza-borda);
        }

        .comentario strong {
            color: var(--texto-principal);
        }

        .nota-avaliacao {
            font-size: 13px;
            color: var(--texto-secundario);
        }

        /* Responsivo */

        @media (max-width: 640px) {

            .caxona {
                margin: 0;
                border-radius: 0;
                border: none;
            }

            .produtos_autor,
            .conteudo,
            .comentarios {
                padding: 16px;
            }

            .campo-comentario {
                flex-direction: column;
                align-items: stretch;
            }

            .botao-enviar {
                align-self: flex-end;
            }

        }

    </style>
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
      <a href="home.php" class="voltar">
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
        <img src="../fotos/<?php echo htmlspecialchars($produto['fotoProduto']); ?>" alt="Imagem do produto" />
      <?php } ?>
    </div>

    <div class="comentarios">
      <h3>Avaliações (<?php echo $totalAvaliacoes; ?>)</h3>

      <div class="comentario">
        <form action="avaliacoes.php" method="POST" class="campo-comentario">
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
      <?php }} ?>
    </div>

  </div>

</body>

</html>