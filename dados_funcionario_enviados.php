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
        <br><br>
        <a href="inicio-banco.php?id=<?= $id ?>" class="link-danger">
            <button type="button" class="btn btn-danger">
                <p class="float-end">

                    Voltar para a página Inicial do Banco

                </p>
            </button>
        </a>
    </div>


    <?php
    require "footer.php";
    ?>