<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_doador = filter_input(INPUT_GET, 'id_doador', FILTER_SANITIZE_SPECIAL_CHARS);
    echo 'oioioioioi';
    if ($_SESSION["idDoador"] == $id) {
        $sql3 = "SELECT id_banco FROM funcionario WHERE id_funcionario = ?";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute([$id]);
        $row3 = $stmt3->fetch();


        $sql = "SELECT nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario, foto FROM doador WHERE idDoador = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_doador]);

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql2 = "SELECT *  FROM banco WHERE id_banco = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$row3['id_banco']]);
        $row2 = $stmt2->fetch();
        foreach ($row as $doador) {

?>

            <style>
                .img {
                    border-radius: 15%;
                    border: solid 2px black;
                }
            </style>
            <main style="padding: 3em; margin: 1em;">


                <div class="row">
                    <!--Div numero 1-->
                    <div class="col-8">
                        <h2>Dados do Doador</h2>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" disabled value=" <?= $doador['nome'] ?>">

                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="idade" class="form-label">Idade</label>
                                    <input type="text" class="form-control" id="idade" name="idade" value=" <?= $doador['idade'] ?>" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="sexo" class="form-label">Sexo</label>
                                    <input class="form-control" id="sexo" name="sexo" value=" <?= $doador['sexo'] ?>" disabled>

                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">

                                    <label for="tipo" class="form-label">Tipo Sanguíneo</label>
                                    <input class="form-control" id="tipo" name="tipo" value=" <?= $doador['tipo'] ?>" disabled>

                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" name="telefone" id="telefone" value=" <?= $doador['telefone'] ?>" class="form-control" disabled>

                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">Cep</label>
                                    <input type="text" class="form-control" name="cep" id="cep" value=" <?= $doador['cep'] ?>" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">

                                    <label for="estado" class="form-label">Estado</label>
                                    <input name="estado" id="estado" class="form-control" value=" <?= $doador['estado'] ?>" disabled>

                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" value=" <?= $doador['cidade'] ?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" value=" <?= $doador['bairro'] ?>" class="form-control" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="rua" class="form-label">Logradouro</label>
                                    <input type="text" name="rua" id="rua" value=" <?= $doador['rua'] ?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="num_residencia" class="form-label">Número da Residência</label>
                                    <input type="text" name="num_residencia" id="num_residencia" value=" <?= $doador['num_residencia'] ?>" class="form-control" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" name="email" id="email" value=" <?= $doador['usuario'] ?>" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--Div numero 2-->
                    <div class="col-3" style="margin: 3em;">
                        <br><br><br><br>
                        <img src="mostrar_foto_funcionario.php?id=<?= $id ?>&id_doador=<?= $id_doador ?>" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto img" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false">

                    </div>
                </div>
            <?php
        }
            ?>
            <div class="row ">
                <div class="col-8">
                    <div class="col-8">
                        <?php
                        if ($row2['statusb'] == "APROVADO") {
                        ?>
                            <a class="btn btn-primary " href="formulario-cadastrar-doacao.php?id=<?= $id ?>&id_doacao=<?= $id_doador ?>">
                                <span data-feather="cadast"></span>
                                Cadastrar Doação
                            </a>

                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <a href="ver-doadores.php?id=<?= $id ?>" class="link-danger">
                        <button type="button" class="btn btn-danger ">
                            Voltar <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </a>
                </div>
            </div>
    <?php
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
    ?>