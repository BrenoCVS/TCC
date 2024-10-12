<?php


$header = 1;
require "header2.php";
$id = filter_input(INPUT_GET, 'id');

?>

<br><br><br><br><br><br>
<main>
    <div class="text-center">

        <img src="fotos/sucesso.jpeg" alt="">
        <br><br><br>
        <div class="alert alert-success row-8" role="alert">
            <h4>Dados enviados com sucesso!</h4>

        </div>
    </div>


    <?php
    require "footer_inicio_banco.php";
    ?>