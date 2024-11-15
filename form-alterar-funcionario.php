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

                //MOSTRA E OCULTA A SENHA
                function mostra() {
                    const camposenha = document.getElementById('senha')
                    const verifsenha = document.getElementById('confsenha')
                    const icone = document.getElementById("olho")

                    if (camposenha.getAttribute('type') == 'password') {
                        icone.classList.remove("bi-eye")
                        icone.classList.add("bi-eye-slash")
                        camposenha.setAttribute('type', 'text')
                        verifsenha.setAttribute('type', 'text')

                    } else {
                        icone.classList.remove("bi-eye-slash")
                        icone.classList.add("bi-eye")
                        camposenha.setAttribute('type', 'password')
                        verifsenha.setAttribute('type', 'password')
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

                <form action="alterar.php?id=<?= $id ?>&id_funcionario=<?= $id_funcionario ?>&user=3" method="post" enctype="multipart/form-data">

                    <div class="col">
                        <h2>Dados do Funcionário</h2>
                        <br><br>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" class="form-control" id="nome" name="nome" value=" <?= $funcionario['nome'] ?>">

                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="idade" class="form-label">Idade</label>
                                            <input type="text" class="form-control" id="idade" name="idade" value=" <?= $funcionario['idade'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="sexo" class="form-label">Sexo</label>
                                            <input class="form-control" id="sexo" name="sexo" value=" <?= $funcionario['sexo'] ?>">

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" name="telefone" id="telefone" maxlength="15" value=" <?= $funcionario['telefone'] ?>" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="cep" class="form-label">Cep</label>
                                            <div class="input-group">
                                                <input type="text" maxlength="9" onkeyup="handleZipCode(event)" placeholder="Cep " class="form-control" name="cep" id="cep" value="<?= $funcionario['cep'] ?>" />
                                                <button type="button" id="mostrar" onclick="buscarCep()" class="btn btn-outline-success">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" col">
                                        <div class="mb-3">

                                            <label for="estado" class="form-label">Estado</label>
                                            <select name="estado" id="estado" class="form-control" value="<?= $row['estado'] ?>">
                                                <option value="nao informado">Selecionar</option>
                                                <option value="AC" <?= ($funcionario['estado'] == "AC")  ? " selected" : " "; ?>>AC</option>
                                                <option value="AL" <?= ($funcionario['estado'] == "AL")  ? " selected" : " "; ?>>AL</option>
                                                <option value="AP" <?= ($funcionario['estado'] == "AP")  ? " selected" : " "; ?>>AP</option>
                                                <option value="AM" <?= ($funcionario['estado'] == "AM")  ? " selected" : " "; ?>>AM</option>
                                                <option value="BA" <?= ($funcionario['estado'] == "BA")  ? " selected" : " "; ?>>BA</option>
                                                <option value="CE" <?= ($funcionario['estado'] == "CE")  ? " selected" : " "; ?>>CE</option>
                                                <option value="ES" <?= ($funcionario['estado'] == "ES")  ? " selected" : " "; ?>>ES</option>
                                                <option value="GO" <?= ($funcionario['estado'] == "GO")  ? " selected" : " "; ?>>GO</option>
                                                <option value="MA" <?= ($funcionario['estado'] == "MA")  ? " selected" : " "; ?>>MA</option>
                                                <option value="MT" <?= ($funcionario['estado'] == "MT")  ? " selected" : " "; ?>>MT</option>
                                                <option value="MS" <?= ($funcionario['estado'] == "MS")  ? " selected" : " "; ?>>MS</option>
                                                <option value="MG" <?= ($funcionario['estado'] == "MG")  ? " selected" : " "; ?>>MG</option>
                                                <option value="PA" <?= ($funcionario['estado'] == "PA")  ? " selected" : " "; ?>>PA</option>
                                                <option value="PB" <?= ($funcionario['estado'] == "PB")  ? " selected" : " "; ?>>PB</option>
                                                <option value="PR" <?= ($funcionario['estado'] == "PR")  ? " selected" : " "; ?>>PR</option>
                                                <option value="PE" <?= ($funcionario['estado'] == "PE")  ? " selected" : " "; ?>>PE</option>
                                                <option value="PI" <?= ($funcionario['estado'] == "PI")  ? " selected" : " "; ?>>PI</option>
                                                <option value="RJ" <?= ($funcionario['estado'] == "RJ")  ? " selected" : " "; ?>>RJ</option>
                                                <option value="RN" <?= ($funcionario['estado'] == "RN")  ? " selected" : " "; ?>>RN</option>
                                                <option value="RD" <?= ($funcionario['estado'] == "RS")  ? " selected" : " "; ?>>RS</option>
                                                <option value="RO" <?= ($funcionario['estado'] == "RO")  ? " selected" : " "; ?>>RO</option>
                                                <option value="RR" <?= ($funcionario['estado'] == "RR")  ? " selected" : " "; ?>>RR</option>
                                                <option value="SC" <?= ($funcionario['estado'] == "SC")  ? " selected" : " "; ?>>SC</option>
                                                <option value="SP" <?= ($funcionario['estado'] == "SP")  ? " selected" : " "; ?>>SP</option>
                                                <option value="SE" <?= ($funcionario['estado'] == "SE")  ? " selected" : " "; ?>>SE</option>
                                                <option value="TO" <?= ($funcionario['estado'] == "TO")  ? " selected" : " "; ?>>TO</option>
                                                <option value="DF" <?= ($funcionario['estado'] == "DF")  ? " selected" : " "; ?>>DF</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="cidade" class="form-label">Cidade</label>
                                            <input type="text" name="cidade" id="cidade" value=" <?= $funcionario['cidade'] ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="bairro" class="form-label">Bairro</label>
                                            <input type="text" name="bairro" id="bairro" value=" <?= $funcionario['bairro'] ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <div class="mb-3">
                                            <label for="rua" class="form-label">Logradouro</label>
                                            <input type="text" name="rua" id="rua" value=" <?= $funcionario['rua'] ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="mb-3">
                                            <label for="num_residencia" class="form-label">Número da Residência</label>
                                            <input type="text" name="num_residencia" id="num_residencia" value=" <?= $funcionario['num_residencia'] ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="text" name="email" id="email" value=" <?= $funcionario['usuario'] ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="logi" id="logi" class="form-control" value="<?= $funcionario['usuario'] ?>">

                    <button type="submit" class="btn btn-primary">Gravar</button>
                    <button type="reset" class="btn btn-warning">Apagar</button>
                    <span style="padding-left: 50em">
                        <a href="info-funcionario.php?id=<?= $id ?>&id_funcionario=<?= $id_funcionario ?>" class="link-danger">
                            <button type="button" class="btn btn-danger ">
                                Voltar <i class="bi bi-box-arrow-left"></i>
                            </button>
                        </a>
                    </span>
                </form>



    <?php
        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}
require "footer.php";
