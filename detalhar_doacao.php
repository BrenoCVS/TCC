<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
    $idd = filter_input(INPUT_GET, "idd", FILTER_SANITIZE_SPECIAL_CHARS);

    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT d.id_doacao, d.id_banco, d.id_doador, d.tipo_sanguineo, DATE_FORMAT(d.data_doacao, '%d/%m/%Y') AS data_formatada, b.nome, f.nome AS nome_funcionario
        FROM doacao d
        INNER JOIN banco b ON d.id_banco = b.id_banco
        INNER JOIN funcionario f ON d.id_funcionario = f.id_funcionario
        WHERE id_doador = ?
        ORDER BY data_formatada;";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

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


            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <ul>
                            <li>id da doação: <?= $row['id_doacao'] ?></li>
                            <li>data da doação: <?= $row['data_formatada'] ?></li>
                            <li>nome do banco: <?= $row['nome'] ?></li>
                            <li>nome do funcionario: <?= $row['nome_funcionario'] ?></li>
                            <li>tipo sanguineo: <?= $row['tipo_sanguineo'] ?></li>
                        </ul>
                    </div>
                    <div class="col-8">
                        <h5 class="align-center"><strong>Seja o Herói de Alguém: Doe Sangue</strong></h5>
                        <p>Você já pensou em ser um verdadeiro herói na vida de alguém? Bem, aqui está sua chance. Sua doação de sangue pode salvar vidas - e isso não é uma exagero.</p>

                        <p>Imagine o impacto positivo que você pode ter ao doar sangue. Você está fornecendo esperança, força e uma segunda chance para alguém que está lutando por sua vida. Cada doação é uma dádiva preciosa que pode fazer toda a diferença.</p>

                        <p>Não importa quem você é ou de onde você vem, todos nós temos o poder de fazer a diferença. Doar sangue é uma maneira simples, rápida e eficaz de contribuir para a saúde e o bem-estar da nossa comunidade.</p>

                        <p>Além disso, o ato de doar sangue não apenas beneficia o receptor, mas também traz benefícios para você. Estudos mostram que doar sangue pode reduzir o risco de certas doenças cardíacas e até mesmo estimular a produção de novas células sanguíneas em seu próprio corpo.</p>

                        <p>Então, o que você está esperando? Junte-se a nós e torne-se um herói de verdade. Sua doação de sangue pode salvar uma vida hoje e inspirar outros a fazerem o mesmo. Faça sua parte e ajude-nos a construir um mundo mais saudável e solidário.</p>

                        <p>Doe sangue. Doe vida.</p>
                    </div>
                    <div class="col-9">
                        <br><br>
                        <a href=" doacoes.php?id=<?= $id ?>"> <button class="btn btn-primary">Voltar</button></a>
                    </div>
                </div>
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