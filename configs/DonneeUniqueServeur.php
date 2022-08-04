<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = 'b5133b736dd83371aa8aea40f804a4c897e8bd63519856c6169718511f4b09e9';
    const SQLITE_ENC = 'ffd17ed656d6d12731277f93f12a8516c55f8eef7c3998668f31c22f9ec318eb02626d6ea2d78a4a0da1537a1acd2361c71db8d579a7f57b47625c857b5d85e1';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'z', '01' => '0', '02' => 'H', '03' => '2', '04' => 'N', '05' => 'f', '06' => 'O', '07' => 'D', '08' => '7', '09' => 'k', '10' => 'P', '11' => 'J', '12' => 'j', '13' => 'R', '14' => '4', '15' => 'g', '16' => '8', '17' => 'B', '18' => 'Z', '19' => 'e', '20' => 'x', '21' => 'q', '22' => 'y', '23' => 'p', '24' => 'Y', '25' => 'r', '26' => 'A', '27' => 't', '28' => 'o', '29' => 'v', '30' => 'K', '31' => 'a', '32' => '6', '33' => 'I', '34' => 'L', '35' => 'm', '36' => 'c', '37' => 'X', '38' => '9', '39' => 'F', '40' => 'h', '41' => 'l', '42' => '1', '43' => 'i', '44' => 'n', '45' => 'T', '46' => 'b', '47' => 'E', '48' => 'w', '49' => 'Q', '50' => 'u', '51' => 's', '52' => 'W', '53' => 'M', '54' => 'V', '55' => 'd', '56' => 'S', '57' => 'G', '58' => '5', '59' => 'C', '60' => '3', '61' => 'U', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LIEN_INTERNE_VALIDE = [0 => '/bdd', 1 => '/bin', 2 => '/configs', 3 => '/docs', 4 => '/docs/classes', 5 => '/docs/css', 6 => '/docs/files', 7 => '/docs/graphs', 8 => '/docs/indices', 9 => '/docs/js', 10 => '/docs/namespaces', 11 => '/docs/packages', 12 => '/docs/PHPdoc', 13 => '/docs/PHPdoc/dev', 14 => '/docs/PHPdoc/ext', 15 => '/docs/PHPdoc/extras', 16 => '/docs/PHPdoc/extras/ssl', 17 => '/docs/PHPdoc/lib', 18 => '/docs/PHPdoc/lib/enchant', 19 => '/docs/reports', 20 => '/erreurs', 21 => '/erreurs/archives', 22 => '/erreurs/logs', 23 => '/journaux', 24 => '/modules', 25 => '/modules/interfaces', 26 => '/modules/Level7', 27 => '/modules/Level7/syntaxe', 28 => '/modules/sousmodules', 29 => '/pages', 30 => '/pages/profils', 31 => '/pages/profils/accueil', 32 => '/pages/profils/exemple', 33 => '/pages/profils/exemple/scripts', 34 => '/pages/profils/produits', 35 => '/pages/profils/produits/html', 36 => '/pages/profils/test', 37 => '/passerelle', 38 => '/passerelle/js', 39 => '/public', 40 => '/ressources', 41 => '/ressources/cache', 42 => '/ressources/contenus', 43 => '/ressources/contenus/accueil', 44 => '/ressources/contenus/accueil/autres', 45 => '/ressources/contenus/accueil/b64', 46 => '/ressources/contenus/accueil/css', 47 => '/ressources/contenus/accueil/img', 48 => '/ressources/contenus/accueil/js', 49 => '/ressources/contenus/accueil/langues', 50 => '/ressources/contenus/accueil/scripts', 51 => '/ressources/contenus/exemple', 52 => '/ressources/contenus/exemple/autres', 53 => '/ressources/contenus/exemple/b64', 54 => '/ressources/contenus/exemple/css', 55 => '/ressources/contenus/exemple/img', 56 => '/ressources/contenus/exemple/js', 57 => '/ressources/contenus/exemple/langues', 58 => '/ressources/contenus/exemple/scripts', 59 => '/ressources/contenus/produits', 60 => '/ressources/contenus/produits/autres', 61 => '/ressources/contenus/produits/b64', 62 => '/ressources/contenus/produits/css', 63 => '/ressources/contenus/produits/img', 64 => '/ressources/contenus/produits/js', 65 => '/ressources/contenus/produits/langues', 66 => '/ressources/contenus/produits/scripts', 67 => '/ressources/contenus/test', 68 => '/ressources/contenus/test/autres', 69 => '/ressources/contenus/test/b64', 70 => '/ressources/contenus/test/css', 71 => '/ressources/contenus/test/img', 72 => '/ressources/contenus/test/js', 73 => '/ressources/contenus/test/langues', 74 => '/ressources/contenus/test/scripts', 75 => '/ressources/generer', 76 => '/ressources/temp', 77 => '/ressources/temp/user1023456', 78 => '/ressources/themes', 79 => '/ressources/themes/blue', 80 => '/ressources/themes/grey', 81 => '/ressources/themes/images', 82 => '/ressources/themes/images/grey', 83 => '/ressources/themes/images/grey/svg', 84 => '/src', 85 => '/src/Controller', 86 => '/tests', 87 => '/tests/modules', 88 => '/tests/modules/Level7', 89 => '/users', 90 => '/users/u0', 91 => '/vendor', 92 => '/vendor/bin', 93 => '/vendor/composer',];
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