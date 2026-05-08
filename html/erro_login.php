<?php

if (isset($_SESSION["erro"]) && $_SESSION["erro"] != NULL) {
    $erro = $_SESSION["erro"];
    echo "<span id='erro'>$erro</span>";
    $_SESSION["erro"] = NULL;

    echo '<style>
            #div_butao {
                margin-top: 2%;
            }
        </style>';
}


?>