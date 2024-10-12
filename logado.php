<?php
session_start();
require 'logica-autenticacao.php';
require 'conexao.php';

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_SPECIAL_CHARS);

$sql = "SELECT senha_hash, tipo, login_user, idLogin FROM logi WHERE login_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);

$row = $stmt->fetch();



if (password_verify($senha, $row['senha_hash'])) {
    // Deu certo


    if ($row['tipo'] == "DOADOR") {
        //Doador
        $sql = "SELECT senha,idDoador,nome,status_doador FROM doador WHERE usuario = ?";
        $stmt_doador = $conn->prepare($sql);
        $stmt_doador->execute([$email]);
        $row_doador = $stmt_doador->fetch();
        $id = $row_doador['idDoador'];

        $_SESSION["email"] = $email;
        $_SESSION["nome"] = $row_doador['nome'];
        $_SESSION["idDoador"] = $id;
        if ($row_doador['status_doador'] == "ATIVO") {
            header("Location: login_sucesso.php?id=$id");
        } else {

            unset($_SESSION["email"]);
            unset($_SESSION["nome"]);
            unset($_SESSION["idDoador"]);
            header("Location: login_erro.php");
        }
    } else if ($row['tipo'] == "BANCO") {
        //Banco

        $sql = "SELECT senha,id_banco,nome, status_banco FROM banco WHERE usuario = ?";
        $stmt_banco = $conn->prepare($sql);
        $stmt_banco->execute([$email]);
        $row_banco = $stmt_banco->fetch();
        $id = $row_banco['id_banco'];

        $_SESSION["email"] = $email;
        $_SESSION["nome"] = $row_banco['nome'];
        $_SESSION["idDoador"] = $id;

        if ($row_banco['status_banco'] == "ATIVO") {
            header("Location: inicio-banco.php?id=$id");
        } else {

            unset($_SESSION["email"]);
            unset($_SESSION["nome"]);
            unset($_SESSION["idDoador"]);
            header("Location: login_erro.php");
        }

        // header("Location: login-banco.php?id=$id");
    } else if ($row['tipo'] == "FUNCIONARIO") {
        //Funcionario

        $sql = "SELECT senha,id_funcionario,nome,status_func FROM funcionario WHERE usuario = ?";
        $stmt_funcionario = $conn->prepare($sql);
        $stmt_funcionario->execute([$email]);
        $row_funcionario = $stmt_funcionario->fetch();
        $id = $row_funcionario['id_funcionario'];

        $_SESSION["email"] = $email;
        $_SESSION["nome"] = $row_funcionario['nome'];
        $_SESSION["idDoador"] = $id;

        if ($row_funcionario['status_func'] == "ATIVO") {
            header("Location: inicio-funcionario.php?id=$id");
        } else {

            unset($_SESSION["email"]);
            unset($_SESSION["nome"]);
            unset($_SESSION["idDoador"]);
            header("Location: login_erro.php");
        }
    } else if ($row['tipo'] == "ADM") {
        //Administrador
        $id = $row['idLogin'];
        $_SESSION["email"] = $email;
        $_SESSION["idDoador"] = $id;
        header("Location: login-adm.php?id=$id");
    }
} else {
    //Não deu certo

    unset($_SESSION["email"]);
    unset($_SESSION["nome"]);
    unset($_SESSION["idDoador"]);

    header("Location: login_erro.php");
}
require_once 'footer.php';
