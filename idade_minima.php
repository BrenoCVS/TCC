<?php
$header = 1;
require "header.php";
?>

<br><br><br><br><br><br>
<main>
    <div class="text-center">

        <img src="fotos/erro.png" alt="">
        <br><br><br>
        <div class="alert alert-danger" role="alert">
            <h4>O Usuário não possui a idade minima de 16 anos completos para ser Doador!</h4>
        </div>
        <br><br>
        <a href="sair.php" class="link-danger">
            <button type="button" class="btn btn-danger">
                <p class="float-end">

                    Voltar para a página inicial

                </p>
            </button>
        </a>
    </div>

    <?php
    require "footer.php";
    ?>