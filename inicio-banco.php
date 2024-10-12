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



                <!--Div numero 2-->
                </div>
            <?php
        }
            ?>
            <div class="row ">
                <div class="col-8">
                    <div class="col-12">

                        <a class="btn btn-warning " href="login-banco.php?id=<?= $id ?>">
                            <span data-feather="edit"></span>
                            Ver Informações
                        </a>

                        <?php
                        if ($doador['statusb'] == "APROVADO") {
                        ?>
                            <a class="btn btn-primary " href="formulario-cadastrar-funcionario.php?id=<?= $id ?>">
                                <span data-feather="cadast"></span>
                                Cadastrar Funcionario
                            </a>

                            <a class="btn btn-success " href="ver-funcionarios.php?id=<?= $id ?>">
                                <span data-feather="cadast"></span>
                                Ver Funcionarios
                            </a>
                        <?php
                        }
                        ?>



                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center">
                    <a href="sair.php" class="link-danger">
                        <button type="button" class="btn btn-danger ">
                            Sair <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </a>
                </div>
            </div>
            <br><br><br><br>
            <div style="width: 100%; text-align:center;">
                <div class="col">
                    <i class="bi bi-bank" style="font-size: 7em;"></i>
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="var(--bs-secondary-color)" /></svg>
                    <h2 class="fw-normal">Banco:</h2>
                    <h3><?= $doador['nome'] ?></h3>


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