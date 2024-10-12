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
    $pesquisa = filter_input(INPUT_POST, 'pesquisa');

    if (empty($tipo)) {
        $tipo = "nome";
    }

    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";

        if ($tipo == "id_doador") {
            if (empty($pesquisa)) {
                $sql = "SELECT * FROM doador ORDER BY idDoador ";
                $stmt = $conn->query($sql);
            } else {
                $sql = "SELECT * FROM doador  WHERE idDoador = '{$pesquisa}'  ORDER BY idDoador";
                $stmt = $conn->query($sql);
            }
        } else if ($tipo == "nome") {
            if (empty($pesquisa)) {
                $sql = "SELECT * FROM doador ORDER BY nome ";
                $stmt = $conn->query($sql);
            } else {
                $sql = "SELECT * FROM doador WHERE nome LIKE '{$pesquisa}%' ORDER BY nome";
                $stmt = $conn->query($sql);
            }
        }

?>
        <div style="margin: 30px;">
            <br><br>
            <div class="row">
                <div class="col-9">
                    <form action="?modi=0" role="search" method="POST" class="row">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <label for="tipo" class="col-2">
                            Ordenar Doadores por:
                        </label>
                        <div class="col-2">
                            <select class="form-select" name="tipo" id="tipo">

                                <option value="id_doador" <?php if ($tipo == "id_doador")
                                                                echo "selected"; ?>>ID</option>
                                <option value="nome" <?php if ($tipo == "nome")
                                                            echo "selected"; ?>>Nome</option>
                            </select>
                        </div>
                        <div class="col">

                            <input type="text" class="form-control" id="pesquisa" name="pesquisa" placeholder="Campo de Pesquisa" <?php if (!empty($pesquisa)) {
                                                                                                                                        echo "value='$pesquisa'";
                                                                                                                                    } ?>>
                        </div>
                        <div class="col">
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
                if ($stmt && $stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        if ($tipo === "nome") {
                ?>

                            <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                                <h1><i class="bi bi-bank"></i></h1>
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                                <br>
                                <h5 class="fw-normal"><?= $row['nome'] ?></h5>
                                <br><br>
                                <p><a class="btn btn-outline-danger" href="info-doador.php?id_doador=<?= $row['idDoador'] ?>&id=<?= $id ?>">DETALHAR &raquo;</a></p>
                            </div>

                        <?php

                        } else if ($tipo === "id_doador") {
                        ?>
                            <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                                <h1><i class="bi bi-bank"></i></h1>
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                                <br>
                                <h5 class="fw-normal"> ID: <?= $row['idDoador'] ?></h5>
                                <br><br>
                                <p><a class="btn btn-outline-danger" href="info-doador.php?id_doador=<?= $row['idDoador'] ?>&id=<?= $id ?>">DETALHAR &raquo;</a></p>
                            </div>
                <?php
                        }
                    }
                } else {
                    echo "<p>Nenhum resultado encontrado.</p>";
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
