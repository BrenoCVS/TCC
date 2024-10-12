<?php
session_start();
require "logica-autenticacao.php";
require 'conexao.php';

if (autenticado()) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id_funcionario = filter_input(INPUT_GET, 'id_funcionario', FILTER_SANITIZE_NUMBER_INT);

    if ($_SESSION["idDoador"] == $id) {

        /*$sql = "DELETE FROM `produtos` WHERE 0";*/

        $sql = "UPDATE funcionario SET status_func = 'DESATIVADO', usuario = 'DESATIVADO', senha = 'DESATIVADO'
        WHERE id_funcionario = ?";
        $sql2 = "SELECT usuario FROM funcionario WHERE id_funcionario = ?";
        $sql3 = "DELETE FROM logi WHERE login_user = ?";

        $stmt2 = $conn->prepare($sql2);
        $result2 = $stmt2->execute([$id_funcionario]);
        $row = $stmt2->fetch();

        $stmt3 = $conn->prepare($sql3);
        $result3 = $stmt3->execute([$row['usuario']]);
        $count3 = $stmt3->rowCount();

        //exluindo
        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([$id_funcionario]);

        $count = $stmt->rowCount();

        if ($result == true && $count >= 1) {
            //deu certo a exclusão
            header("Location: ver-funcionarios.php?id=$id&modi=3");
        } else {
            //nao deu certo o insert, erro!
            header("Location: ver-funcionarios.php?id=$id&modi=4");
        }
    } else {
        redireciona();
    }
} else {
    redireciona();
}


require 'footer.php';
