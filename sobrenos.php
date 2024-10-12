<?php
$header = 1;
require "header.php";
?>

<style>
  .img {
    border-radius: 15%;
    border: solid 2px black;
  }
</style>


<main>
  <br><br><br>

  <div class="container marketing">

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading fw-normal lh-1">Sobre nós</h2>
        <div class="just">
          <p class="lead">Meraki é uma palavra de origem grega que significa que deixar um pedaço de si mesmo, uma parte da sua alma, do seus sentimentos, em tudo o que você faz, colocando um pouco de amor em cada detalhe. Por este motivo, a comunidade leva este nome.</p>

          <p class="lead">O Banco de Sangue Virtual é uma instituição criada com o intuito de incentivar as pessoas a doarem sangue de forma ágil, segura e acessível. </p>

          <p class="lead">O projeto teve iniciativa em 2004 através de um projeto de conclusao de curso, desenvolvido no Instituto Federal Campus Votuporanga pelos alunos: Breno Cauã Vialle da Silva, Júlia Geromello Queizada e Mariana Batista Gonçalves.</p>
        </div>
      </div>
      <div class="col-md-5">
        <img src="fotos/nos.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto img" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false">
      </div>
    </div>

    <?php
    require "footer.php";
    ?>