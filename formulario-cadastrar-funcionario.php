<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {


    $header = 1;
    require "header2.php";
    $id = filter_input(INPUT_GET, 'id');
    if ($_SESSION['idDoador'] == $id) {
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

            <form action="inserir-funcionario.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-8">
                        <h2>Preencha com os dados do Funcionário</h2>
                        <br><br>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome </label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="idade" class="form-label">Idade</label>
                                        <input type="number" class="form-control" id="idade" name="idade" placeholder="Idade " required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="sexo" class="form-label">Sexo</label>
                                        <select class="form-control" id="sexo" name="sexo">
                                            <option value="NAO INFORMADO">Selecionar</option>
                                            <option value="MASCULINO">Masculino</option>
                                            <option value="FEMININO">Feminino</option>
                                        </select>
                                    </div>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input type="text" name="telefone" id="telefone" placeholder="Telefone " maxlength="15" class="form-control" required>

                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="cep" class="form-label">Cep</label>
                                        <div class="input-group">
                                            <input type="text" maxlength="9" onkeyup="handleZipCode(event)" placeholder="Cep " class="form-control" name="cep" id="cep" required />
                                            <button type="button" id="mostrar" onclick="buscarCep()" class="btn btn-outline-success">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">

                                        <label for="estado" class="form-label">Estado</label>
                                        <select name="estado" id="estado" class="form-control">
                                            <option value="NAO INFORMADO">Selecionar</option>
                                            <option value="AC">AC</option>
                                            <option value="AL">AL</option>
                                            <option value="AP">AP</option>
                                            <option value="AM">AM</option>
                                            <option value="BA">BA</option>
                                            <option value="CE">CE</option>
                                            <option value="ES">ES</option>
                                            <option value="GO">GO</option>
                                            <option value="MA">MA</option>
                                            <option value="MT">MT</option>
                                            <option value="MS">MS</option>
                                            <option value="MG">MG</option>
                                            <option value="PA">PA</option>
                                            <option value="PB">PB</option>
                                            <option value="PR">PR</option>
                                            <option value="PE">PE</option>
                                            <option value="PI">PI</option>
                                            <option value="RJ">RJ</option>
                                            <option value="RN">RN</option>
                                            <option value="RS">RS</option>
                                            <option value="RO">RO</option>
                                            <option value="RR">RR</option>
                                            <option value="SC">SC</option>
                                            <option value="SP">SP</option>
                                            <option value="SE">SE</option>
                                            <option value="TO">TO</option>
                                            <option value="DF">DF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" placeholder="Cidade " class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" placeholder="Bairro " class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="rua" class="form-label">Logradouro</label>
                                        <input type="text" name="rua" id="rua" placeholder="Logradouro " class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="num_residencia" class="form-label">Número da Residência</label>
                                        <input type="text" name="num_residencia" id="num_residencia" placeholder="Número da residência " class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" name="email" id="email" placeholder="Insira seu e-mail" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="senha" class="form-label">Senha</label>

                                        <div class="input-group">
                                            <input type="password" class="form-control" id="senha" name="senha" required placeholder="Crie sua senha" aria-describedby="mostrar" required>
                                            <!-- Botão de mostrar/ocultar a senha -->
                                            <button type="button" id="mostrar" onclick="mostra()" class="btn btn-outline-secondary">
                                                <i class="bi bi-eye" id="olho"></i>
                                            </button>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="confsenha" class="form-label">Confirmação senha</label>
                                        <input type="password" class="form-control" id="confsenha" name="confsenha" required aria-describedby="confsenha confsenhaFeedback" onblur="verifica_senhas();" placeholder="Confirme sua senha " required>
                                        <div id="confsenhaFeedback" class="invalid-feedback">
                                            As senhas informadas não estão iguais.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">


                                <br><br>

                                <div class="bot">
                                    <a href="inicio-banco.php?id=<?= $id ?>" class="btn btn-danger">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">Gravar</button>
                                    <button type="reset" class="btn btn-warning">Apagar</button>
                                </div>
                            </div>
                        </div>
                    </div>

            </form>



            </div>
            <br><br>

    <?php
        require "footer.php";
    } else {
        redireciona();
    }
} else {
    redireciona();
}
