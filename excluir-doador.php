<?php
session_start();
require "logica-autenticacao.php";
require 'conexao.php';

if (autenticado()) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($_SESSION["idDoador"] == $id) {

        /*$sql = "DELETE FROM `produtos` WHERE 0";*/

        $sql = " UPDATE  doador SET status_doador = 'DESATIVADO', usuario = 'DESTIDADO', senha = 'DESATIVAFO'
         WHERE idDoador = ?";
        $sql2 = "SELECT usuario FROM doador WHERE idDoador = ?";
        $sql3 = "DELETE FROM logi WHERE login_user = ?";

        $stmt2 = $conn->prepare($sql2);
        $result2 = $stmt2->execute([$id]);
        $row = $stmt2->fetch();

        $stmt3 = $conn->prepare($sql3);
        $result3 = $stmt3->execute([$row['usuario']]);
        $count3 = $stmt3->rowCount();

        //exluindo
        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([$id]);

        $count = $stmt->rowCount();

        if ($result == true && $count >= 1) {
            //deu certo a exclusão
            header("Location: excluir-sucesso.php");
        } else {
            //nao deu certo o insert, erro!
            header("Location: excluir-erro.php");
        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}


require 'footer.php';
