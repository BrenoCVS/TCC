<?php

function limpaPesquisa($const)
{
    $remover = "'";
    $stringSemCaractere = str_replace($remover, "", $const);
    return $stringSemCaractere;
}
