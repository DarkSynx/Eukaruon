<?php
ini_set('html_errors', false);
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
include REROOT_ADMIN . 'chemins.php';


//var_dump(CONFIGS);
$administration_outils = new administration_outils();

if ($administration_outils->tester_la_validiter_de_la_demande()) {


    include CHEMIN_SITE . 'chemins.php';

    $donnees_serveur = $_POST;


    $fp = fopen(CONFIGS . 'admin_pilote.php', 'w');
    fwrite($fp, '<?php namespace Eukaruon\\configs;' . PHP_EOL . 'class admin_pilote {' . PHP_EOL . PHP_EOL);

    fwrite($fp, 'Const REGISTRE =' . var_export_style($donnees_serveur) . ';' . PHP_EOL . PHP_EOL);

    fwrite($fp, '}');
    fclose($fp);


    echo 'mise à jour terminé ! ';
} else {
    echo 'mise à jour Erreur !';
}

function var_export_style($valeur): string
{
    return str_replace(
        ['array (', ')', "[\n    ]", "=> \n    [", "[\n]", "=> \n  ["],
        ['[', ']', '[]', "=> [", '[]', "=> ["], var_export($valeur, true));
}