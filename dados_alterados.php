<?php
session_start();
$header = 0;
require "logica-autenticacao.php";
require "header.php";
if (autenticado()) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

?>


    <br><br><br><br><br><br>
    <main>
        <div class="text-center">

            <img src="fotos/sucesso.jpeg" alt="">
            <br><br><br>
            <div class="alert alert-success row-8" role="alert">
                <h4>Dados de doador alterados com sucesso!</h4>

            </div>
        </div>


    <?php
} else {
    redireciona();
}
require "footer_volta_login.php";
    ?>