<?php namespace Eukaruon\configs;
/** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
 * Attention les données dans DonneeUniqueServeur ne doivent jamais
 * devenir des données modifiable ou accessible en dehors d'une instanciation
 * il est imperative d'utilisé que des constantes.
 */
class DonneeUniqueServeur
{
    const PHP_VERSION = '80011';
    const IDSERVEUR = '51d59a846ffb3d2c6bdbe6121a3726fccc75727c7c5429845cb7b12cc1682c66';
    const MODULES_UTILISATEUR_INSCRIT = 'Modules_bdd_sqlite';
    const LISTING_VAR = array(
        0 => 'PHP_VERSION',
        1 => 'IDSERVEUR',
        2 => 'MODULES_UTILISATEUR_INSCRIT',
    );
}