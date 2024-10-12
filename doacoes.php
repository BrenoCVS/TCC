<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada, b.nome
        FROM doacao d
        INNER JOIN banco b ON d.id_banco = b.id_banco
        WHERE id_doador = ?
        ORDER BY data_formatada;";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);



        // $row = $stmt->fetch();
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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID Doação</th>
                        <th scope="col">Data da Doação</th>
                        <th scope="col">Nome do Banco</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while ($row = $stmt->fetch()) {
                    ?>
                        <tr>
                            <th scope="row"><?= $row['id_doacao'] ?></th>
                            <td><?= $row['data_formatada'] ?></td>
                            <td><?= $row['nome'] ?></td>
                            <td>
                                <a class="btn btn-primary " href="info-doacao.php?id=<?= $id ?>&id_doacao=<?= $row['id_doacao'] ?>&tipo=doador">
                                    <span data-feather="details"></span>
                                    Detalhar <i class="bi bi-arrow-down-circle"></i>
                                </a>
                            </td>
                        </tr>


                    <?php

                        $ultimaDoacao = $row['data_formatada'];
                    }
                    ?>
                </tbody>
            </table>
            <?php

            //$dataFinal = $ultimaDoacao->add(new DateInterval('P9M'))->format('d/m/Y');

            ?>



            <a href="login_sucesso.php?id=<?= $id ?>"><button class="btn btn-primary">Voltar</button></a>
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