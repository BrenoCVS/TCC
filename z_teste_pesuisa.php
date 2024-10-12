<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header2.php";
    require 'conexao.php';
    if ($_GET['id']) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
    $modi = filter_input(INPUT_GET, 'modi', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!isset($tipo)) {
        $tipo = "data_doacao";
    }

    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";


        $sql_banco = "SELECT id_banco FROM funcionario WHERE id_funcionario = $id";
        $stmt_banco = $conn->query($sql_banco);
        $row_banco = $stmt_banco->fetch();
        $id_banco = $row_banco['id_banco'];
        $sql = "SELECT id_doacao, id_banco, id_doador, DATE_FORMAT(data_doacao, '%d/%m/%Y') AS data_formatada
        FROM doacao 
        WHERE id_banco = $id_banco
        ORDER BY '{$tipo}' ";
        $stmt = $conn->query($sql);

?>
        <div style="margin: 30px;">
            <br><br>
            <div class="row">
                <div class="col-9">
                    <form action="?modi=0" role="search" method="POST" class="row">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <label for="tipo" class="col-2">
                            Ordenar Funcionários por:
                        </label>
                        <div class="col-sm-2">
                            <select class="form-select" name="tipo" id="tipo">

                                <option value="id_doacao" <?php if ($tipo == "id_doacao")
                                                                echo "selected"; ?>>ID</option>
                                <option value="data_doacao" <?php if ($tipo == "data_doacao")
                                                                echo "selected"; ?> onclick="<?php $data = 1 ?>">Data</option>
                            </select>
                        </div>

                        <?php if ($data == 1) { ?>
                            <div class="col-sm-2">
                                funcionou
                            </div>
                        <?php } ?>

                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-outline-success">
                                Ordenar <i class="bi bi-sort-down"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-3">
                    <div class="col-4 d-flex justify-content-center align-items-center">
                        <a href="inicio-funcionario.php?id=<?= $id ?>" class="link-danger">
                            <button type="button" class="btn btn-danger ">
                                Voltar <i class="bi bi-box-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <?php
                while ($row = $stmt->fetch()) {
                    if ($tipo === "data_doacao") {
                ?>

                        <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                            <h1><i class="bi bi-droplet"></i></h1>
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                            <br>
                            <h5 class="fw-normal"><?= $row['data_formatada'] ?></h5>
                            <br><br>
                            <p><a class="btn btn-outline-danger" href="info-doacao.php?id=<?= $id ?>&id_doacao=<?= $row['id_doacao'] ?>">DETALHAR &raquo;</a></p>
                        </div>

                    <?php
                    } else if ($tipo === "id_doacao") {
                    ?>
                        <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                            <h1><i class="bi bi-droplet"></i></h1>
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                            <br>
                            <h5 class="fw-normal"> ID: <?= $row['id_doacao'] ?></h5>
                            <br><br>
                            <p><a class="btn btn-outline-danger" href="info-doacao.php?id=<?= $id ?>&id_doacao=<?= $row['id_doacao'] ?>">DETALHAR &raquo;</a></p>
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
                alert("Informações do Funcionário modificadas com sucesso!")
            </script>

        <?php

        } else if ($modi == 2) {
            $modi = 0;
        ?>

            <script>
                alert("Erro ao modificar infomações do Funcionário")
            </script>
        <?php

        } else if ($modi == 3) {
            $modi = 0;
        ?>

            <script>
                alert("Funcionário excluido com sucesso!")
            </script>
        <?php

        } else if ($modi == 4) {
            $modi = 0;
        ?>

            <script>
                alert("Erro ao excluir funcionário")
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
