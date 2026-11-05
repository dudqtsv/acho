<?php
// Importa os municípios que faltam no banco, usando a API oficial do IBGE.
// Rode UMA vez no navegador (http://localhost:81/importarMunicipios.php) e depois APAGUE este arquivo.
// Municípios que já existem não são alterados (INSERT IGNORE).

require_once "./conexao.php";
$conexao->set_charset("utf8mb4");

header('Content-Type: text/plain; charset=utf-8');
set_time_limit(120);

$url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios";

// Baixa a lista (tenta cURL; se não der, file_get_contents)
$json = false;
if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 60, CURLOPT_FOLLOWLOCATION => true]);
    $json = curl_exec($ch);
    curl_close($ch);
}
if (!$json) {
    $json = @file_get_contents($url);
}
if (!$json) {
    die("Não consegui baixar a lista do IBGE. Confira se o container tem acesso à internet.\n");
}

$municipios = json_decode($json, true);
if (!is_array($municipios) || count($municipios) < 5000) {
    die("Resposta inesperada do IBGE.\n");
}

// id de cada estado na tabela estados, pela sigla
$idsEstados = [];
foreach ($conexao->query("SELECT id, uf FROM estados") as $e) {
    $idsEstados[$e['uf']] = (int) $e['id'];
}

$stmt = $conexao->prepare("INSERT IGNORE INTO municipios (codigo, nome, uf, estados_id) VALUES (?, ?, ?, ?)");

$novos = 0;
$semEstado = 0;

foreach ($municipios as $m) {
    $codigo = (int) $m['id'];
    $nome = $m['nome'];
    // a sigla do estado fica em um destes dois lugares, dependendo do município
    $uf = $m['microrregiao']['mesorregiao']['UF']['sigla']
        ?? $m['regiao-imediata']['regiao-intermediaria']['UF']['sigla']
        ?? null;

    if (!$uf || !isset($idsEstados[$uf])) {
        $semEstado++;
        continue;
    }

    $estadoId = $idsEstados[$uf];
    $stmt->bind_param("issi", $codigo, $nome, $uf, $estadoId);
    $stmt->execute();
    $novos += $stmt->affected_rows > 0 ? 1 : 0;
}

echo "Pronto!\n";
echo "Municípios novos inseridos: $novos\n";
echo "Ignorados por falta do estado: $semEstado\n";
echo "Total de municípios no banco agora: " . $conexao->query("SELECT COUNT(*) c FROM municipios")->fetch_assoc()['c'] . "\n";
echo "\nAgora apague o arquivo importarMunicipios.php.\n";