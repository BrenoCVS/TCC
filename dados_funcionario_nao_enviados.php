<?php
$header = 1;
require "header2.php";
$id = filter_input(INPUT_GET, 'id');

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
            <h4>Erro ao enviar dados!</h4>
            <p><?= $errorArray[2]; ?></p>
        </div>
    </div>


    <?php
    require "footer_formulario_funcionario.php";
    ?>