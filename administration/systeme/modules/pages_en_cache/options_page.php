<?php

use Eukaruon\modules\Modules_pages;

ini_set('html_errors', false);
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
include REROOT_ADMIN . 'chemins.php';

$administration_outils = new administration_outils();
if ($administration_outils->tester_la_validiter_de_la_demande()) {

    include CHEMIN_SITE . 'chemins.php';

    include INTERFACES . 'interfaces_modules.php';
    include MODULES . 'Modules_outils.php';
    include MODULES . 'Modules_pages.php';

    $Modules_pages = new Modules_pages();

    $donnees_serveur = $_POST;
    var_dump($donnees_serveur);

    //securité
    $donnees_serveur['valeur'] = str_replace(['.', '/', '\\'], '', $donnees_serveur['valeur']);

    var_dump($donnees_serveur);

    switch ($donnees_serveur['option']) {
        case 'generer':
            $Modules_pages->preparation_mise_encache($donnees_serveur['valeur']);
            /* Fini la gestion des pages non profile  */
            $page_construite = $Modules_pages->get_profile($donnees_serveur['valeur']);
            $Modules_pages->generer($donnees_serveur['valeur'] . '.html', $page_construite);
            // echo 'Generer!' . PHP_EOL;
            break;
        case 'cache':
            $Modules_pages->mise_en_cache($donnees_serveur['valeur'] . '.html');
            //echo 'Mise en cache!' . PHP_EOL;
            break;
        case 'supprimer':
            @unlink(CACHE . $donnees_serveur['valeur'] . '.html');
            @unlink(GENERER . $donnees_serveur['valeur'] . '.html');
            @delTree(CONTENUS . $donnees_serveur['valeur']);
            //echo 'Supprimer!' . PHP_EOL;
            break;
    }


    echo 'mise à jour terminé ! ';
} else {

    echo 'mise à jour erreur ! ';
}

function delTree($dir): bool
{
    $files = array_diff(@scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? @delTree("$dir/$file") : @unlink("$dir/$file");
    }
    return @rmdir($dir);
}

function var_export_style($valeur): string
{
    return str_replace(
        ['array (', ')', "[\n    ]", "=> \n    [", "[\n]", "=> \n  ["],
        ['[', ']', '[]', "=> [", '[]', "=> ["], var_export($valeur, true));
}