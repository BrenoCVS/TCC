<?php
require 'conexao.php';

$n = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$r = filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS);
$cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
$c = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS);
$b = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS);
$num_residencia = filter_input(INPUT_POST, 'num_residencia', FILTER_SANITIZE_SPECIAL_CHARS);
$estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha');
$relatorio = "";
$status_banco = "ATIVO";

$tipo = "BANCO";

$nome = strtoupper($n);
$rua = strtoupper($r);
$cidade = strtoupper($c);
$bairro = strtoupper($b);

$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

// Verifique se o email já existe
$stmt_select = $conn->prepare('SELECT COUNT(*) FROM logi WHERE login_user = :login_user');
$stmt_select->bindParam(':login_user', $email, PDO::PARAM_STR);
$stmt_select->execute();
$count = $stmt_select->fetchColumn();

if ($count == 0) {
    // O email não existe, pode inserir os dados
    $stmt_insert = $conn->prepare('INSERT INTO banco (nome, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario, senha, statusb, relatorio_adm, status_banco) 
                                      VALUES (:nome, :telefone, :rua, :cep, :cidade, :bairro, :num_residencia, :estado, :usuario, :senha, :statusb, :relatorio_adm, :status_banco)');
    // Definindo parâmetros
    $status = "PENDENTE";
    $stmt_insert->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt_insert->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $stmt_insert->bindParam(':rua', $rua, PDO::PARAM_STR);
    $stmt_insert->bindParam(':cep', $cep, PDO::PARAM_STR);
    $stmt_insert->bindParam(':cidade', $cidade, PDO::PARAM_STR);
    $stmt_insert->bindParam(':bairro', $bairro, PDO::PARAM_STR);
    $stmt_insert->bindParam(':num_residencia', $num_residencia, PDO::PARAM_STR);
    $stmt_insert->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt_insert->bindParam(':usuario', $email, PDO::PARAM_STR);
    $stmt_insert->bindParam(':senha', $senha_hash, PDO::PARAM_STR);
    $stmt_insert->bindParam(':statusb', $status, PDO::PARAM_STR);
    $stmt_insert->bindParam(':relatorio_adm', $relatorio, PDO::PARAM_STR);
    $stmt_insert->bindParam(':status_banco', $status_banco, PDO::PARAM_STR);


    $result = $stmt_insert->execute();

    $stmt_login = $conn->prepare('INSERT INTO logi(login_user, senha_hash, tipo) VALUES (:login_user, :senha_hash, :tipo)');
    $stmt_login->bindParam(':login_user', $email, PDO::PARAM_STR);
    $stmt_login->bindParam(':senha_hash', $senha_hash, PDO::PARAM_STR);
    $stmt_login->bindParam(':tipo', $tipo, PDO::PARAM_STR);

    $result_logi = $stmt_login->execute();

    if ($result) {
        // Deu certo o insert
        header("Location: dados_enviados_banco.php");
    } else {
        // Não deu certo o insert, erro!
        header("Location: dados_nao_enviados-banco.php");
    }
} else {
    // O email já existe
    header("Location: email_existente-banco.php?email=$email");
}
