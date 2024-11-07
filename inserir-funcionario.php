<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id');
    $n = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_SANITIZE_SPECIAL_CHARS);
    $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    $r = filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS);
    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
    $c = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS);
    $b = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS);
    $num_residencia = filter_input(INPUT_POST, 'num_residencia', FILTER_SANITIZE_SPECIAL_CHARS);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha');
    $status = "ATIVO";

    $tipo_user = "FUNCIONARIO";
    if ($_SESSION['idDoador'] == $id) {
        $nome = strtoupper($n);
        $rua = strtoupper($r);
        $cidade = strtoupper($c);
        $bairro = strtoupper($b);

        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);



        // Verifique se o email já existe

        $stmt_select2 = $conn->prepare('SELECT COUNT(*) FROM logi WHERE login_user = :login_user');
        $stmt_select2->bindParam(':login_user', $email, PDO::PARAM_STR);
        $stmt_select2->execute();
        $count2 = $stmt_select2->fetchColumn();

        if ($count2 == 0) {
            // O email não existe, pode inserir os dados
            $stmt_insert = $conn->prepare('INSERT INTO funcionario (id_banco, nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario, senha, status_func) 
                                      VALUES (:id_banco, :nome, :tipo, :idade, :sexo, :telefone, :rua, :cep, :cidade, :bairro, :num_residencia, :estado, :usuario, :senha, :status_func)');
            // Definindo parâmetros

            $stmt_insert->bindParam(':id_banco', $id, PDO::PARAM_INT);
            $stmt_insert->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt_insert->bindParam(':idade', $idade, PDO::PARAM_INT);
            $stmt_insert->bindParam(':sexo', $sexo, PDO::PARAM_STR);
            $stmt_insert->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt_insert->bindParam(':rua', $rua, PDO::PARAM_STR);
            $stmt_insert->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt_insert->bindParam(':cidade', $cidade, PDO::PARAM_STR);
            $stmt_insert->bindParam(':bairro', $bairro, PDO::PARAM_STR);
            $stmt_insert->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
            $stmt_insert->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt_insert->bindParam(':usuario', $email, PDO::PARAM_STR);
            $stmt_insert->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt_insert->bindParam(':tipo', $tipo_user, PDO::PARAM_STR);
            $stmt_insert->bindParam(':status_func', $status, PDO::PARAM_STR);

            $result = $stmt_insert->execute();

            $stmt_login = $conn->prepare('INSERT INTO logi(login_user, senha_hash, tipo) VALUES (:login_user, :senha_hash, :tipo)');
            $stmt_login->bindParam(':login_user', $email, PDO::PARAM_STR);
            $stmt_login->bindParam(':senha_hash', $senha_hash, PDO::PARAM_STR);
            $stmt_login->bindParam(':tipo', $tipo_user, PDO::PARAM_STR);

            $result_logi = $stmt_login->execute();

            if ($result) {
                // Deu certo o insert
                header("Location: dados_funcionario_enviados.php?id=$id");
            } else {
                // Não deu certo o insert, erro!
                header("Location: dados_funcionario_nao_enviados.php?id=$id");
            }
        } else {
            // O email já existe
            header("Location: email_existente_funcionario.php?email=$email&id=$id");
        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}
