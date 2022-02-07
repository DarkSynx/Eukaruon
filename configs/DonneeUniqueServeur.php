<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = '2b5385032b96be06104cd0007ad2ee3a941cced328da9d79ade5493b91b0d1ba';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const ALPHACODE = ['00' => 'W', '01' => 'S', '02' => 'L', '03' => 'Z', '04' => 'R', '05' => '9', '06' => '1', '07' => 'g', '08' => 's', '09' => 'r', '10' => 'O', '11' => 'F', '12' => 'D', '13' => '8', '14' => 'I', '15' => 'h', '16' => '3', '17' => 'y', '18' => 'b', '19' => 'd', '20' => 'e', '21' => 'E', '22' => '7', '23' => 'B', '24' => 'm', '25' => 'X', '26' => 'Q', '27' => 'A', '28' => 'H', '29' => 'K', '30' => 'q', '31' => '2', '32' => 'u', '33' => 'w', '34' => 'z', '35' => 'c', '36' => 'f', '37' => 'M', '38' => 'o', '39' => '4', '40' => 'V', '41' => '6', '42' => 'U', '43' => 'p', '44' => 'k', '45' => 'J', '46' => 'N', '47' => 'j', '48' => 'T', '49' => 'v', '50' => 't', '51' => '0', '52' => '5', '53' => 'l', '54' => 'G', '55' => 'P', '56' => 'a', '57' => 'n', '58' => 'x', '59' => 'Y', '60' => 'C', '61' => 'i', '62' => ';', '63' => '_', '64' => '+', '65' => '#', '66' => '!', '67' => '@', '68' => '|', '69' => '-',];
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'MODULES_UTILISATEUR_INSCRIT',
        3 => 'ALPHACODE',
    );
}