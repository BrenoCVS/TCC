<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id');
    $id_funcionario = filter_input(INPUT_GET, 'id_funcionario');
    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario FROM funcionario WHERE id_funcionario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_funcionario]);

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

                <form action="inserir-funcionario.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">

                    <div class="col">
                        <h2>Dados do Funcionário</h2>
                        <br><br>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" class="form-control" id="nome" name="nome" disabled value=" <?= $funcionario['nome'] ?>">

                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="idade" class="form-label">Idade</label>
                                            <input type="text" class="form-control" id="idade" name="idade" value=" <?= $funcionario['idade'] ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="sexo" class="form-label">Sexo</label>
                                            <input class="form-control" id="sexo" name="sexo" value=" <?= $funcionario['sexo'] ?>" disabled>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" name="telefone" id="telefone" value=" <?= $funcionario['telefone'] ?>" class="form-control" disabled>

                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="cep" class="form-label">Cep</label>
                                            <input type="text" class="form-control" name="cep" id="cep" value=" <?= $funcionario['cep'] ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">

                                            <label for="estado" class="form-label">Estado</label>
                                            <input name="estado" id="estado" class="form-control" value=" <?= $funcionario['estado'] ?>" disabled>

                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="cidade" class="form-label">Cidade</label>
                                            <input type="text" name="cidade" id="cidade" value=" <?= $funcionario['cidade'] ?>" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="bairro" class="form-label">Bairro</label>
                                            <input type="text" name="bairro" id="bairro" value=" <?= $funcionario['bairro'] ?>" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <div class="mb-3">
                                            <label for="rua" class="form-label">Logradouro</label>
                                            <input type="text" name="rua" id="rua" value=" <?= $funcionario['rua'] ?>" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="mb-3">
                                            <label for="num_residencia" class="form-label">Número da Residência</label>
                                            <input type="text" name="num_residencia" id="num_residencia" value=" <?= $funcionario['num_residencia'] ?>" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="text" name="email" id="email" value=" <?= $funcionario['usuario'] ?>" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>


                <a class="btn btn-warning " href="form-alterar-funcionario.php?id=<?= $id ?>&id_funcionario=<?= $id_funcionario ?>">
                    <span data-feather="edit"></span>
                    Editar Informações
                </a>

                <a class="btn btn-danger" href="excluir-funcionario.php?id=<?= $id ?>&id_funcionario=<?= $id_funcionario ?>" onclick="if(!confirm('Tem certeza que deseja excluir este funrionário?')) return false;">
                    <span data-feather="trash-2"></span>
                    Excluir Perfil dO Funcionário
                </a>

                <span style="padding-left: 50em">
                    <a href="ver-funcionarios.php?id=<?= $id ?>" class="link-danger">
                        <button type="button" class="btn btn-danger ">
                            Voltar <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </a>
                </span>

    <?php
        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
