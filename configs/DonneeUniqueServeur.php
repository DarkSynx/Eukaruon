<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = '014b1c5bf194e286b87b85200a86f802c5b79db249b9da051710b99e673235e4';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'x', '01' => '7', '02' => 'l', '03' => '9', '04' => 'z', '05' => 'g', '06' => 'e', '07' => 'U', '08' => 's', '09' => 'b', '10' => '0', '11' => 'V', '12' => 'A', '13' => 'B', '14' => 'f', '15' => 'M', '16' => 'X', '17' => '6', '18' => '2', '19' => '1', '20' => 'D', '21' => 'O', '22' => 'Y', '23' => 'h', '24' => '4', '25' => 'a', '26' => 'E', '27' => 'G', '28' => 'T', '29' => 'm', '30' => 'W', '31' => 'v', '32' => 'C', '33' => 'N', '34' => 't', '35' => '3', '36' => 'P', '37' => 'K', '38' => '8', '39' => 'L', '40' => 'o', '41' => 'r', '42' => 'n', '43' => 'p', '44' => 'I', '45' => 'd', '46' => '5', '47' => 'Z', '48' => 'k', '49' => 'c', '50' => 'i', '51' => 'w', '52' => 'q', '53' => 'y', '54' => 'S', '55' => 'H', '56' => 'J', '57' => 'j', '58' => 'Q', '59' => 'F', '60' => 'u', '61' => 'R', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LIEN_INTERNE_VALIDE = [0 => '/bdd', 1 => '/configs', 2 => '/journaux', 3 => '/modules', 4 => '/modules/interfaces', 5 => '/modules/Level7', 6 => '/modules/Level7/syntaxe', 7 => '/modules/sousmodules', 8 => '/pages', 9 => '/pages/profils', 10 => '/pages/profils/accueil', 11 => '/pages/profils/accueil/html', 12 => '/pages/profils/produits', 13 => '/pages/profils/produits/html', 14 => '/pages/profils/test', 15 => '/passerelle', 16 => '/passerelle/js', 17 => '/ressources', 18 => '/ressources/cache', 19 => '/ressources/contenus', 20 => '/ressources/contenus/accueil', 21 => '/ressources/contenus/accueil/autres', 22 => '/ressources/contenus/accueil/b64', 23 => '/ressources/contenus/accueil/css', 24 => '/ressources/contenus/accueil/img', 25 => '/ressources/contenus/accueil/js', 26 => '/ressources/contenus/accueil/langues', 27 => '/ressources/contenus/accueil/scripts', 28 => '/ressources/contenus/produits', 29 => '/ressources/contenus/produits/autres', 30 => '/ressources/contenus/produits/b64', 31 => '/ressources/contenus/produits/css', 32 => '/ressources/contenus/produits/img', 33 => '/ressources/contenus/produits/js', 34 => '/ressources/contenus/produits/langues', 35 => '/ressources/contenus/produits/scripts', 36 => '/ressources/contenus/test', 37 => '/ressources/contenus/test/autres', 38 => '/ressources/contenus/test/b64', 39 => '/ressources/contenus/test/css', 40 => '/ressources/contenus/test/img', 41 => '/ressources/contenus/test/js', 42 => '/ressources/contenus/test/langues', 43 => '/ressources/contenus/test/scripts', 44 => '/ressources/generer', 45 => '/ressources/temp', 46 => '/ressources/temp/user1023456', 47 => '/ressources/themes', 48 => '/ressources/themes/blue', 49 => '/ressources/themes/grey', 50 => '/ressources/themes/images', 51 => '/users', 52 => '/users/u0', 53 => '/vendor', 54 => '/vendor/composer',];
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'MODULES_UTILISATEUR_INSCRIT',
        3 => 'ALPHACODE',
        4 => 'LIEN_INTERNE_VALIDE',
    );

}