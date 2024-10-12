<?php
session_start();
require "logica-autenticacao.php";
require 'conexao.php';

if (autenticado()) {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($_SESSION["idDoador"] == $id) {

        /*$sql = "DELETE FROM `produtos` WHERE 0";*/

        $sql = " UPDATE  banco SET status_banco = 'DESATIVADO', usuario = 'DESTIDADO', senha = 'DESATIVAFO'
         WHERE id_banco = ?";
        $sql2 = "SELECT usuario FROM banco WHERE id_banco = ?";
        $sql3 = "DELETE FROM logi WHERE login_user = ?";
        $sql4 = "UPDATE funcionario SET status_func = 'DESATIVADO', usuario = 'DESATIVADO', senha = 'DESATIVADO', statusb='RECUSADO'
        WHERE id_banco = ?";
        $sql5 = "SELECT * FROM funcionario WHERE id_banco = ?";
        $stmt5 = $conn->prepare($sql5);
        $result5 = $stmt5->execute([$id]);

        while ($row5 = $stmt5->fetch()) {
?>
                <?= $row5['usuario'] ?>
            <?php
            $sql6 = "DELETE FROM logi WHERE login_user = ?";
            $stmt6 = $conn->prepare($sql6);
            $result4 = $stmt6->execute([$row5['usuario']]);
        }

        $stmt4 = $conn->prepare($sql4);
        $result4 = $stmt4->execute([$id]);



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
