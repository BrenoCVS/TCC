<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    $header = 0;
    require "header.php";
?>

    <br><br><br><br><br><br>
    <main>

        <div class="text-center">
            <img src="fotos/sucesso.jpeg" alt="">
            <br><br><br>
            <div class="alert alert-success " role="alert">
                <h4>Registro excluido com sucesso!</h4>
            </div>
        </div>
    <?php
} else {
    redireciona();
}
require "footer_volta_inicio.php";
    ?>