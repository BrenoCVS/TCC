<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    $header = 0;
    require "header2.php";
?>

    <br><br><br><br><br><br>
    <main>

        <div class="text-center">
            <img src="fotos/sucesso.jpeg" alt="">
            <br><br><br>
            <div class="alert alert-success " role="alert">
                <h4>Registro excluido com sucesso!</h4>
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
} else {
    redireciona();
}
require "footer.php";
    ?>