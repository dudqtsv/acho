<?php

    $host = "mysql_lab";
    $user = "root";
    $password = "123";
    $bd = "banco";
    $conexao = new mysql($host, $user, $password, $bd);

    if($conexao->connect_error){
        die("Erro na conexão" . $conexao->connect_error);
    }
    echo "Conectado com sucesso"

?>
