<?php
session_start();
require "logica-autenticacao.php";

if (autenticado()) {
    $header = 0;
    require "header.php";

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);


    require 'conexao.php';
    $sql = "erro";
    $stmt = $conn->prepare($sql);
    $errorArray = $stmt->errorInfo();

?>
    <br><br><br><br><br><br>
    <main>
        <div class="text-center">

            <img src="fotos/erro.png" alt="">
            <br><br><br>
            <div class="alert alert-danger" role="alert">
                <h4>Erro ao alterar dados do doador!</h4>
                <p><?= $errorArray[2]; ?></p>
            </div>
        </div>

    <?php
} else {
    redireciona();
}
require "footer_volta_login.php";
    ?>