<?php

ini_set('html_errors', false);


use Eukaruon\configs\CMD;
use Eukaruon\pilote;

include_once '../pilote.php';
$pilote = new pilote();

$Modules_pages = $pilote->Charger_le_module(
    module_a_charger: 'Modules_pages',
    modules_primaire: [CMD::MODULES_BDD]
);

$var_filtrer = array();
foreach ($_POST as $key => $val) {
    // si tableau rien n'est réalisé
    // on bloque donc les tableaux
    if (!is_array($val)) {
        $filtrer = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
        $var_filtrer[$key] = filtrer($filtrer);
    }
}

$Modules_pages->mise_en_stockage($var_filtrer);

var_dump($var_filtrer);
echo 'ok mise en session des valeurs';

function filtrer($var, $filtre = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    '.', '-', '_', '@']): string
{
    $nouveau_var = '';
    $taillemax = strlen($var);
    for ($curseur = 0; $curseur < $taillemax; $curseur++) {
        if (in_array($var[$curseur], $filtre)) {
            $nouveau_var .= $var[$curseur];
        }
    }
    return $nouveau_var;
}