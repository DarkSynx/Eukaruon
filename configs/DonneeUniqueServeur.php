<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = '14a762858c77f6635f57ef242b912b2df84f7e85b0f66ad982683e3c988c2877';
    const SQLITE_ENC = 'c1e47e85692fb7057c0055d929b3590d196e7beed24b55fbca2b094ad1767c2a91db21d1a763402a64bdbdd1eb786debf323cca63f5e9ef1181e237a2384a08b';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'M', '01' => '3', '02' => 'o', '03' => 'd', '04' => 'O', '05' => 'U', '06' => 't', '07' => 'm', '08' => 'a', '09' => 'X', '10' => 'E', '11' => 'C', '12' => 'y', '13' => 'I', '14' => '9', '15' => 'K', '16' => 'F', '17' => 'c', '18' => 'Q', '19' => 'b', '20' => 'P', '21' => '7', '22' => '2', '23' => 'B', '24' => 'L', '25' => '4', '26' => 'Z', '27' => 'h', '28' => 'g', '29' => 'R', '30' => 'v', '31' => '6', '32' => 'x', '33' => 'Y', '34' => 'k', '35' => 'z', '36' => 'j', '37' => 'i', '38' => 'r', '39' => 'H', '40' => 'l', '41' => 's', '42' => 'G', '43' => 'T', '44' => 'A', '45' => 'u', '46' => '5', '47' => 'V', '48' => 'S', '49' => 'D', '50' => 'N', '51' => 'n', '52' => 'W', '53' => '0', '54' => 'p', '55' => 'J', '56' => 'q', '57' => 'w', '58' => '1', '59' => 'f', '60' => '8', '61' => 'e', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LIEN_INTERNE_VALIDE = [0 => '/bdd', 1 => '/bin', 2 => '/configs', 3 => '/docs', 4 => '/docs/classes', 5 => '/docs/css', 6 => '/docs/files', 7 => '/docs/graphs', 8 => '/docs/indices', 9 => '/docs/js', 10 => '/docs/namespaces', 11 => '/docs/packages', 12 => '/docs/PHPdoc', 13 => '/docs/PHPdoc/dev', 14 => '/docs/PHPdoc/ext', 15 => '/docs/PHPdoc/extras', 16 => '/docs/PHPdoc/extras/ssl', 17 => '/docs/PHPdoc/lib', 18 => '/docs/PHPdoc/lib/enchant', 19 => '/docs/reports', 20 => '/erreurs', 21 => '/erreurs/archives', 22 => '/erreurs/logs', 23 => '/journaux', 24 => '/modules', 25 => '/modules/interfaces', 26 => '/modules/Level7', 27 => '/modules/Level7/syntaxe', 28 => '/modules/sousmodules', 29 => '/pages', 30 => '/pages/profils', 31 => '/pages/profils/accueil', 32 => '/pages/profils/exemple', 33 => '/pages/profils/exemple/scripts', 34 => '/pages/profils/produits', 35 => '/pages/profils/produits/html', 36 => '/pages/profils/test', 37 => '/passerelle', 38 => '/passerelle/js', 39 => '/public', 40 => '/ressources', 41 => '/ressources/cache', 42 => '/ressources/contenus', 43 => '/ressources/contenus/accueil', 44 => '/ressources/contenus/accueil/autres', 45 => '/ressources/contenus/accueil/b64', 46 => '/ressources/contenus/accueil/css', 47 => '/ressources/contenus/accueil/img', 48 => '/ressources/contenus/accueil/js', 49 => '/ressources/contenus/accueil/langues', 50 => '/ressources/contenus/accueil/scripts', 51 => '/ressources/contenus/exemple', 52 => '/ressources/contenus/exemple/autres', 53 => '/ressources/contenus/exemple/b64', 54 => '/ressources/contenus/exemple/css', 55 => '/ressources/contenus/exemple/img', 56 => '/ressources/contenus/exemple/js', 57 => '/ressources/contenus/exemple/langues', 58 => '/ressources/contenus/exemple/scripts', 59 => '/ressources/contenus/produits', 60 => '/ressources/contenus/produits/autres', 61 => '/ressources/contenus/produits/b64', 62 => '/ressources/contenus/produits/css', 63 => '/ressources/contenus/produits/img', 64 => '/ressources/contenus/produits/js', 65 => '/ressources/contenus/produits/langues', 66 => '/ressources/contenus/produits/scripts', 67 => '/ressources/contenus/test', 68 => '/ressources/contenus/test/autres', 69 => '/ressources/contenus/test/b64', 70 => '/ressources/contenus/test/css', 71 => '/ressources/contenus/test/img', 72 => '/ressources/contenus/test/js', 73 => '/ressources/contenus/test/langues', 74 => '/ressources/contenus/test/scripts', 75 => '/ressources/formulaires', 76 => '/ressources/generer', 77 => '/ressources/temp', 78 => '/ressources/temp/user1023456', 79 => '/ressources/themes', 80 => '/ressources/themes/blue', 81 => '/ressources/themes/grey', 82 => '/ressources/themes/images', 83 => '/ressources/themes/images/grey', 84 => '/ressources/themes/images/grey/svg', 85 => '/src', 86 => '/src/Controller', 87 => '/tests', 88 => '/tests/modules', 89 => '/tests/modules/Level7', 90 => '/users', 91 => '/users/u0', 92 => '/vendor', 93 => '/vendor/bin', 94 => '/vendor/composer',];
    const BDD_INSCRIPTION = 'inscription.db';
    const BDD_INSCRIPTION_TABLE = 'inscription';
    const BDD_IP = 'ip.db';
    const BDD_IP_TABLE = 'ip';
    const BDD_UTILISATEURS = 'utilisateurs.db';
    const BDD_UTILISATEURS_TABLE = 'utilisateurs';
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'SQLITE_ENC',
        3 => 'MODULES_UTILISATEUR_INSCRIT',
        4 => 'ALPHACODE',
        5 => 'LIEN_INTERNE_VALIDE',
        6 => 'BDD_INSCRIPTION',
        7 => 'BDD_INSCRIPTION_TABLE',
        8 => 'BDD_IP',
        9 => 'BDD_IP_TABLE',
        10 => 'BDD_UTILISATEURS',
        11 => 'BDD_UTILISATEURS_TABLE',
    );
}