<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // Cria um objeto DateTime com a data e hora atuais
    $dataAtual = new DateTime();

    // Soma dois meses à data atual
    $dataAtual->modify('+2 months');

    // Formata a data para um formato legível, se necessário
    $dataFormatada = $dataAtual->format('Y-m-d');

    // Cria um objeto DateTime com a data e hora atuais
    $dataHoje = new DateTime();

    // Soma dois meses à data atual
    $dataHoje->modify('+2 months');

    // Formata a data para um formato legível, se necessário
    $dataHojeFormatada = $dataHoje->format('Y-m-d');

    if ($dataHojeFormatada >= $dataFormatada) {
        echo 'deu certo';
    } else {
        echo 'deu rrado';
    }
    ?>
</body>

</html>