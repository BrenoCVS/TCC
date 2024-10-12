<?php
$header = 1;
require "header.php";

require 'conexao.php';

$id = filter_input(INPUT_GET, 'id');

$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);


?>
<br><br><br><br><br><br>
<main>
    <div class="text-center">

        <img src="fotos/erro.png" alt="">
        <br><br><br>
        <div class="alert alert-danger" role="alert">
            <h4>Erro ao enviar dados do funcionario!</h4>
            <p>O e-mail: <b><?= $email ?></b> já esta em uso!</p>
            <p>Por favor tente um e-mail diferente.</p>
        </div>
        <p><a class="btn btn-danger" href="formulario-cadastrar-funcionario.php?id=<?= $id ?>">Voltar ao formulario de funcionario &raquo;</a></p>
    </div>


    <?php
    require "footer.php";
    ?>