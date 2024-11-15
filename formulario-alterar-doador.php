<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {
    require "header2.php";
    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario FROM doador WHERE idDoador = ?";
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

            //API de localização

            const buscarCep = async () => {
                const cep = document.getElementById('cep').value;
                const url = `https://brasilapi.com.br/api/cep/v1/${cep}`; // Usando crase para interpolação
                try {
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }


                    const data = await response.json();

                    //Preenche o campo de cidade
                    document.getElementById('cidade').value = data.city;

                    //Seleciona o estado
                    const estadoRecebido = data.state;
                    const selectElement = document.getElementById('estado');
                    selectElement.value = estadoRecebido;

                    try {
                        //Preenche o Bairro, caso a api nos de o nome da rua
                        document.getElementById('bairro').value = data.neighborhood;

                        //Preenche a rua, caso a api nos de o nome da rua
                        document.getElementById('rua').value = data.street;

                    } catch (error) {
                        console.error('Fetch error:', error);

                    }

                    console.log(data);
                } catch (error) {
                    console.error('Fetch error:', error);
                }
            };
        </script>

        <main>
            <br><br><br>

            <form action="alterar.php?id=<?= $id ?>&user=1" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-8">
                        <h2>Preencha com os dados do Doador</h2>
                        <br><br>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome " value="<?= $row['nome'] ?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="idade" class="form-label">Idade</label>
                                    <input type="number" class="form-control" id="idade" name="idade" placeholder="Idade " value="<?= $row['idade'] ?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="sexo" class="form-label">Sexo </label>
                                    <select class="form-control" id="sexo" name="sexo" value="<?= $row['sexo'] ?>">
                                        <option value="NAO INFORMADO" <?= ($row['sexo'] == "NAO INFORMADO")  ? " selected" : " "; ?>>Selecionar</option>
                                        <option value="MASCULINO" <?= ($row['sexo'] == "MASCULINO")  ? " selected" : " "; ?>>Masculino</option>
                                        <option value="FEMININO" <?= ($row['sexo'] == "FEMININO")  ? " selected" : " "; ?>>Feminino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">

                                    <label for="tipo" class="form-label">Tipo Sanguíneo</label>
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
                        </div>




                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" name="telefone" id="telefone" placeholder="Telefone " maxlength="15" class="form-control" value="<?= $row['telefone'] ?>">

                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">Cep</label>
                                    <div class="input-group">
                                        <input type="text" maxlength="9" onkeyup="handleZipCode(event)" placeholder="Cep " class="form-control" name="cep" id="cep" value="<?= $row['cep'] ?>" />
                                        <button type="button" id="mostrar" onclick="buscarCep()" class="btn btn-outline-success">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">

                                    <label for="estado" class="form-label">Estado</label>
                                    <select name="estado" id="estado" class="form-control" value="<?= $row['estado'] ?>">
                                        <option value="nao informado">Selecionar</option>
                                        <option value="AC" <?= ($row['estado'] == "AC")  ? " selected" : " "; ?>>AC</option>
                                        <option value="AL" <?= ($row['estado'] == "AL")  ? " selected" : " "; ?>>AL</option>
                                        <option value="AP" <?= ($row['estado'] == "AP")  ? " selected" : " "; ?>>AP</option>
                                        <option value="AM" <?= ($row['estado'] == "AM")  ? " selected" : " "; ?>>AM</option>
                                        <option value="BA" <?= ($row['estado'] == "BA")  ? " selected" : " "; ?>>BA</option>
                                        <option value="CE" <?= ($row['estado'] == "CE")  ? " selected" : " "; ?>>CE</option>
                                        <option value="ES" <?= ($row['estado'] == "ES")  ? " selected" : " "; ?>>ES</option>
                                        <option value="GO" <?= ($row['estado'] == "GO")  ? " selected" : " "; ?>>GO</option>
                                        <option value="MA" <?= ($row['estado'] == "MA")  ? " selected" : " "; ?>>MA</option>
                                        <option value="MT" <?= ($row['estado'] == "MT")  ? " selected" : " "; ?>>MT</option>
                                        <option value="MS" <?= ($row['estado'] == "MS")  ? " selected" : " "; ?>>MS</option>
                                        <option value="MG" <?= ($row['estado'] == "MG")  ? " selected" : " "; ?>>MG</option>
                                        <option value="PA" <?= ($row['estado'] == "PA")  ? " selected" : " "; ?>>PA</option>
                                        <option value="PB" <?= ($row['estado'] == "PB")  ? " selected" : " "; ?>>PB</option>
                                        <option value="PR" <?= ($row['estado'] == "PR")  ? " selected" : " "; ?>>PR</option>
                                        <option value="PE" <?= ($row['estado'] == "PE")  ? " selected" : " "; ?>>PE</option>
                                        <option value="PI" <?= ($row['estado'] == "PI")  ? " selected" : " "; ?>>PI</option>
                                        <option value="RJ" <?= ($row['estado'] == "RJ")  ? " selected" : " "; ?>>RJ</option>
                                        <option value="RN" <?= ($row['estado'] == "RN")  ? " selected" : " "; ?>>RN</option>
                                        <option value="RD" <?= ($row['estado'] == "RS")  ? " selected" : " "; ?>>RS</option>
                                        <option value="RO" <?= ($row['estado'] == "RO")  ? " selected" : " "; ?>>RO</option>
                                        <option value="RR" <?= ($row['estado'] == "RR")  ? " selected" : " "; ?>>RR</option>
                                        <option value="SC" <?= ($row['estado'] == "SC")  ? " selected" : " "; ?>>SC</option>
                                        <option value="SP" <?= ($row['estado'] == "SP")  ? " selected" : " "; ?>>SP</option>
                                        <option value="SE" <?= ($row['estado'] == "SE")  ? " selected" : " "; ?>>SE</option>
                                        <option value="TO" <?= ($row['estado'] == "TO")  ? " selected" : " "; ?>>TO</option>
                                        <option value="DF" <?= ($row['estado'] == "DF")  ? " selected" : " "; ?>>DF</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" placeholder="Cidade " class="form-control" value="<?= $row['cidade'] ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" placeholder="Bairro " class="form-control" value="<?= $row['bairro'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="rua" class="form-label">Logradouro</label>
                                    <input type="text" name="rua" id="rua" placeholder="Logradouro " class="form-control" value="<?= $row['rua'] ?>">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="num_residencia" class="form-label">Número da Residência</label>
                                    <input type="text" name="num_residencia" id="num_residencia" placeholder="Número da residência " class="form-control" value="<?= $row['num_residencia'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" name="email" id="email" placeholder="Insira seu e-mail" class="form-control" value="<?= $row['usuario'] ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="logi" id="logi" class="form-control" value="<?= $row['usuario'] ?>">

                        <div class="mb-3">


                            <br><br>

                            <div class="bot">
                                <button type="submit" class="btn btn-primary">Gravar</button>
                                <button type="reset" class="btn btn-warning">Apagar</button>
                                <a href="login_sucesso.php?id=<?= $id ?>" class="btn btn-danger">
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