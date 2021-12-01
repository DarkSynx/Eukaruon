<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80010';
    const IDSERVEUR = '00fc2ad3796ea6130a9257081ac09be8c9c629b08a84cde6930e29d288ada3ce';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'MODULES_UTILISATEUR_INSCRIT',
    );
}