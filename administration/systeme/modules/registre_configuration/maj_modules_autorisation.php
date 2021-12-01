<?php
ini_set('html_errors', false);
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
//echo REROOT_ADMIN  . PHP_EOL;
include REROOT_ADMIN . 'chemins.php';
//echo RACINE_ADMIN  . PHP_EOL;


//var_dump(CONFIGS);
$administration_outils = new administration_outils();

if ($administration_outils->tester_la_validiter_de_la_demande()) {


    include CHEMIN_SITE . 'chemins.php';
    //echo RACINE  . PHP_EOL;
    //echo CONFIGS  . PHP_EOL;

    // var_dump(CONFIGS . 'DonneeUniqueServeur.php');
    $donnees_serveur = $_POST;

    $fp = fopen(CONFIGS . 'Modules_autorisations.php', 'w');

    $attention = <<<INFOS
    /* permet d'obtenir la liste de droit d'utilisation et d'autorisation des modules */
    /* les autorisations sont composé de 2 maniéres d'exprimer les autorisation
     * global : le chiffre qui permet de spécifier une autorisation global
     *  0 => pas de restriction
     *  1 => restriction à tout les Modules primaires
     *  2 => restriction pas la liste d'exception
     *  3 => restriction à tout les Modules primaires et restriction pas la liste d'exception
     * exception : permet de spécifier les modules qui ne doivent pas etre charger dans un sous module
     * evidement : MODULE_INTERDIT_AU_SOUSMODULES prime sur AUTORISATION_PAR_MODULE
     * cette class à pour objectif de proposé une sécurité et sera générer et exploité
     * par un Module de votre création ou le module Administration via son interface Web
     * minimal.
     */
INFOS;
    $attention2 = <<<INFOS2
        /* les modules si dessous sont considéré comme sensible
         * il vous faudra donc les Charger avant puis récupérer les valeurs
         * la raison pour la quel ils ne sont pas disponible dans les sous-modules
         * c'est par ce qu'il y a une recherche d'herméticité pour assuré la sécurité :
         * 1. ne créé pas un Module primaire pour faire une passerel entre Modules et sousModules
         * 2. Dupliquer pour cantonner l'information des Modules dans des variables pour les sousModules
         * 3. une recherche d'herméticité par et dans des variables pour assuré la sécurité
         * 4. utilisé les Monades et le fonctionnel le plus souvant possible dans les sousModules
         * PS :: le meilleur cas d'utilisation de protection de donnée serait avec le module : Modules_securiser
         */
INFOS2;


    fwrite($fp, '<?php namespace Eukaruon\\configs;' . PHP_EOL . 'class Modules_autorisations {' . PHP_EOL . $attention . PHP_EOL . PHP_EOL . $attention2 . PHP_EOL);

    fwrite($fp, 'const MODULE_INTERDIT_AUX_SOUSMODULES = ');
    $mias = explode(',', str_replace(chr(32), null, $donnees_serveur['MODULE_INTERDIT_AUX_SOUSMODULES']));
    fwrite($fp, var_export_style($mias) . ';' . PHP_EOL);

    //AUTORISATION_PAR_MODULE_global_
    //AUTORISATION_PAR_MODULE_exception_
    fwrite($fp, 'const AUTORISATION_PAR_MODULE = ');
    //var_dump($donnees_serveur);

    $AUTORISATION_PAR_MODULE_list = array();
    foreach ($donnees_serveur as $index => $valeur) {
        $nom = explode('-', $index);
        if ($nom[0] == 'AUTORISATION_PAR_MODULE') {
            if ($nom[2] == 'global') {
                $AUTORISATION_PAR_MODULE_list[$nom[1]][$nom[2]] = intval($valeur);
            } else {
                if ($valeur != '') {
                    $valeur = explode(',', str_replace(chr(32), null, $valeur));
                    $AUTORISATION_PAR_MODULE_list[$nom[1]][$nom[2]] = $valeur;
                } else {
                    $AUTORISATION_PAR_MODULE_list[$nom[1]][$nom[2]] = array();
                }

            }
        }
    }
    // var_dump($AUTORISATION_PAR_MODULE_list);
    fwrite($fp, var_export_style($AUTORISATION_PAR_MODULE_list) . ';' . PHP_EOL);

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