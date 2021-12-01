<?php
ini_set('html_errors', false);
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
include REROOT_ADMIN . 'chemins.php';

$administration_outils = new administration_outils();
if ($administration_outils->tester_la_validiter_de_la_demande()) {

    include CHEMIN_SITE . 'chemins.php';
    //include CONFIGS . 'Page_en_cache.php';

    $donnees_serveur = $_POST;

    $attention = <<<INFOS
/** 
 * CE FICHIER EST GERER ET RECREE PAR 
 * L'ADMINISTRATION 
 */
INFOS;

    $list = array_flip($donnees_serveur);
    var_dump($list);


    $fp = fopen(CONFIGS . 'Page_en_cache.php', 'w');

    fwrite($fp, '<?php namespace Eukaruon\\configs;' . PHP_EOL . 'class Page_en_cache {' . PHP_EOL . $attention . PHP_EOL);

    fwrite($fp, 'protected array $page_en_cache = ');
    fwrite($fp, var_export_style($list) . ';' . PHP_EOL);

    fwrite($fp, 'public function get_page_en_cache() { return $this->page_en_cache; }' . PHP_EOL . PHP_EOL);

    fwrite($fp, '}');

    fclose($fp);


    //header('Content-Type: application/json; charset=utf-8');
    //echo json_encode($liste_fichier);
    echo 'mise à jour terminé ! ';
} else {
    //header('Content-Type: application/json; charset=utf-8');
    //echo json_encode(array());
    echo 'mise à jour erreur ! ';
}

function var_export_style($valeur): string
{
    return str_replace(
        ['array (', ')', "[\n    ]", "=> \n    [", "[\n]", "=> \n  ["],
        ['[', ']', '[]', "=> [", '[]', "=> ["], var_export($valeur, true));
}