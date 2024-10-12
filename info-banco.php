<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header-adm.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id-adm', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_banco = filter_input(INPUT_GET, 'id-banco', FILTER_SANITIZE_SPECIAL_CHARS);


    if ($_SESSION["idDoador"] == $id) {
        //$sql = "SELECT * FROM BANCO WHERE statusb = {$tipo}";
        $sql = "SELECT * FROM BANCO WHERE id_banco = '{$id_banco}' ";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch();

?>

        <main>
            <br><br><br>
            <div style="margin: 30px;">
                <form action="inserir-banco.php" method="post">
                    <div class="row">
                        <div class="col-8">
                            <h2>Dados do Banco</h2>
                            <br><br>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome do Banco</label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?= $row['nome'] ?>" disabled>
                                    </div>
                                </div>
                            </div>






                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input type="text" name="telefone" id="telefone" placeholder="Telefone " maxlength="15" class="form-control" value="<?= $row['telefone'] ?>" disabled>

                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="cep" class="form-label">Cep</label>
                                        <input type="text" maxlength="9" onkeyup="handleZipCode(event)" placeholder="Cep " class="form-control" name="cep" id="cep" value="<?= $row['cep'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">

                                        <label for="estado" class="form-label">Estado</label>
                                        <input type="text" class="form-control" value="<?= $row['estado'] ?>" disabled>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" placeholder="Cidade " class="form-control" value="<?= $row['cidade'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" placeholder="Bairro " class="form-control" value="<?= $row['bairro'] ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="rua" class="form-label">Logradouro</label>
                                        <input type="text" name="rua" id="rua" placeholder="Logradouro " class="form-control" value="<?= $row['rua'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="num_residencia" class="form-label">Número do Edifício</label>
                                        <input type="text" name="num_residencia" id="num_residencia" placeholder="Número da residência " class="form-control" value="<?= $row['num_residencia'] ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" name="email" id="email" placeholder="Insira seu e-mail" class="form-control" value="<?= $row['usuario'] ?>" disabled>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Status do Banco</label>
                                        <input type="text" class="form-control" value="<?= $row['statusb'] ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">


                                <br><br>

                                <div class="bot">
                                    <a href="login-adm.php?id=<?= $id ?>" class="btn btn-primary">
                                        Voltar
                                    </a>
                                    <?php
                                    if (($row['statusb'] == "PENDENTE")) {
                                    ?>
                                        <a href="relatorio-banco.php?id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-danger">Recusar</a>
                                        <a href="alterar-banco.php?mod=2&id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-success">Aprovar</a>
                                    <?php
                                    }
                                    if (($row['statusb'] == "RECUSADO")) {
                                    ?>
                                        <a href="alterar-banco.php?mod=3&id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-warning"> Deixar em Espera</a>
                                        <a href="alterar-banco.php?mod=2&id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-success">Aprovar</a>

                                    <?php
                                    }
                                    if (($row['statusb'] == "APROVADO")) {

                                    ?>
                                        <a href="relatorio-banco.php?id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-danger">Recusar</a>
                                        <a href="alterar-banco.php?mod=3&id-banco=<?= $row['id_banco'] ?>&id-adm=<?= $id ?>" class="btn btn-warning"> Deixar em Espera</a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>



            </div>
            <br><br>
            </div>
    <?php
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
