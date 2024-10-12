<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_doador = filter_input(INPUT_GET, 'id_doacao', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario FROM doador WHERE idDoador = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_doador]);

        $row = $stmt->fetch();
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

        <script>
            /*MASCARA DE CEP*/

            const handleZipCode = (event) => {
                let input = event.target
                input.value = zipCodeMask(input.value)
            }

            const zipCodeMask = (value) => {
                if (!value) return ""
                value = value.replace(/\D/g, '')
                value = value.replace(/(\d{5})(\d)/, '$1-$2')
                return value
            }



            /*MASCARA DE TELEFONE*/
            function mascara(o, f) {
                v_obj = o
                v_fun = f
                setTimeout("execmascara()", 1)
            }

            function execmascara() {
                v_obj.value = v_fun(v_obj.value)
            }

            function mtel(v) {
                v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
                v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
                v = v.replace(/(\d)(\d{4})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
                return v;
            }

            function id(el) {
                return document.getElementById(el);
            }
            window.onload = function() {
                id('telefone').onkeyup = function() {
                    mascara(this, mtel);
                }
            }
            /*VERFICA SE AS SENHAS DO FORMULARIO ESTÃO IGUAIS*/
            function verifica_senhas() {
                var senha = document.getElementById("senha");
                var confsenha = document.getElementById("confsenha");

                if (senha.value && confsenha.value) {
                    if (senha.value != confsenha.value) {
                        senha.classList.add("is-invalid");
                        confsenha.classList.add("is-invalid");
                        confsenha.value = null;
                    } else {
                        senha.classList.remove("is-invalid");
                        confsenha.classList.remove("is-invalid");
                    }
                }
            }
        </script>

        <main>
            <br><br><br>

            <form action="inserir-doacao.php?id=<?= $id ?>&id_doador=<?= $id_doador ?>" method="post">
                <div class="row">
                    <div class="col-8">
                        <h2>Preencha o formulário, corretamente</h2>
                        <br><br>

                        <div class="row">

                            <div class="col">
                                <div class="mb-3">

                                    <label for="tipo" class="form-label">Tipo Sanguíneo do Doador</label>
                                    <select class="form-control" id="tipo" name="tipo">
                                        <option value="nao informado">Não sei</option>
                                        <option value="A+" <?= ($row['tipo'] == "A+")  ? " selected" : " "; ?>>A+</option>
                                        <option value="A-" <?= ($row['tipo'] == "A-")  ? " selected" : " "; ?>>A-</option>
                                        <option value="B+" <?= ($row['tipo'] == "B+")  ? " selected" : " "; ?>>B+</option>
                                        <option value="B-" <?= ($row['tipo'] == "B-")  ? " selected" : " "; ?>>B-</option>
                                        <option value="AB+" <?= ($row['tipo'] == "AB+") ? " selected" : " "; ?>>AB+</option>
                                        <option value="AB-" <?= ($row['tipo'] == "AB-") ? " selected" : " "; ?>>AB-</option>
                                        <option value="O+" <?= ($row['tipo'] == "O+")  ? " selected" : " "; ?>>O+</option>
                                        <option value="O-" <?= ($row['tipo'] == "O-")  ? " selected" : " "; ?>>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="exames" class="form-label">Exames</label>

                                    <textarea type="text" class="form-control" id="exames" name="exames" placeholder="Escreva os resultados dos exames aqui" required></textarea>
                                </div>
                            </div>
                        </div>


                        <br><br>

                        <div class="bot">
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                            <button type="reset" class="btn btn-warning">Apagar</button>
                            <a href="login-funcionario.php?id=<?= $id ?>" class="btn btn-danger">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
                </div>

            </form>



            </div>
            <br><br>

    <?php
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
    ?>