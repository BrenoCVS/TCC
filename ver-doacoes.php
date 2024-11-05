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

    $data_inicial = "";
    $data_final = "";
    $tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS));
    $modi = filter_input(INPUT_GET, 'modi', FILTER_SANITIZE_SPECIAL_CHARS);
    $pesquisa = filter_input(INPUT_POST, 'pesquisa');
    $data_inicial = filter_input(INPUT_POST, 'data_inicial', FILTER_SANITIZE_SPECIAL_CHARS);
    $data_final = filter_input(INPUT_POST, 'data_final', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($tipo)) {
        $tipo = "";
    }

    if ($_SESSION["idDoador"] == $id) {
        if ($tipo == "data_doacao") {
            if (empty($data_inicial) && empty($data_final)) {
                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
                        DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                        FROM doacao d
                        JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                        WHERE f.id_funcionario = :id_funcionario
                        ORDER BY d.{$tipo}";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id_funcionario' => $id]);
            } else {
                $data_inicial_formatada = date('Y-m-d', strtotime(str_replace('/', '-', $data_inicial)));
                $data_final_formatada = date('Y-m-d', strtotime(str_replace('/', '-', $data_final)));

                $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, 
                        DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada
                        FROM doacao d
                        JOIN funcionario f ON d.id_funcionario = f.id_funcionario
                        WHERE f.id_funcionario = :id_funcionario 
                        AND d.data_doacao BETWEEN :data_inicial AND :data_final
                        ORDER BY d.{$tipo}";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'id_funcionario' => $id,
                    'data_inicial' => $data_inicial_formatada,
                    'data_final' => $data_final_formatada
                ]);
            }
        } else if ($tipo == "nome_doador") {
            if (empty($pesquisa)) {
                $sql_donacoes = "SELECT d.id_doacao, d.data_doacao, dd.nome 
                                 FROM doacao d 
                                 JOIN doador dd ON d.id_doador = dd.idDoador 
                                 JOIN funcionario f ON d.id_banco = f.id_banco 
                                 WHERE f.id_funcionario = :id_funcionario 
                                 ORDER BY dd.nome";

                $stmt = $conn->prepare($sql_donacoes);
                $stmt->execute([':id_funcionario' => $id]);
            } else {
                $sql_donacoes = "SELECT d.id_doacao, d.data_doacao, dd.nome 
                                 FROM doacao d 
                                 JOIN doador dd ON d.id_doador = dd.idDoador 
                                 JOIN funcionario f ON d.id_banco = f.id_banco 
                                 WHERE f.id_funcionario = :id_funcionario 
                                 AND dd.nome LIKE :nome_doador
                                 ORDER BY dd.nome";

                $stmt = $conn->prepare($sql_donacoes);
                $stmt->execute([
                    ':id_funcionario' => $id,
                    ':nome_doador' => $pesquisa . "%"
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

                        <div class="col-4">
                            <select class="form-select" name="tipo" id="tipo">
                                <option value="" <?php if ($tipo == "") echo "selected"; ?> onclick="campoNormal()">Escolha o método de Pesquisa</option>
                                <option value="data_doacao" <?php if ($tipo == "data_doacao") echo "selected"; ?> onclick="campoData()">Data</option>
                                <option value="nome_doador" <?php if ($tipo == "nome_doador") echo "selected"; ?> onclick="campoNormal()">Nome do Doador</option>
                            </select>
                        </div>
                        <div class="col" id="campo_pesquisa">
                            <?php if ($tipo != "data_doacao") { ?>
                                <input type="text" class="form-control" id="pesquisa" name="pesquisa" placeholder="Campo de Pesquisa" <?php if (!empty($pesquisa)) echo "value='$pesquisa'"; ?>>
                            <?php } else { ?>
                                <label id="pesquisa">
                                    <div class="container text-center">
                                        <div class="row align-items-start">
                                            <div class="col">

                                                <input type="text" class="form-control" id="data_inicial" name="data_inicial" placeholder="Data Inicial" maxlength="10" onkeypress="mascaraData(this, event)" <?php if (!empty($data_inicial)) echo "value='$data_inicial'"; ?>>
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" id="data_final" name="data_final" placeholder="Data Final" maxlength="10" onkeypress="mascaraData(this, event)" <?php if (!empty($data_final)) echo "value='$data_final'"; ?>>

                                            </div>

                                        </div>
                                    </div>
                                </label>

                            <?php } ?>
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
                            <button type="button" class="btn btn-danger">
                                Voltar <i class="bi bi-box-arrow-left"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <?php
                if (!empty($tipo)) {
                    if ($stmt && $stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch()) {
                            if ($tipo === "data_doacao") {
                ?>
                                <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                                    <h1><i class="bi bi-droplet"></i></h1>
                                    <h5 class="fw-normal"><?= $row['data_formatada'] ?></h5>
                                    <br><br>
                                    <p><a class="btn btn-outline-danger" href="info-doacao.php?id=<?= $id ?>&id_doacao=<?= $row['id_doacao'] ?>">DETALHAR &raquo;</a></p>
                                </div>
                            <?php
                            } else if ($tipo === "nome_doador") {
                            ?>
                                <div style="border: solid black 3px; height: 290px; width: 150px; border-radius: 10%; text-align: center; margin: 1em;">
                                    <h1><i class="bi bi-droplet"></i></h1>
                                    <h5 class="fw-normal"> Nome: <?= $row['nome'] ?></h5>
                                    <p><a class="btn btn-outline-danger" href="info-doacao.php?id=<?= $id ?>&id_doacao=<?= $row['id_doacao'] ?>">DETALHAR &raquo;</a></p>
                                </div>
                <?php
                            }
                        }
                    } else {
                        echo "<p>Nenhum resultado encontrado.</p>";
                    }
                }
                ?>
            </div>
        </div>
        <script>
            function campoData() {
                document.getElementById("pesquisa").outerHTML = '<label id="pesquisa"><div class="container text-center"><div class="row align-items-start"><div class="col"><input type="text" class="form-control" id="data_inicial" name="data_inicial" placeholder="Data Inicial" maxlength="10" onkeypress="mascaraData(this, event)"></div><div class="col"><input type="text" class="form-control" id="data_final" name="data_final" placeholder="Data Final" maxlength="10" onkeypress="mascaraData(this, event)"></div></div></div></label>';
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
