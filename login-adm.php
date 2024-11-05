<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header-adm.php";
    require 'conexao.php';
    if ($_GET['id']) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
    $modi = filter_input(INPUT_GET, 'modi', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($tipo)) {
        $tipo = "";
    }

    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";
        if (!empty($tipo)) {
            $sql = "SELECT * FROM BANCO WHERE statusb = '{$tipo}' ";
            $stmt = $conn->query($sql);
        }

?>
        <div style="margin: 30px;">
            <br><br>
            <div class="row">
                <div class="col-9">
                    <form action="?modi=0" role="search" method="POST" class="row">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <label for="tipo" class="col-2">
                            Buscar por Bancos:
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="tipo" id="tipo">

                                <option value="" <?php if ($tipo == "")
                                                        echo "selected"; ?>>Selecione</option>
                                <option value="Pendente" <?php if ($tipo == "Pendente")
                                                                echo "selected"; ?>>Pendentes</option>
                                <option value="Recusado" <?php if ($tipo == "Recusado")
                                                                echo "selected"; ?>>Recusados</option>
                                <option value="Aprovado" <?php if ($tipo == "Aprovado")
                                                                echo "selected"; ?>>Aprovados</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-outline-success">
                                Filtrar <i class="bi bi-sort-down"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-3">
                    <div class="col-4 d-flex justify-content-center align-items-center">
                        <a href="sair.php" class="link-danger">
                            <button type="button" class="btn btn-danger ">
                                Sair <i class="bi bi-box-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                if (!empty($tipo)) {
                    while ($row = $stmt->fetch()) {
                ?>

                        <div style="border: solid black 3px; height: 290px; width: 10em; border-radius: 10%; text-align: center; margin: 1em;">
                            <h1><i class="bi bi-bank"></i></h1>
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                            <br>
                            <h5 class="fw-normal"><?= $row['nome'] ?></h5>
                            <br><br>
                            <p><a class="btn btn-outline-danger" href="info-banco.php?id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>">DETALHAR &raquo;</a></p>
                        </div>

                <?php

                    }
                }
                ?>
            </div>
        </div>
        <?php
        if ($modi == 1) {
            $modi = 0;
        ?>

            <script>
                alert("Ocorreu um erro ao modificar o status do banco")
            </script>

        <?php

        } else if ($modi == 2) {
            $modi = 0;
        ?>

            <script>
                alert("O status do banco foi modificado com sucesso!")
            </script>
<?php

        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
