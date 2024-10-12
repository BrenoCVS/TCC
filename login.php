<?php
$nao = filter_input(INPUT_GET, 'nao');

$header = 1;
require "header.php";

?>
<style>
  .icon-grande {
    font-size: 4em;
    /* Tamanho desejado */
  }
</style>
<main class="form-signin w-100 m-auto">
  <div class="text-center">
    <br><br><br><br>

    <form action="logado.php" method="post">

      <i class="bi bi-person-circle icon-grande"></i>

      <h1 class="h3 mb-3 fw-normal">Identifique-se</h1>

      <div class="row justify-content-center">

        <div class="mb-3 col-4 form-floating">
          <input type="email" class="form-control" id="email" name="email" placeholder="" required>
          <label for="email"> E-mail</label>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-4">
          <div class="input-group mb-3 form-floating ">

            <input type="password" class="form-control" id="senha" name="senha" placeholder="" required aria-describedby="mostrar">
            <label for="senha">Senha</label>

            <!-- Botao de mostar/ocultar a senha-->
            <button type="button" id="mostrar" onclick="mostra()" class="btn btn-outline-secondary">
              <i class="bi bi-eye" id="olho"></i>
            </button>

          </div>
        </div>
      </div>


      <br><br><br>
      <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
  </div>
  <script>
    function mostra() {
      const camposenha = document.getElementById('senha')
      const icone = document.getElementById("olho")

      if (camposenha.getAttribute('type') == 'password') {
        icone.classList.remove("bi-eye")
        icone.classList.add("bi-eye-slash")
        camposenha.setAttribute('type', 'text')

      } else {
        icone.classList.remove("bi-eye-slash")
        icone.classList.add("bi-eye")
        camposenha.setAttribute('type', 'password')
      }
    }
    <?php
    if ($nao == 1) {
    ?>

        <
        script >
        alert("Voce tentou entrar em uma página protegida sem realizar o login!")
  </script>

<?php
    }
?>
</script>
<br><br><br><br>

<?php
require "footer.php";
?>