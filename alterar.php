  <?php
    session_start();
    require "logica-autenticacao.php";
    if (autenticado()) {
        require 'conexao.php';


        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $id_funcionario = filter_input(INPUT_GET, 'id_funcionario', FILTER_SANITIZE_SPECIAL_CHARS);
        $user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_SPECIAL_CHARS);

        $logi = filter_input(INPUT_POST, 'logi', FILTER_SANITIZE_EMAIL);
        if ($user == 1) {
            //doador    
            $nome = strtoupper(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
            $foto = filter_input(INPUT_POST, 'foto');
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
            $idade = strtoupper(filter_input(INPUT_POST, 'idade', FILTER_SANITIZE_SPECIAL_CHARS));
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
            $rua = strtoupper(filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS));
            $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
            $cidade = strtoupper(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS));
            $bairro = strtoupper(filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS));
            $num_residencia = filter_input(INPUT_POST, 'num_residencia', FILTER_SANITIZE_SPECIAL_CHARS);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);


            if (isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
                $foto = $_FILES['foto'];
                $imgnome = $foto['name'];
                $imgtipo = $foto['type'];
                $tamanho = $foto['size'];
                $conteudo = file_get_contents($foto['tmp_name']);
            } else {
                $conteudo = null;
            }

            if ($idade >= 16 && $idade <= 69) {


                if (!empty($conteudo)) {
                    $stmt = $conn->prepare('UPDATE doador SET nome = :nome, tipo = :tipo, idade = :idade, sexo = :sexo, 
            telefone = :telefone, rua = :rua, cep = :cep, cidade = :cidade, bairro = :bairro, num_residencia = :num_residencia, 
            estado = :estado, usuario = :usuario, foto = :foto, tipo_foto = :tipo_foto WHERE idDoador = :idDoador');

                    // Definindo parâmetros com foto
                    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                    $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
                    $stmt->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                    $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                    $stmt->bindParam(':rua', $rua, PDO::PARAM_STR);
                    $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
                    $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
                    $stmt->bindParam(':bairro', $bairro, PDO::PARAM_STR);
                    $stmt->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
                    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
                    $stmt->bindParam(':usuario', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':foto', $conteudo, PDO::PARAM_LOB);
                    $stmt->bindParam(':tipo_foto', $imgtipo, PDO::PARAM_STR);
                    $stmt->bindParam(':idDoador', $id, PDO::PARAM_STR);
                } else {
                    $stmt = $conn->prepare('UPDATE doador SET nome = :nome, tipo = :tipo, idade = :idade, sexo = :sexo, 
            telefone = :telefone, rua = :rua, cep = :cep, cidade = :cidade, bairro = :bairro, num_residencia = :num_residencia, 
            estado = :estado, usuario = :usuario WHERE idDoador = :idDoador');

                    // Definindo parâmetros sem a foto
                    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                    $stmt->bindParam(':idade', $idade, PDO::PARAM_INT);
                    $stmt->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                    $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                    $stmt->bindParam(':rua', $rua, PDO::PARAM_STR);
                    $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
                    $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
                    $stmt->bindParam(':bairro', $bairro, PDO::PARAM_STR);
                    $stmt->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
                    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
                    $stmt->bindParam(':usuario', $email, PDO::PARAM_STR);

                    $stmt->bindParam(':idDoador', $id, PDO::PARAM_STR);
                }

                $result = $stmt->execute();

                $sql = ("SELECT * FROM logi WHERE login_user = ?");
                $stmtP = $conn->prepare($sql);
                $stmtP->execute([$logi]);

                $row = $stmtP->fetch();

                $stmtLogi = $conn->prepare('UPDATE logi SET login_user = :login_user WHERE idLogin = :idLogin');

                $stmtLogi->bindParam(':login_user', $email, PDO::PARAM_STR);
                $stmtLogi->bindParam(':idLogin', $row['idLogin'], PDO::PARAM_INT);

                $resultLogi = $stmtLogi->execute();


                if ($result == true) {
                    //deu certo o insert
                    header("Location: dados_alterados.php?id=$id");
                } else {
                    //nao deu certo o insert, erro!
                    header("Location: dados_nao_alterados.php?id=$id");
                }
            }
        } else if ($user == 2) {
            //banco
            $n = strtoUpper(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
            $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
            $r = strtoUpper(filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS));
            $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
            $c = strtoUpper(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS));
            $b = strtoUpper(filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS));
            $num_residencia = filter_input(INPUT_POST, 'num_residencia', FILTER_SANITIZE_SPECIAL_CHARS);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $tipo = 'PENDENTE';
            $relatorio = "";

            $stmt = $conn->prepare('UPDATE banco SET nome = :nome, telefone = :telefone, rua = :rua,
        cep = :cep, cidade = :cidade, bairro = :bairro, num_residencia = :num_residencia, estado = :estado,
        usuario = :usuario, statusb = :statusb, relatorio_adm = :relatorio_adm WHERE id_banco = :id_banco');

            $stmt->bindParam(':nome', $n, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':rua', $r, PDO::PARAM_STR);
            $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindParam(':cidade', $c, PDO::PARAM_STR);
            $stmt->bindParam(':bairro', $b, PDO::PARAM_STR);
            $stmt->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $email, PDO::PARAM_STR);
            $stmt->bindParam(':statusb', $tipo, PDO::PARAM_STR);
            $stmt->bindParam(':relatorio_adm', $relatorio, PDO::PARAM_STR);
            $stmt->bindParam(':id_banco', $id, PDO::PARAM_STR);


            $result = $stmt->execute();


            $sql = ("SELECT * FROM logi WHERE login_user = ?");
            $stmtP = $conn->prepare($sql);
            $stmtP->execute([$logi]);

            $row = $stmtP->fetch();

            $stmtLogi = $conn->prepare('UPDATE logi SET login_user = :login_user WHERE idLogin = :idLogin');

            $stmtLogi->bindParam(':login_user', $email, PDO::PARAM_STR);
            $stmtLogi->bindParam(':idLogin', $row['idLogin'], PDO::PARAM_INT);

            $resultLogi = $stmtLogi->execute();

            if ($result == true) {
                //deu certo o insert
                header("Location: login-banco.php?id=$id&modi=1");
            } else {
                //nao deu certo o insert, erro!
                header("Location: login-banco.php?id=$id&modi=2");
            }
        } else if ($user == 3) {
            //funcionario

            $n = strtoUpper(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
            $idade = filter_input(INPUT_POST, 'idade', FILTER_SANITIZE_SPECIAL_CHARS);
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
            $r = strtoUpper(filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS));
            $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
            $c = strtoUpper(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS));
            $b = strtoUpper(filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS));
            $num_residencia = filter_input(INPUT_POST, 'num_residencia', FILTER_SANITIZE_SPECIAL_CHARS);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            $stmt = $conn->prepare('UPDATE funcionario SET 
    nome = :nome, idade = :idade, telefone = :telefone, rua = :rua,cep = :cep, cidade = :cidade, 
    bairro = :bairro, num_residencia = :num_residencia, estado = :estado,usuario = :usuario, sexo = :sexo
    WHERE id_funcionario = :id_funcionario');

            $stmt->bindParam(':nome', $n, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':rua', $r, PDO::PARAM_STR);
            $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindParam(':cidade', $c, PDO::PARAM_STR);
            $stmt->bindParam(':bairro', $b, PDO::PARAM_STR);
            $stmt->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':usuario', $email, PDO::PARAM_STR);
            $stmt->bindParam(':sexo', $sexo, PDO::PARAM_STR);
            $stmt->bindParam(':idade', $idade, PDO::PARAM_STR);
            $stmt->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_STR);

            $result = $stmt->execute();

            $sql = ("SELECT * FROM logi WHERE login_user = ?");
            $stmtP = $conn->prepare($sql);
            $stmtP->execute([$logi]);

            $row = $stmtP->fetch();

            $stmtLogi = $conn->prepare('UPDATE logi SET login_user = :login_user WHERE idLogin = :idLogin');

            $stmtLogi->bindParam(':login_user', $email, PDO::PARAM_STR);
            $stmtLogi->bindParam(':idLogin', $row['idLogin'], PDO::PARAM_INT);

            $resultLogi = $stmtLogi->execute();



            if ($result == true) {
                //deu certo o insert
                header("Location: ver-funcionarios.php?id=$id&modi=1");
            } else {
                //nao deu certo o insert, erro!
                header("Location: ver-funcionarios.php?id=$id&modi=2");
            }
        }
    } else {
        redireciona();
    }
