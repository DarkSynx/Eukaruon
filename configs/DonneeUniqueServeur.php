<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = 'ef33b99874895d9b4f43d82eb6483fa8d1b9581453092707c6c92f51198cdb77';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'z', '01' => '5', '02' => 'I', '03' => 'N', '04' => 'W', '05' => 'K', '06' => '3', '07' => 'k', '08' => 'P', '09' => 'f', '10' => 'a', '11' => 'p', '12' => '8', '13' => 'E', '14' => 'Y', '15' => 'n', '16' => 'O', '17' => 'J', '18' => 't', '19' => 'u', '20' => 'x', '21' => '7', '22' => 'T', '23' => 'U', '24' => '6', '25' => 'G', '26' => 'i', '27' => 'c', '28' => '2', '29' => 'B', '30' => 'w', '31' => 'm', '32' => 'C', '33' => 'S', '34' => 'M', '35' => 'V', '36' => 'A', '37' => 'D', '38' => 'q', '39' => 'e', '40' => 'X', '41' => 'o', '42' => 'Q', '43' => 'y', '44' => 'l', '45' => '4', '46' => 'g', '47' => 'j', '48' => 's', '49' => '9', '50' => 'L', '51' => 'd', '52' => 'R', '53' => 'h', '54' => 'F', '55' => 'H', '56' => 'b', '57' => '1', '58' => 'r', '59' => '0', '60' => 'v', '61' => 'Z', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'MODULES_UTILISATEUR_INSCRIT',
        3 => 'ALPHACODE',
    );
}