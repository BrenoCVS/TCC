<?php
session_start();
$header = 0;
require "logica-autenticacao.php";
require "header2.php";
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
            <br><br>
            <a href="login_sucesso.php?id=<?= $id ?>" class="link-danger">
                <button type="button" class="btn btn-danger">
                    <p class="float-end">

                        Voltar para a página inicial

                    </p>
                </button>
            </a>
        </div>


    <?php
} else {
    redireciona();
}
require "footer.php";
    ?>