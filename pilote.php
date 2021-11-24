<?php

/** Cette Class est là pour piloté l'application elle se distingue du reste par
 * ce quelle prend la main sur le module Gestionnaire pour facilité son utilisation
 * ainsi que d'autre fonctionnalité en somme pilote est bien là pour exploiter
 *  des fonctionnalités de base
 *  - inclure_fichier
 *  - Modules_gestionnaire
 *  - get_Modules_gestionnaire
 *  - spl_autoload_register
 * TOUTES LES méthode  DANS PILOTE et Modules_Outils COMMENCE PAR UNE MAJUSCULE
 */
class pilote
{
    const NO_RELOAD_MODULE = 0;
    const RELOAD_MODULE = 1;
    const RELOAD_POST_CONSTRUCT = 2;
    /** Représente un tableau des fichiers à charger
     * - chemins.php
     * - journal.php
     * - installation.php
     */
    const MODULES_PRIMAIRE = [
        'chemins.php',
        'journal.php',
        'installation.php'
    ];
    /** Contiendra l'instanciation de Modules_gestionnaire
     * @var null
     */
    protected ?object $Modules_gestionnaire = null;
    /** Contiendra l'instanciation de journal
     * @var journal|null
     */
    protected ?object $journal = null;

    /** Le constructeur acceptera la constante
     * @param null $modules_primaire
     * @param null $forcer_sessionid
     */
    public function __construct(array $modules_primaire = null, string $forcer_sessionid = null)
    {
        if (!is_null($forcer_sessionid)) session_id($forcer_sessionid);

        session_start();


        if (!is_null($modules_primaire)) {
            $this->Inclure_fichier($modules_primaire);
        }

        $this->Modules_gestionnaire = new Modules_gestionnaire();
        $this->journal = new journal();
    }

    /**
     * @param $tableau_fichier
     */
    public function Inclure_fichier($tableau_fichier)
    {
        foreach ($tableau_fichier as $fichier) {
            include_once $fichier;
        }
    }

    public function gestion_url(): string
    {

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https";
        } else {
            $url = "http";
        }
        $url .= "://";
        $url .= @$_SERVER['HTTP_HOST'];
        $url .= @$_SERVER['REQUEST_URI'];

        return $url;
    }

    public function &Utiliser_le_gestionnaire()
    {
        return $this->Modules_gestionnaire;
    }

    /**
     * cette partie permet de charger rapidement un module sans passé par la méthode conventionel :
     * $gestionnaire = $pilote->Modules_gestionnaire();
     * $Modules_Users = $gestionnaire->load_modules('Modules_Users', 'DonneeUniqueServeur');
     * var_dump($Modules_Users->get_IDuser());
     */
    public function &Charger_le_module(
        string $module_a_charger = null,
        mixed  $modules_primaire = null,
        array  $modules_secondaire = null,
        bool   $forcer_recharger_module = self::NO_RELOAD_MODULE
    ): mixed
    {

        if (is_null($module_a_charger)) {
            return $this->Modules_gestionnaire;
        } else {

            if (is_null($modules_primaire)) {
                $this->Ecrire_journal('Chargement module : [ ' . $module_a_charger . ' ]');
                return $this->Modules_gestionnaire->charger_le_module($module_a_charger, $modules_primaire, $modules_secondaire, $forcer_recharger_module);
            } else {
                $this->Ecrire_journal('Chargement module : [ ' . $module_a_charger . ' ] avec options de donnee charger : ' . (is_array($modules_primaire) ? implode(' | ', $modules_primaire) : $modules_primaire));
                return $this->Modules_gestionnaire->charger_le_module($module_a_charger, $modules_primaire, $modules_secondaire, $forcer_recharger_module);
            }
        }
    }


    /**
     * @param $information
     */
    public function Ecrire_journal($information)
    {
        $this->journal->journal($information);
    }
}

spl_autoload_register(/**
 * @param $class_name
 */ function ($class_name) {
    try {
        $portion_nom = explode('_', $class_name);
        $dossier_a_charger = strtolower($portion_nom[0]);
        if ($dossier_a_charger == 'interfaces') {
            include_once INTERFACES . $class_name . '.php';
        } elseif ($dossier_a_charger == 'modules') {

            include_once MODULES . $class_name . '.php';
        } else {
            if ($class_name == 'CMD') {
                include_once CONFIGS . 'CMD.php';
            } elseif ($class_name == 'admin_pilote') {
                include_once CONFIGS . 'admin_pilote.php';
            } else {
                include_once MODULES . $dossier_a_charger . '/' . $class_name . '.php';
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }
});