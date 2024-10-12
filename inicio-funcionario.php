<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id');
    $mod = filter_input(INPUT_GET, 'mod');
    $data = filter_input(INPUT_GET, 'data');


    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario FROM funcionario WHERE id_funcionario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $funcionario) {
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


                <a class="btn btn-warning" href="login-funcionario.php?id=<?= $id ?>">

                    Minhas Informações
                </a>

                <a class="btn btn-danger" href="ver-doadores.php?id=<?= $id ?>">

                    Ver Doadores
                </a>

                <a class="btn btn-primary" href="ver-doacoes.php?id=<?= $id ?>">

                    Ver Doações
                </a>



                <span style="padding-left: 50em">
                    <a href="sair.php" class="link-danger">
                        <button type="button" class="btn btn-danger ">
                            Sair <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </a>
                </span>
                <br><br><br><br><br>

                <div style="width: 100%; text-align:center;">
                    <div class="col">
                        <i class="bi bi-person" style="font-size: 7em;"></i>
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="var(--bs-secondary-color)" /></svg>
                        <h2 class="fw-normal">Funcionário:</h2>
                        <h3><?= $funcionario['nome'] ?></h3>


                    </div>
                </div>
                <?php
                if ($mod == 1) {
                ?>
                    <script>
                        alert("Cadastro de doação realizado com sucesso!")
                    </script>
                <?php
                } else if ($mod == 2) {
                ?>
                    <script>
                        alert("Erro ao realizar cadastro de doação")
                    </script>
                <?php
                } else if ($mod == 3) {

                ?>
                    <script>
                        alert("O doador só pode realizar doações a partir do dia <?= $data ?>")
                    </script>
    <?php
                }
            }
        } else {
            redireciona();
        }
    } else {
        redireciona();
    }
    require "footer.php";
