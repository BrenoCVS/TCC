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
        $tipo = "id_doacao";
    }

    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";

        if ($tipo == "data_doacao") {
            if (empty($pesquisa)) {
                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
               DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                FROM doacao d
                JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                WHERE f.id_funcionario = :id_funcionario
                ORDER BY d.{$tipo}";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id_funcionario' => $id]);
            } else {
                $dataFormatada = date('Y-m-d', strtotime(str_replace('/', '-', $pesquisa)));
                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
               DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                FROM doacao d
                JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                WHERE f.id_funcionario = :id_funcionario AND d.data_doacao = '{$dataFormatada}'
                ORDER BY d.{$tipo}";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id_funcionario' => $id]);
            }
        } else if ($tipo == "id_doacao") {
            if (empty($pesquisa)) {
                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
               DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                FROM doacao d
                JOIN banco b ON d.id_banco = b.id_banco
                JOIN funcionario f ON b.id_banco = f.id_banco
                WHERE f.id_funcionario = :id_funcionario
                ORDER BY d.{$tipo}";


                $stmt = $conn->prepare($sql);
                $stmt->execute(['id_funcionario' => $id]);
            } else {
                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
               DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                FROM doacao d
                JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                WHERE f.id_funcionario = :id_funcionario AND d.id_doacao = {$pesquisa}
                ORDER BY d.{$tipo}";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id_funcionario' => $id]);
            }
        } else if ($tipo == "nome_doador") {
            if (empty($pesquisa)) {
                $sql_donacoes = "SELECT 
                    d.id_doacao, 
                    d.data_doacao, 
                    dd.nome 
                 FROM 
                    doacao d 
                 JOIN 
                    doador dd ON d.id_doador = dd.idDoador 
                 JOIN 
                    funcionario f ON d.id_banco = f.id_banco 
                 WHERE 
                    f.id_funcionario = :id_funcionario 
                    ORDER BY dd.nome";

                $stmt = $conn->prepare($sql_donacoes);
                $stmt->execute([
                    ':id_funcionario' => $id
                ]);
            } else {
                $sql_donacoes = "SELECT 
                    d.id_doacao, 
                    d.data_doacao, 
                    dd.nome 
                 FROM 
                    doacao d 
                 JOIN 
                    doador dd ON d.id_doador = dd.idDoador 
                 JOIN 
                    funcionario f ON d.id_banco = f.id_banco 
                
                    WHERE
                    f.id_funcionario = :id_funcionario
                 AND 
                    dd.nome LIKE :nome_doador
                    ORDER BY dd.nome";

                $stmt = $conn->prepare($sql_donacoes);
                $stmt->execute([
                    ':id_funcionario' => $id,
                    ':nome_doador' => "%" . $pesquisa . "%"
                ]);
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
                            Ordenar Doações por:
                        </label>
                        <div class="col-sm-3">
                            <select class="form-select" name="tipo" id="tipo">

                                <option value="id_doacao" <?php if ($tipo == "id_doacao")
                                                                echo "selected"; ?> onclick="campoNormal()">ID</option>
                                <option value="data_doacao" <?php if ($tipo == "data_doacao")
                                                                echo "selected"; ?> onclick="campoData()">Data</option>
                                <option value="nome_doador" <?php if ($tipo == "nome_doador")
                                                                echo "selected"; ?> onclick="campoNormal()">Nome do Doador</option>
                            </select>
                        </div>
                        <div class="col" id="campo_pesquisa">
                            <input type="text" class="form-control" id="pesquisa" name="pesquisa" placeholder="Campo de Pesquisa" <?php if (!empty($pesquisa)) {
                                                                                                                                        echo "value='$pesquisa'";
                                                                                                                                    } ?>>
                        </div>
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
                    } else if ($tipo === "nome_doador") {
                    ?>
                        <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                            <h1><i class="bi bi-droplet"></i></h1>
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                            <br>
                            <h5 class="fw-normal"> Nome: <?= $row['nome'] ?></h5>
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
        ?>
        <script>
            function campoData() {
                document.getElementById("pesquisa").outerHTML = '<input type="text" class="form-control" id="pesquisa" name="pesquisa" placeholder="certo de Pesquisa" maxlength="10"  onkeypress="mascaraData( this, event )" >';
            }

            function campoNormal() {
                document.getElementById("pesquisa").outerHTML = '<input type="text" class="form-control" id="pesquisa" name="pesquisa" placeholder="Campo de Pesquisa">';
            }

            function mascaraData(campo, e) {

                var kC = (document.all) ? event.keyCode : e.keyCode;
                var data = campo.value;

                data = data.replace(/[^0-9\/]/g, '');

                if (kC != 8 && kC != 46) {
                    if (data.length == 2) {
                        campo.value = data += '/';
                    } else if (data.length == 5) {
                        campo.value = data += '/';
                    } else
                        campo.value = data;
                }
            }
        </script>
<?php
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
