<?php
session_start();
require "logica-autenticacao.php";
require "conexao.php";
if (autenticado()) {


    $header = 1;
    require "header2.php";
    $id = filter_input(INPUT_GET, 'id');
    $id_doacao = filter_input(INPUT_GET, 'id_doacao');
    $tipo = filter_input(INPUT_GET, 'tipo');

    //select doacao
    $sql = "SELECT id_doacao, id_banco, id_doador, id_funcionario ,DATE_FORMAT(data_doacao, '%d/%m/%Y') AS data_formatada, exames, tipo_sanguineo
    FROM doacao 
    WHERE id_doacao = '{$id_doacao}' ";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch();

    //select funcionario
    $sqlfunc = "SELECT  nome
    FROM funcionario 
    WHERE id_funcionario = '{$row['id_funcionario']}' ";
    $stmtfunc = $conn->query($sqlfunc);
    $rowfunc = $stmtfunc->fetch();

    //select doador
    $sqldoador = "SELECT nome
    FROM doador 
    WHERE idDoador = '{$row['id_doador']}' ";
    $stmtdoador = $conn->query($sqldoador);
    $rowdoador = $stmtdoador->fetch();

    //select banco
    $sqlbanco = "SELECT nome
    FROM banco
    WHERE id_banco = '{$row['id_banco']}' ";
    $stmtbanco = $conn->query($sqlbanco);
    $rowbanco = $stmtbanco->fetch();

?>

    <style>
        body {

            height: 100%;
            padding-left: 3em;
        }

        .p1 {

            flex: end;
        }

        .bot {

            text-align: center;
        }
    </style>



    <main>
        <br><br><br>

        <form action="inserir-funcionario.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-8">
                    <h2>Dados da Doação:</h2>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="nomed" class="form-label">Nome do Doador: </label>
                                <input type="text" class="form-control" id="nomed" name="nomed" disabled value="<?= $rowdoador['nome'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nomeb" class="form-label">Nome do Banco de Sangue:</label>
                                    <input type="text" class="form-control" id="nomeb" name="nomeb" disabled value="<?= $rowbanco['nome'] ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nomef" class="form-label">Nome do funcionario:</label>
                                    <input type="text" class="form-control" id="nomef" name="nomef" disabled value="<?= $rowfunc['nome'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="data" class="form-label">Data da Doação:</label>
                                    <input type="text" class="form-control" id="data" name="data" disabled value="<?= $row['data_formatada'] ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">

                                    <label for="tipo" class="form-label">Tipo Sanguíneo</label>
                                    <select class="form-control" id="tipo" name="tipo" disabled>
                                        <option value="nao informado">Não sei</option>
                                        <option value="A+" <?= ($row['tipo_sanguineo'] == "A+")  ? " selected" : " "; ?>>A+</option>
                                        <option value="A-" <?= ($row['tipo_sanguineo'] == "A-")  ? " selected" : " "; ?>>A-</option>
                                        <option value="B+" <?= ($row['tipo_sanguineo'] == "B+")  ? " selected" : " "; ?>>B+</option>
                                        <option value="B-" <?= ($row['tipo_sanguineo'] == "B-")  ? " selected" : " "; ?>>B-</option>
                                        <option value="AB+" <?= ($row['tipo_sanguineo'] == "AB+") ? " selected" : " "; ?>>AB+</option>
                                        <option value="AB-" <?= ($row['tipo_sanguineo'] == "AB-") ? " selected" : " "; ?>>AB-</option>
                                        <option value="O+" <?= ($row['tipo_sanguineo'] == "O+")  ? " selected" : " "; ?>>O+</option>
                                        <option value="O-" <?= ($row['tipo_sanguineo'] == "O-")  ? " selected" : " "; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exame" class="form-label">Exames:</label>
                                    <textarea type="text" class="form-control" id="exame" name="exame" disabled><?= $row['exames'] ?></textarea>
                                </div>
                            </div>
                        </div>







                        <div class="mb-3">


                            <br><br>
                            <?php
                            if ($tipo == "doador") {
                            ?>
                                <div class="bot">
                                    <a href="doacoes.php?id=<?= $id ?>" class="btn btn-danger">
                                        Voltar
                                    </a>

                                </div>
                            <?php
                            } else {

                            ?>
                                <div class="bot">
                                    <a href="ver-doacoes.php?id=<?= $id ?>" class="btn btn-danger">
                                        Voltar
                                    </a>

                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

        </form>



        </div>
        <br><br>

    <?php
    require "footer.php";
} else {
    redireciona();
}
