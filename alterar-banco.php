<?php
session_start();
require "logica-autenticacao.php";

// Verifica se o usuário está autenticado
if (autenticado()) {
    require 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

    // Filtra e obtém os parâmetros da URL
    $id = filter_input(INPUT_GET, 'id-adm', FILTER_SANITIZE_SPECIAL_CHARS);
    $id_banco = filter_input(INPUT_GET, 'id-banco', FILTER_SANITIZE_SPECIAL_CHARS);
    $mod = filter_input(INPUT_GET, 'mod', FILTER_SANITIZE_SPECIAL_CHARS);
    $relatorio = filter_input(INPUT_POST, 'relatorio', FILTER_SANITIZE_SPECIAL_CHARS);


    // Verifica se o ID do administrador na sessão é o mesmo que está sendo passado
    if ($_SESSION["idDoador"] == $id) {
        if ($mod == 1) {
            // Atualiza o status para RECUSADO
            $sql = "UPDATE banco SET statusb='RECUSADO' WHERE id_banco = :id_banco";

            //Informando porque o banco foi recusado
            $sqlRelatorio = "UPDATE banco SET relatorio_adm = :relatorio_adm WHERE id_banco = :id_banco";
            $stmtRelatorio = $conn->prepare($sqlRelatorio);
            $stmtRelatorio->bindParam(':relatorio_adm', $relatorio, PDO::PARAM_STR);
            $stmtRelatorio->bindParam(':id_banco', $id_banco, PDO::PARAM_INT);
?>
            <?= $relatorio ?>
            <?php
            // Executa a consulta
            $resultRelatorio = $stmtRelatorio->execute();
        } else if ($mod == 2) {
            // Atualiza o status para APROVADO
            $sql = "UPDATE banco SET statusb='APROVADO' WHERE id_banco = :id_banco";
        } else if ($mod == 3) {
            // Atualiza o status para PENDENTE
            $sql = "UPDATE banco SET statusb='PENDENTE' WHERE id_banco = :id_banco";
        }

        // Prepara a consulta
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_banco', $id_banco, PDO::PARAM_INT);

        // Executa a consulta
        $result = $stmt->execute();

        // Verifica se a atualização foi bem-sucedida e redireciona conforme necessário
        if ($result) {
            header("Location: login-adm.php?id=$id&modi=2");
            exit(); // Termina o script após o redirecionamento
        } else {
            header("Location: login-adm.php?id=$id&modi=1");
            exit(); // Termina o script após o redirecionamento
        }
    } else {
        redireciona(); // Função redireciona() deve ser definida em logica-autenticacao.php
    }
} else {
    redireciona(); // Função redireciona() deve ser definida em logica-autenticacao.php
}
