<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header-adm.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id-adm', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_banco = filter_input(INPUT_GET, 'id-banco', FILTER_SANITIZE_SPECIAL_CHARS);


    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";
        $sql = "SELECT * FROM BANCO WHERE id_banco = '{$id_banco}' ";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch();

?>

        <main>
            <br><br><br>
            <div style="margin: 30px;">


                <form action="alterar-banco.php?mod=1&id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" method="post">


                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="relatorio" class="form-label">Escreva aqui o motivo do banco ser recusado:</label>
                                <textarea type="text" name="relatorio" id="relatorio" class="form-control" required></textarea>
                            </div>
                        </div>

                    </div>




                    <div class="mb-3">


                        <br><br>

                        <div class="bot">
                            <a href="info-banco.php?id-adm=<?= $id ?>&id-banco=<?= $id_banco ?>" class="btn btn-primary">
                                Voltar
                            </a>
                            <button type="submit" class="btn btn-success">Enviar</button>
                            <button type="reset" class="btn btn-warning">Apagar</button>

                        </div>
                    </div>
            </div>
            </div>

            </form>



            </div>
            <br><br>
            </div>
    <?php
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
