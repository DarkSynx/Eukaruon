<?php namespace Eukaruon\configs;

class Modules_autorisations
{
    /* permet d'obtenir la liste de droit d'utilisation et d'autorisation des modules */
    /* les autorisations sont composé de 2 maniéres d'exprimer les autorisation
     * global : le chiffre qui permet de spécifier une autorisation global
     *  0 => pas de restriction
     *  1 => restriction à tout les Modules primaires
     *  2 => restriction pas la liste d'exception
     *  3 => restriction à tout les Modules primaires et restriction pas la liste d'exception
     * exception : permet de spécifier les modules qui ne doivent pas etre charger dans un sous module
     * evidement : MODULE_INTERDIT_AU_SOUSMODULES prime sur AUTORISATION_PAR_MODULE
     * cette class à pour objectif de proposé une sécurité et sera générer et exploité
     * par un Module de votre création ou le module Administration via son interface Web
     * minimal.
     */

    /* les modules si dessous sont considéré comme sensible
     * il vous faudra donc les Charger avant puis récupérer les valeurs
     * la raison pour la quel ils ne sont pas disponible dans les sous-modules
     * c'est par ce qu'il y a une recherche d'herméticité pour assuré la sécurité :
     * 1. ne créé pas un Module primaire pour faire une passerel entre Modules et sousModules
     * 2. Dupliquer pour cantonner l'information des Modules dans des variables pour les sousModules
     * 3. une recherche d'herméticité par et dans des variables pour assuré la sécurité
     * 4. utilisé les Monades et le fonctionnel le plus souvant possible dans les sousModules
     * PS :: le meilleur cas d'utilisation de protection de donnée serait avec le module : Modules_securiser
     */
    const MODULE_INTERDIT_AUX_SOUSMODULES = [
        0 => 'Modules_pages',
        1 => 'Modules_utilisateurs',
    ];
    const AUTORISATION_PAR_MODULE = [
        'sousmodules_test' => [
            'global' => 3,
            'exception' => [
                0 => 'Modules_pages',
                1 => 'Modules_utilisateurs',
            ],
        ],
        'DonneeUniqueServeur' => [
            'global' => 0,
            'exception' => [],
        ],
        'Page_en_cache' => [
            'global' => 0,
            'exception' => [],
        ],
        'Modules_pages' => [
            'global' => 0,
            'exception' => [],
        ],
        'Modules_utilisateurs' => [
            'global' => 0,
            'exception' => [],
        ]
    ];
}