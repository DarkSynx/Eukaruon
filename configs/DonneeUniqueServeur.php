<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = '8ec9fe82a7ed485060342a3a0c3926e0e493ea639dd3eba9be7e111ad5274550';
    const SQLITE_ENC = 'd2465bda02ec836569991d1d7c51ba6471adfad2228d1ea0ff6558d4e5333676447668ec8a4c6681128b0c86f692b97f8744be1fb23050214fdbb5ddf16f19db';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'E', '01' => 's', '02' => 'm', '03' => 'L', '04' => '7', '05' => 'R', '06' => 'Y', '07' => 'v', '08' => 'A', '09' => 'N', '10' => 'b', '11' => 'O', '12' => 'q', '13' => 'V', '14' => '8', '15' => 'W', '16' => 'r', '17' => 'S', '18' => 'T', '19' => 'K', '20' => 'B', '21' => 'M', '22' => '9', '23' => 'w', '24' => 'X', '25' => '1', '26' => 'U', '27' => 'F', '28' => 'h', '29' => 'C', '30' => 'z', '31' => 'e', '32' => '2', '33' => 'f', '34' => 'Z', '35' => 'J', '36' => 'o', '37' => 'l', '38' => '5', '39' => 'Q', '40' => 'I', '41' => 'G', '42' => 'k', '43' => 'd', '44' => 'x', '45' => 'g', '46' => 'P', '47' => 'a', '48' => 'y', '49' => 'H', '50' => '4', '51' => '0', '52' => 'c', '53' => 'p', '54' => 'n', '55' => 't', '56' => '6', '57' => 'u', '58' => 'i', '59' => 'j', '60' => 'D', '61' => '3', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LIEN_INTERNE_VALIDE = [0 => '/bdd', 1 => '/configs', 2 => '/erreurs', 3 => '/erreurs/archives', 4 => '/erreurs/logs', 5 => '/journaux', 6 => '/modules', 7 => '/modules/interfaces', 8 => '/modules/Level7', 9 => '/modules/Level7/syntaxe', 10 => '/modules/sousmodules', 11 => '/pages', 12 => '/pages/profils', 13 => '/pages/profils/accueil', 14 => '/pages/profils/exemple', 15 => '/pages/profils/exemple/scripts', 16 => '/pages/profils/produits', 17 => '/pages/profils/produits/html', 18 => '/pages/profils/test', 19 => '/passerelle', 20 => '/passerelle/js', 21 => '/ressources', 22 => '/ressources/cache', 23 => '/ressources/contenus', 24 => '/ressources/contenus/accueil', 25 => '/ressources/contenus/accueil/autres', 26 => '/ressources/contenus/accueil/b64', 27 => '/ressources/contenus/accueil/css', 28 => '/ressources/contenus/accueil/img', 29 => '/ressources/contenus/accueil/js', 30 => '/ressources/contenus/accueil/langues', 31 => '/ressources/contenus/accueil/scripts', 32 => '/ressources/contenus/exemple', 33 => '/ressources/contenus/exemple/autres', 34 => '/ressources/contenus/exemple/b64', 35 => '/ressources/contenus/exemple/css', 36 => '/ressources/contenus/exemple/img', 37 => '/ressources/contenus/exemple/js', 38 => '/ressources/contenus/exemple/langues', 39 => '/ressources/contenus/exemple/scripts', 40 => '/ressources/contenus/produits', 41 => '/ressources/contenus/produits/autres', 42 => '/ressources/contenus/produits/b64', 43 => '/ressources/contenus/produits/css', 44 => '/ressources/contenus/produits/img', 45 => '/ressources/contenus/produits/js', 46 => '/ressources/contenus/produits/langues', 47 => '/ressources/contenus/produits/scripts', 48 => '/ressources/contenus/test', 49 => '/ressources/contenus/test/autres', 50 => '/ressources/contenus/test/b64', 51 => '/ressources/contenus/test/css', 52 => '/ressources/contenus/test/img', 53 => '/ressources/contenus/test/js', 54 => '/ressources/contenus/test/langues', 55 => '/ressources/contenus/test/scripts', 56 => '/ressources/generer', 57 => '/ressources/temp', 58 => '/ressources/temp/user1023456', 59 => '/ressources/themes', 60 => '/ressources/themes/blue', 61 => '/ressources/themes/grey', 62 => '/ressources/themes/images', 63 => '/ressources/themes/images/grey', 64 => '/ressources/themes/images/grey/svg', 65 => '/tests', 66 => '/tests/modules', 67 => '/tests/modules/Level7', 68 => '/users', 69 => '/users/u0', 70 => '/vendor', 71 => '/vendor/composer',];
    const BDD_INSCRIPTION = 'inscription.db';
    const BDD_IP = 'ip.db';
    const BDD_UTILISATEURS = 'utilisateurs.db';
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'SQLITE_ENC',
        3 => 'MODULES_UTILISATEUR_INSCRIT',
        4 => 'ALPHACODE',
        5 => 'LIEN_INTERNE_VALIDE',
        6 => 'BDD_INSCRIPTION',
        7 => 'BDD_IP',
        8 => 'BDD_UTILISATEURS',
    );
}