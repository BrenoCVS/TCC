<?php
require 'conexao.php';

$n = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
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

$tipo_user = "DOADOR";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $cookie_expire = time() + 3600;

  setcookie('nome', $n, $cookie_expire);
  setcookie('idade', $idade, $cookie_expire);
  setcookie('email', $email, $cookie_expire);
  setcookie('telefone', $telefone, $cookie_expire);
  setcookie('cep', $cep, $cookie_expire);
  setcookie('cidade', $c, $cookie_expire);
  setcookie('bairro', $b, $cookie_expire);
  setcookie('rua', $r, $cookie_expire);
  setcookie('num_residencia', $num_residencia, $cookie_expire);
  setcookie('sexo', $sexo, $cookie_expire);
  setcookie('tipo', $tipo, $cookie_expire);
  setcookie('estado', $estado, $cookie_expire);
}

$nome = strtoupper($n);
$rua = strtoupper($r);
$cidade = strtoupper($c);
$bairro = strtoupper($b);

$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

if ($idade >= 16 && $idade <= 69) {
  // Recupera os dados dos campos
  $foto = $_FILES['foto'];
  $imgnome = $foto['name'];
  $imgtipo = $foto['type'];
  $tamanho = $foto['size'];

  $conteudo = file_get_contents($foto['tmp_name']);

  // Verifique se o email já existe
  $stmt_select2 = $conn->prepare('SELECT COUNT(*) FROM logi WHERE login_user = :login_user');
  $stmt_select2->bindParam(':login_user', $email, PDO::PARAM_STR);
  $stmt_select2->execute();
  $count2 = $stmt_select2->fetchColumn();

  if ($count2 == 0) {
    // O email não existe, pode inserir os dados
    $stmt_insert = $conn->prepare('INSERT INTO doador (nome, tipo, idade, sexo, telefone, rua, cep, cidade, bairro, num_residencia, estado, usuario, senha, foto, tipo_foto, tipo_usuario, status_doador) 
                                      VALUES (:nome, :tipo, :idade, :sexo, :telefone, :rua, :cep, :cidade, :bairro, :num_residencia, :estado, :usuario, :senha, :foto, :tipo_foto, :tipo_usuario, :status_doador)');
    // Definindo parâmetros

    $stmt_insert->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt_insert->bindParam(':tipo', $tipo, PDO::PARAM_STR);
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
    $stmt_insert->bindParam(':foto', $conteudo, PDO::PARAM_LOB);
    $stmt_insert->bindParam(':tipo_foto', $imgtipo, PDO::PARAM_STR);
    $stmt_insert->bindParam(':tipo_usuario', $tipo_user, PDO::PARAM_STR);
    $stmt_insert->bindParam(':status_doador', $status, PDO::PARAM_STR);

    $result = $stmt_insert->execute();

    $stmt_login = $conn->prepare('INSERT INTO logi(login_user, senha_hash, tipo) VALUES (:login_user, :senha_hash, :tipo)');
    $stmt_login->bindParam(':login_user', $email, PDO::PARAM_STR);
    $stmt_login->bindParam(':senha_hash', $senha_hash, PDO::PARAM_STR);
    $stmt_login->bindParam(':tipo', $tipo_user, PDO::PARAM_STR);

    $result_logi = $stmt_login->execute();

    if ($result) {
      // Deu certo o insert
      header("Location: dados_enviados.php");
    } else {
      // Não deu certo o insert, erro!
      header("Location: dados_nao_enviados.php");
    }
  } else {
    // O email já existe
    header("Location: email_existente.php?email=$email");
  }
} elseif ($idade < 16) {
  // Idade mínima inadequada
  header("Location: idade_minima.php");
} elseif ($idade > 69) {
  // Idade máxima inadequada
  header("Location: idade_maxima.php");
}
