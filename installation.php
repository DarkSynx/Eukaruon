<?php namespace Eukaruon;


/* Fabrication des unique du serveur */

$cstrong = false;
while (!$cstrong) $bytes = openssl_random_pseudo_bytes(32, $cstrong);


$donnees_serveur = [
    'PHP_VERSION' => (PHP_VERSION_ID >= 80000 ? PHP_VERSION_ID : 'ERROR_VERSION_PAS_PHP8_OU_SUPERIEUR'),
    'IDSERVEUR' => bin2hex($bytes),
    'MODULES_UTILISATEUR_INSCRIT' => 'Modules_bdd_sqlite',


];


$DUSStream = fopen(CONFIGS . 'DonneeUniqueServeur.php', 'w');

$attention = <<<INFOS
    /** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
    * Attention les données dans DonneeUniqueServeur ne doivent jamais 
    * devenir des données modifiable ou accessible en dehors d'une instanciation
    * il est imperative d'utilisé que des constantes. 
    */
INFOS;

fwrite($DUSStream, '<?php namespace Eukaruon\\configs; ' . PHP_EOL . $attention . PHP_EOL . 'class DonneeUniqueServeur {' . PHP_EOL);
foreach ($donnees_serveur as $cle => $valeurs) {
    fwrite($DUSStream, 'const ' . $cle . ' = \'' . $valeurs . '\';' . PHP_EOL);
}

fwrite($DUSStream, 'const LISTING_VAR = ' . var_export(array_keys($donnees_serveur), true) . ';' . PHP_EOL);

/*
fwrite($DUSStream, 'public function get_linsting_var() { return  self::LISTING_VAR; }' . PHP_EOL);

foreach ($donnees_serveur as $cle => $valeurs) {
    fwrite($DUSStream, 'public function get_' . $cle . '() { return  self::' . $cle . '; }' . PHP_EOL);
}
*/

fwrite($DUSStream, '}');
fclose($DUSStream);