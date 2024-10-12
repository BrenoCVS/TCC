<?php

function autenticado()
{
    if (isset($_SESSION['email'])) {
        return true;
    } else {
        return false;
    }
}

function idDoador()
{
    return $_SESSION['idDoador'];
}

function nome_usuario()
{
    return $_SESSION['nome'];
}

function email_usuario()
{
    return $_SESSION['email'];
}

function redireciona($pagina = null)
{
    if (empty($pagina)) {
        $pagina = 'login.php?nao=1';
    }
    header('Location:' . $pagina);
}
