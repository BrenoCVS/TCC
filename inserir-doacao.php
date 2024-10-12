<?php
session_start();
require "logica-autenticacao.php";
if (autenticado()) {

    require 'conexao.php';

    $id = filter_input(INPUT_GET, 'id');
    $id_doador = filter_input(INPUT_GET, 'id_doador');
    $n = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $dia_doacao = date('j');
    $mes_doacao = date('n');
    $ano_doacao = date('Y');
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
    $exames = filter_input(INPUT_POST, 'exames', FILTER_SANITIZE_SPECIAL_CHARS);
    $data_doacao = date('Y-m-d');

    //vendo quando o doador pode doar

    $sqldoacao = "SELECT * FROM doacao WHERE id_doador = ? ORDER BY data_doacao DESC LIMIT 1";
    $stmtdoacao = $conn->prepare($sqldoacao);
    $result2 = $stmtdoacao->execute([$id_doador]);
    $rowdoacao = $stmtdoacao->fetch(PDO::FETCH_ASSOC);





    $stmt_select = $conn->prepare('SELECT COUNT(*) FROM doacao WHERE id_doador = :id_doador');
    $stmt_select->bindParam(':id_doador', $id_doador, PDO::PARAM_STR);
    $stmt_select->execute();
    $count = $stmt_select->fetchColumn();


    if ($count != 0) {

        $sqldoador = "SELECT sexo FROM doador WHERE idDoador = ?";
        $stmtdoador = $conn->prepare($sqldoador);
        $stmtdoador->execute([$id_doador]);
        $rowdoador = $stmtdoador->fetch(PDO::FETCH_ASSOC);

        //teste de data da Doação
        if ($rowdoador['sexo'] == "MASCULINO") {

            // Adiciona dois meses na data em que a doação foi feita 
            $dataPodeDoar = new DateTime($rowdoacao['data_doacao']);

            // Soma dois meses à data atual
            $dataPodeDoar->add(new DateInterval('P2M'));

            $dataatual = date('Y-m-d');

            // Formata a data para um formato legível, se necessário
            $dataPodeFormatada = $dataPodeDoar->format('Y-m-d');
            if ($dataPodeFormatada <= $dataAtual) {
                $doacao = "PODE";
            } else {
                $doacao = "NAO PODE";
                $dataMinima = "NAO";
            }
        } else {
            // Adiciona dois meses na data em que a doação foi feita 
            $dataPodeDoar = new DateTime($rowdoacao['data_doacao']);

            // Soma dois meses à data atual
            $dataPodeDoar->add(new DateInterval('P3M'));

            $dataatual = date('Y-m-d');

            // Formata a data para um formato legível, se necessário
            $dataPodeFormatada = $dataPodeDoar->format('Y-m-d');
            if ($dataPodeFormatada <= $dataAtual) {
                $doacao = "PODE";
            } else {
                $doacao = "NAO PODE";
                $dataminima = "NAO";
            }
        }
    } else {
        $doacao = "PODE";
    }



    if ($doacao == "PODE") {
        //Pegando o id do banco

        $sql = "SELECT id_banco FROM funcionario WHERE id_funcionario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();


        $stmt_insert = $conn->prepare('INSERT INTO doacao (id_doador, id_funcionario, id_banco, tipo_sanguineo, dia_doacao, mes_doacao, ano_doacao, data_doacao, exames) 
                                      VALUES (:id_doador, :id_funcionario, :id_banco, :tipo_sanguineo, :dia_doacao, :mes_doacao, :ano_doacao, :data_doacao, :exames)');
        // Definindo parâmetros

        $stmt_insert->bindParam(':id_doador', $id_doador, PDO::PARAM_INT);
        $stmt_insert->bindParam(':id_funcionario', $id, PDO::PARAM_STR);
        $stmt_insert->bindParam(':id_banco', $row['id_banco'], PDO::PARAM_INT);
        $stmt_insert->bindParam(':dia_doacao', $dia_doacao, PDO::PARAM_STR);
        $stmt_insert->bindParam(':mes_doacao', $mes_doacao, PDO::PARAM_STR);
        $stmt_insert->bindParam(':ano_doacao', $ano_doacao, PDO::PARAM_STR);
        $stmt_insert->bindParam(':data_doacao', $data_doacao, PDO::PARAM_STR);
        $stmt_insert->bindParam(':exames', $exames, PDO::PARAM_STR);
        $stmt_insert->bindParam(':tipo_sanguineo', $tipo, PDO::PARAM_STR);

        $result = $stmt_insert->execute();
    } else if ($doacao == "NAO PODE") {
        //Nao passou o tempo minimo
        echo 'erro ';
    }

    if ($result) {
        // o inseet deu certo
        header("Location: inicio-funcionario.php?id=$id&mod=1");
    } else {
        // Não deu certo o insert, erro!
        if ($dataMinima == "NAO") {
            $dataFormatada = $dataPodeDoar->format("d/m/Y");
            header("Location: inicio-funcionario.php?id=$id&mod=3&data=$dataFormatada");
        } else {
            header("Location: inicio-funcionario.php?id=$id&mod=2");
        }
    }
} else {
    redireciona();
}
