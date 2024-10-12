<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $modi = filter_input(INPUT_GET, 'modi', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($_SESSION["idDoador"] == $id) {

        if ($modi == 1) {
?>
            <script>
                alert("Os dados foram modificados com sucesso! Aguarde a vereficação!")
            </script>
        <?php
        } else if ($modi == 2) {
        ?>
            <script>
                1
                alert("Erro ao modificar os dados! Tente novamente!")
            </script>
        <?php
        }
        $sql = "SELECT * FROM banco WHERE id_banco = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <div class="col">
                        <h2>Dados do Banco</h2>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Banco</label>
                            <input type="text" class="form-control" id="nome" name="nome" disabled value=" <?= $doador['nome'] ?>">

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
                            <?php
                            if ($doador['statusb']  == "RECUSADO") {
                            ?>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" name="email" id="email" value=" <?= $doador['usuario'] ?>" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status do Banco</label>
                                        <input type="text" name="status" id="status" value=" <?= $doador['statusb'] ?>" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="relatorio" class="form-label">Motivo do Banco ser Recusado:</label>
                                        <textarea type="text" name="relatorio" id="relatorio" class="form-control" disabled><?= $doador['relatorio_adm'] ?></textarea>
                                    </div>
                                </div>

                            <?php

                            } else {
                            ?>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" name="email" id="email" value=" <?= $doador['usuario'] ?>" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status do Banco</label>
                                        <input type="text" name="status" id="status" value=" <?= $doador['statusb'] ?>" class="form-control" disabled>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>


                    <!--Div numero 2-->
                </div>
            <?php
        }
            ?>
            <div class="row ">
                <div class="col-8">
                    <div class="col-12">

                        <a class="btn btn-warning " href="form-alterar-banco.php?id=<?= $id ?>">
                            <span data-feather="edit"></span>
                            Editar Informações
                        </a>

                        <a class="btn btn-danger" href="excluir-banco.php?id=<?= $id ?>" onclick="if(!confirm('Tem certeza que deseja excluir seus dados como doador?')) return false;">
                            <span data-feather="trash-2"></span>
                            Excluir Perfil de Banco
                        </a>

                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <a href="inicio-banco.php?id=<?= $id ?>" class="link-danger">
                        <button type="button" class="btn btn-danger ">
                            Voltar <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </a>
                </div>
            </div>

            <?php
            if ($doador['statusb']  == " RECUSADO") {
            ?>
                < <?php
                }
            } else {
                redireciona();
            }
        } else {
            redireciona();
        }
        require "footer.php";
                    ?>