<?php
session_start();
require_once 'conexao.php';
require "logica-autenticacao.php";
if (autenticado()) {

    $id = filter_input(INPUT_GET, "id");

    if ($_SESSION["idDoador"] == $id) {

        $sql = "SELECT foto, tipo_foto FROM doador WHERE idDoador = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        $tipo_arquivo = $row["tipo_foto"];

        header("Content-type: " . $tipo_arquivo);
        echo $dados_arquivo = $row["foto"];
        //echo pg_unescape_bytea($dados_arquivo);
    } else {
        redireciona();
    }
} else {
    redireciona();
}
