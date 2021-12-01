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
    //echo RACINE  . PHP_EOL;
    //echo CONFIGS  . PHP_EOL;

    // var_dump(CONFIGS . 'DonneeUniqueServeur.php');
    $donnees_serveur = $_POST;

    $fp = fopen(CONFIGS . 'DonneeUniqueServeur.php', 'w');

    $attention = <<<INFOS
    /** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
    * Attention les données dans DonneeUniqueServeur ne doivent jamais 
    * devenir des données modifiable ou accessible en dehors d'une instanciation
    * il est imperative d'utilisé que des constantes. 
    */
INFOS;

    fwrite($fp, '<?php namespace Eukaruon\\configs;' . PHP_EOL . $attention . PHP_EOL . 'class DonneeUniqueServeur {' . PHP_EOL);
    foreach ($donnees_serveur as $cle => $valeurs) {
        fwrite($fp, 'const ' . $cle . ' = \'' . $valeurs . '\';' . PHP_EOL);
    }
    fwrite($fp, 'const MAJ_VAR = \'' . time() . '\';' . PHP_EOL);
    fwrite($fp, 'const LISTING_VAR = ' . var_export(array_keys($donnees_serveur), true) . ';}' . PHP_EOL);
    fclose($fp);


    echo 'mise à jour terminé ! ';
} else {
    echo 'mise à jour Erreur !';
}