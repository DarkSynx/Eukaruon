<?php
ini_set('html_errors', false);
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
include REROOT_ADMIN . 'chemins.php';

$administration_outils = new administration_outils();
if ($administration_outils->tester_la_validiter_de_la_demande()) {

    include CHEMIN_SITE . 'chemins.php';
    include CONFIGS . 'Page_en_cache.php';
    // PROFILS
    // PAGES
    $liste_fichier = array();
    $callback = function ($class_fichier) use (&$liste_fichier) {
        $nom = $class_fichier->getBasename();
        if (is_dir(PROFILS . $nom)) {
            $liste_fichier[] = $nom;
        }
    };
    array_map($callback, iterator_to_array(
        new FilesystemIterator(PROFILS, FilesystemIterator::SKIP_DOTS)));

    /*
     * pas de gestion de page simple pour le moment
     * toutes les pages doivent être dans pages/profils
    $callback2 = function ($class_fichier) use (&$liste_fichier) {
        $nom = $class_fichier->getFilename();
        if (!is_dir(PAGES . $nom)) {
            $nom = basename($nom,'.php');
            $liste_fichier[] = $nom;
        }
    };
    array_map($callback2, iterator_to_array(
        new FilesystemIterator(PAGES, FilesystemIterator::SKIP_DOTS)));
    //var_dump($liste_fichier);*/

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($liste_fichier);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array());
}

