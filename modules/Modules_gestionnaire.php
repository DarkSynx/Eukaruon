<?php namespace Eukaruon\modules;

use Eukaruon\configs\admin_pilote;
use Eukaruon\configs\CMD;
use Eukaruon\configs\Modules_autorisations;
use Exception;
use FilesystemIterator;


/** module principale de gestion des autres modules
 *  - set_DonneeUniqueServeur
 *  - set_Modules_bdd
 *  - set_Modules_bdd
 *  - get_DonneeUniqueServeur
 *  - get_list_modules
 *  - load_modules
 *  - chargement_donne_choisi
 */
class Modules_gestionnaire
{

    /** Va contenir un tableu avec la liste des fichiers du dossier ./modules
     *  qui commence que par 'Modules_' et ne contiendra pas Module_gestionnaire
     * @var array
     */
    protected array $list_modules_instancier = array();
    /**
     * @var array
     */
    protected array $tableau_contenant_donnes = array();
    // protected array $Modules_charger = array();


    /**
     * @var string
     */
    protected string $prefix_constante = 'MGC_';

    /**
     * @var int|float
     */
    protected int|float $valeur_drapeau_incrementer = 0.5;
    /**
     * @var array
     */
    protected array $tableau_drapeau = array();
    /**
     * @var string
     */
    private string $nom_module = '';

    /**
     * @var array
     */
    private array $_liste_exception_module = array();
    /**
     * @var array
     */
    private array $_liste_espace_de_nom = array();

    /** Le constructeur démarre les modules essentiels au bon fonctionnement
     *  chaque module essentiel dit primaire devra être rajouté à la main. Ils sont
     *  considérés comme des modules sensibles et ne peuvent donc pas être chargé automatiquement
     *  - une methode set_[nomdumodule] devra être créé pour charger celui-ci et une variable
     *  - une variable aussi : "protected ?object $Modules_[NOMMODULE] = null;" à null devra être définit
     *
     *  - les modules essentiel sont ceux correspondent aux modules primaires de cette application
     *  - les autres modules sont chargers à la demande
     */
    public function __construct(array $liste_exception_module = array(), array $liste_name_space = array())
    {

        $this->_liste_exception_module = $liste_exception_module;
        $this->_liste_espace_de_nom = $liste_name_space;

        $this->set_DonneeUniqueServeur();
        $this->set_Page_en_cache();
        $this->set_Modules_bdd();
        $this->set_Modules_Level7();
        $this->set_Modules_autorisations();


        /* doit être générer coté Administration en dev vous pouvez l'activer */
        /* la gestion des modules est administrer par /configs/CMD.php */
        $this->generer_list_modules();

        // on fabrique le drapeau MGC_AUTRES_MODULES pour indiqué que l'on peut attaquer le dossier
        // autres_modules pour y charger des modules secondaire c'est un indicateur à mettre dans
        //
        //$this->generer_drapeau_tableau('AUTRES_MODULES');

        // ajouter le gestionnaire de sous module : Module_sousmodule
    }

    /** On instancie ici les données de ./configs/DonneeUniqueServeur.php
     * attention DonneeUniqueServeur.php n'est créé qu'a l'installation du serveur via
     * installation.php et DonneeUniqueServeur.php sera misà jours par la suite vie des
     * Applicatifs coté ./administration
     * ici nous avons besoin d'utiliser ces données unique et essentiel au serveur
     */
    public function set_DonneeUniqueServeur()
    {
        $this->generer_drapeau_tableau('DonneeUniqueServeur');
        include_once CONFIGS . 'DonneeUniqueServeur.php';
        $this->liste_modules_instance($this->name_space('configs') . 'DonneeUniqueServeur', true);
    }

    /**
     * instantiation de Page_en_cache
     */
    protected function set_Page_en_cache()
    {
        $this->generer_drapeau_tableau('Page_en_cache');
        include_once CONFIGS . 'Page_en_cache.php';
        $this->liste_modules_instance($this->name_space('configs') . 'Page_en_cache', true);
    }

    /** Permet de charger le module de gestion des bases de donnée
     *
     */
    public function set_Modules_bdd()
    {
        $this->generer_drapeau_tableau('Modules_bdd');
        $this->liste_modules_instance($this->name_space('modules') . 'Modules_bdd', true);
        //$this->Modules_bdd->set_selection_module_bdd('Modules_bdd_sqlite');
    }

    /** Permet de charger le module Level7
     *
     */
    public function set_Modules_Level7()
    {
        $this->generer_drapeau_tableau('Modules_Level7');
        $this->liste_modules_instance($this->name_space('modules') . 'Modules_Level7', true);
        //$this->Modules_bdd->set_selection_module_bdd('Modules_bdd_sqlite');
    }


    /**
     * Permet de charger le module Modules_autorisations
     */
    protected function set_Modules_autorisations()
    {
        $this->generer_drapeau_tableau('Modules_autorisations');
        include_once CONFIGS . 'Modules_autorisations.php';
        $this->liste_modules_instance($this->name_space('configs') . 'Modules_autorisations', true);
    }

    /* ----------------------------------- */

    /** recuperer la liste des espaces de nom
     * @param $nom
     * @return mixed
     */
    public function name_space($nom)
    {
        return $this->_liste_espace_de_nom[$nom];
    }

    /**
     * permet de generer 63 drapeau pour controler 63 modules
     * par la méthode module1 | module2 | module3 ...
     * @param $nom_module
     * @return int
     */
    public function generer_drapeau_tableau($nom_module): int
    { // les modules primaire sont limité à 63 modules
        $this->valeur_drapeau_incrementer = intval($this->valeur_drapeau_incrementer * 2);
        if ($this->valeur_drapeau_incrementer <= pow(2, 63)) {
            $this->tableau_drapeau[$this->valeur_drapeau_incrementer] = $nom_module;
            $prefix_module_nom = $this->prefix_constante . strtoupper($nom_module);
            if (!defined($prefix_module_nom))
                define($prefix_module_nom, $this->valeur_drapeau_incrementer);
        }

        return $this->valeur_drapeau_incrementer;
    }

    /** permet d'obtenir la liste des modules d'instance
     * @param $nom_du_module
     * @param false $force
     * @param null $donnees_module
     * @param false $force_post_construct
     * @return mixed
     */
    protected function &liste_modules_instance($nom_du_module, $force = false, &$donnees_module = null, $force_post_construct = false): mixed
    {
        $nom_du_module_repertorier = basename($nom_du_module);

        if (
            $nom_du_module != 'AUTRES_MODULES' &&
            ($force ||
                is_null($this->list_modules_instancier[$nom_du_module])
            )
        ) {
            if (!is_null($donnees_module)) {

                if (in_array($nom_du_module, $this->_liste_exception_module)) {
                    echo 'yes >> ', $nom_du_module, PHP_EOL;
                    $nom_du_module = $this->name_space('configs') . $nom_du_module;
                } else {
                    $portion_nom = explode('_', $nom_du_module);
                    $dossier_a_charger = strtolower($portion_nom[0]);
                    if ($dossier_a_charger == 'modules') {
                        $nom_du_module = $this->name_space('modules') . $nom_du_module;
                    } elseif ($dossier_a_charger == 'sousmodules') {
                        $nom_du_module = $this->name_space('sousmodules') . $nom_du_module;
                    }
                }
                $this->list_modules_instancier[$nom_du_module_repertorier]['donnee'] = &$donnees_module;
                $this->list_modules_instancier[$nom_du_module_repertorier]['module'] = new $nom_du_module($donnees_module, $force_post_construct);
                $this->parent_et_interfaces($nom_du_module);
            } else {

                /* pas oublier de retirer les here 1,2,3
                et gerer les fichier dans configs*/
                if (in_array($nom_du_module, $this->_liste_exception_module)) {

                    $nom_du_module = $this->name_space('configs') . $nom_du_module;
                } else {
                    $portion_nom = explode('_', $nom_du_module);
                    $dossier_a_charger = strtolower($portion_nom[0]);
                    if ($dossier_a_charger == 'modules') {
                        $nom_du_module = $this->name_space('modules') . $nom_du_module;
                    } elseif ($dossier_a_charger == 'sousmodules') {
                        $nom_du_module = $this->name_space('sousmodules') . $nom_du_module;
                    }
                }
                $this->list_modules_instancier[$nom_du_module_repertorier]['module'] = new $nom_du_module(null, $force_post_construct);
                $this->parent_et_interfaces($nom_du_module);
            }
            return $this->list_modules_instancier[$nom_du_module_repertorier]['module'];
        }

        return $this->list_modules_instancier[$nom_du_module_repertorier]['module'];
    }

    /** vérifie si le module exploité
     *  est fils à module_outils et comporte bien
     *  son interface; valable seulement pour les
     *  modules utilisé en dehors des pages réaliser
     *  par exemple dans index.php
     * @param $nom_du_module
     */
    public function parent_et_interfaces($nom_du_module)
    {
        $nom_du_module_repertorier = basename($nom_du_module);
        // créé une erreur si l'objet n'est pas charger
        if (strpos($nom_du_module, '_')) {
            try {
                $nom_eclater = explode('_', $nom_du_module);
                $interfaces_modules = class_implements($this->list_modules_instancier[$nom_du_module_repertorier]['module']);
                $Modules_outils = get_parent_class($this->list_modules_instancier[$nom_du_module_repertorier]['module']);

                // voir si c'est pas possible de le mettre en début de class
                $list_modules_exceptions = [
                    'DonneeUniqueServeur',
                    'Page_en_cache',
                    'Modules_bdd',
                    'Modules_autorisations'
                ];

                $list_tagmodules_acepter = ['Modules' /*, 'sousmodules'*/];
                if (
                    !in_array($nom_du_module, $list_modules_exceptions) &&
                    in_array($nom_eclater[0], $list_tagmodules_acepter)
                    && (
                        /* les interfaces */
                        !in_array('interfaces_modules', $interfaces_modules) ||
                        /* les modules */
                        $Modules_outils != 'Modules_outils'
                    )
                ) {

                    throw new Exception(
                        'Erreur => [ ' . $nom_du_module . ' ] ' . PHP_EOL .
                        ($interfaces_modules ? '' : 'manque l\'interface : "interfaces_modules"') . PHP_EOL .
                        ($Modules_outils ? '' : 'manque le module : "Modules_outils"') . PHP_EOL .
                        'class ' . $nom_du_module . ' extends Modules_outils  { }' . PHP_EOL
                    );


                }
            } catch
            (Exception $e) {
                echo 'FATAL::' . $e->getMessage();
                exit;
            }
        }
    }


    /** Permet d'obtenir la liste des modules disponible
     * @return array
     */
    public function generer_list_modules(): array
    {
        $callback = function ($class_fichier) {
            $nom_fichier = $class_fichier->getBasename('.php');
            $tableau_nom_fichier_eclater = explode('_', $nom_fichier);
            $modules_exception = ['Modules_outils', 'Modules_gestionnaire'];
            if (
                !in_array($nom_fichier, $modules_exception) &&
                $tableau_nom_fichier_eclater[0] == 'Modules'
            ) {
                $this->list_modules_instancier[$nom_fichier] = null;
                $this->generer_drapeau_tableau($nom_fichier);
            }
            // var_dump($this->list_modules_instancier);
        };
        if (admin_pilote::REGISTRE['recherche_automatique_modules'] === '0' ||
            admin_pilote::REGISTRE['recherche_automatique_modules'] === 0 ||
            admin_pilote::REGISTRE['recherche_automatique_modules'] === false
        ) {
            foreach (CMD::CMD_LISTE as $valeur) {
                $nom_fichier = constant('Eukaruon\\configs\\CMD::' . $valeur);
                $this->list_modules_instancier[$nom_fichier] = null;
                $this->generer_drapeau_tableau($nom_fichier);
            }

        } else {
            array_map($callback, iterator_to_array(
                new FilesystemIterator(MODULES, FilesystemIterator::SKIP_DOTS)));
        }
        return $this->list_modules_instancier;
    }

    /** Permet d'obtenir l'accés à l'objet DonneeUniqueServeur par référence
     * @return mixed
     */
    public function &get_DonneeUniqueServeur(): mixed
    {
        return $this->list_modules_instancier['DonneeUniqueServeur']['module'];
    }

    /** Permet l'instanciation d'un module
     * @param $nom_module
     * @param null $modules_primaire
     * @return mixed
     */
    public function &charger_le_module($nom_module, $modules_primaire = null, $modules_secondaire = null, int $forcer_recharger_module = 0, $force_post_construct = false): array|object
    {
        //  $forcer_recharger_module = 0 => on ne fait rien
        //  $forcer_recharger_module = 1 => on réinstancie le module
        //  $forcer_recharger_module = 2 => on réinstancie pas le module on relance post_construct

        $donnee = null;
        /* sauvgarde temporaire du nom du module */
        $this->nom_module = $nom_module;

        /* partie ou l'on instancie le module avec ces modules utilitaire */
        if ($forcer_recharger_module === 1 || !array_key_exists($nom_module, $this->list_modules_instancier) || is_null($this->list_modules_instancier[$nom_module])) {
            if (!is_null($modules_primaire)) {
                $donnee = &$this->chargement_donnee_choisi($modules_primaire, $modules_secondaire);
            }
            //$this->Modules_charger[$nom_module]['module'] = new $nom_module($this->Modules_charger[$nom_module]['donnee']);

            return $this->liste_modules_instance($nom_module, true, $donnee, $force_post_construct);
        }

        /* partie ou l'on redémarre le post_construct du module avec ces modules utilitaire */
        if (!is_null($modules_primaire)) {
            $donnee = &$this->chargement_donnee_choisi($modules_primaire, $modules_secondaire);
            if ($forcer_recharger_module === 2) {
                $this->list_modules_instancier[$nom_module]['module']->post_construct($donnee);
            } else {
                $this->list_modules_instancier[$nom_module]['module']->charger_donnee_gestionnaire($donnee);
            }
        }

        /* on retourne le module module pour l'exploité par référence */
        return $this->liste_modules_instance($nom_module, false, $donnee, $force_post_construct);
    }

    /** Cette section permet de charger les données que possède Module_gestionnaire par
     * exemple comme il charge les donnée DonneeUniqueServeur si dans un autre module on à besoin des données pour
     * travailler avec nous pouvons alors les charger ici , les donnée sont partager par référence pour évité la
     * multiplication des données.
     * - pour proposé des données vous devez connaitre ces données et comment les utilise comme par exemple :
     * - 'DonneeUniqueServeur' ou 'Modules_bdd'
     * - si je désire avoir les deux modules charger alors je représente comme cela 'DonneeUniqueServeur | Modules_bdd'
     * avec un '|' comme séparateur
     * - vous pouvez aussi représenter les données dans un tableau ['DonneeUniqueServeur',' Modules_bdd']
     * @param mixed $modules_primaire
     * @return array
     */
    public function &chargement_donnee_choisi(mixed $modules_primaire, array $modules_secondaire = null): array
    {
        $donnee_tableau = array();
        $this->tableau_contenant_donnes = array();
        if (!is_null($modules_primaire)) {

            if (is_array($modules_primaire)) {
                $donnee_tableau = $modules_primaire;
            } /*
            // le gestionnaire de drapeau ne gére de 66 modules max
            // à n'activer que si vous en avez une utilité
            /*
             *  $Modules_pages = $pilote->Charger_le_module(
             *      module_a_charger: 'Modules_pages',
             *      modules_primaire: MGC_PAGE_EN_CACHE | MGC_MODULES_BDD <-- ici
             *  );
             * /
            elseif (is_int($modules_primaire)) {
                $donnee_tableau = $this->binaire_extraction_drapeau($modules_primaire);
                // $donnee_tableau =
            }
            */
            elseif (is_string($modules_primaire)) {
                $donnee_tableau = explode('|', $modules_primaire);
            }

            if (array_key_exists('AUTRES_MODULES', $this->tableau_contenant_donnes) && !is_null($modules_secondaire)) {
                unset($this->tableau_contenant_donnes['AUTRES_MODULES']);
                $donnee_tableau = array_merge($donnee_tableau, $modules_secondaire);
            }

            /* gestion des autorisations : Modules_autorisations */
            if (strpos($this->nom_module, '_')) {
                $nom_module = explode('_', $this->nom_module);
                if ($nom_module[0] == 'sousmodules') {
                    var_dump($this->nom_module);
                    $autorisation_mias = Modules_autorisations::MODULE_INTERDIT_AUX_SOUSMODULES;
                    $autorisatio_pm = Modules_autorisations::AUTORISATION_PAR_MODULE;

                    $callback0 = function ($donnee) use ($autorisation_mias, $donnee_tableau) {
                        try {
                            if (in_array($donnee, $donnee_tableau)) {
                                throw new Exception(
                                    'Erreur => [ ' . $this->nom_module . ' ] ' . PHP_EOL .
                                    'Par sécurité, ce module : [ ' . $donnee . ' ] Il ne peut pas être exploité ' . PHP_EOL .
                                    'Liste des modules non autorisée :' . PHP_EOL . '-' .
                                    implode(PHP_EOL . '-', $autorisation_mias) . PHP_EOL
                                );
                            }
                        } catch
                        (Exception $e) {
                            echo 'FATAL::' . $e->getMessage();
                            exit;
                        }
                    };

                    $callback1 = function ($donnee) use ($autorisatio_pm, $donnee_tableau) {
                        try {
                            if (in_array($donnee, $donnee_tableau)) {
                                throw new Exception(
                                    'Erreur => [ ' . $this->nom_module . ' ] ' . PHP_EOL .
                                    'Par sécurité, ce module : [ ' . $donnee . ' ] Il ne peut pas être exploité ' . PHP_EOL .
                                    'Liste des modules non autorisée :' . PHP_EOL . '-' .
                                    implode(PHP_EOL . '-', $autorisatio_pm[$this->nom_module]['exception']) . PHP_EOL
                                );
                            }
                        } catch
                        (Exception $e) {
                            echo 'FATAL::' . $e->getMessage();
                            exit;
                        }
                    };


                    if (array_key_exists($this->nom_module, $autorisatio_pm)) {
                        switch ($autorisatio_pm[$this->nom_module]['global']) {
                            case 1:
                                // si le module est dans les autorisations 1
                                // on accorde 1 par défaut dans l'administration
                                // zero est réservé à l'ajout automatisé durant une installation
                                // quand vous ouvez le menu d'administration de gestion des autorisatioins
                                // tout les modules à zero passe à 1 c'est une traça bilité d'évenement
                                array_map($callback0, $autorisation_mias);
                                break;
                            case 2:
                                // on definit ici que seul les exceptions comptes dans AUTORISATION_PAR_MODULE
                                array_map($callback1, $autorisatio_pm[$this->nom_module]['exception']);
                                break;
                            case 3:
                                // ici on active les exceptions dans AUTORISATION_PAR_MODULE et
                                // MODULE_INTERDIT_AUX_SOUSMODULES
                                array_map($callback0, $autorisation_mias);
                                array_map($callback1, $autorisatio_pm[$this->nom_module]['exception']);
                                break;
                        }
                    } else {
                        // si le module n'est pas dans les autorisations on vérifie au moins
                        // MODULE_INTERDIT_AUX_SOUSMODULES
                        // c'est une sécurité obligatoire minimal
                        array_map($callback0, $autorisation_mias);
                    }
                }

            }

            $callback = function ($donnee) {
                $donnee = trim($donnee);
                $this->liste_modules_instance($donnee);
                $this->tableau_contenant_donnes[$donnee] = &$this->list_modules_instancier[$donnee]['module'];
            };
            array_map($callback, $donnee_tableau);

        }
        return $this->tableau_contenant_donnes;
    }

    /** fonction là pour recuprer la valeur d'un drapeau
     * @param int $valeur_combiner_decimal
     * @return array
     */
    function binaire_extraction_drapeau(int $valeur_combiner_decimal): array
    {
        $taille = strlen(decbin($valeur_combiner_decimal));
        $tableau_de_valeur = array();
        for ($inc = 0; $inc < $taille; $inc++) {
            $bdc = 1 << $inc;
            if (($valeur_combiner_decimal & $bdc) == $bdc) {
                $tableau_de_valeur[] = $this->tableau_drapeau[$bdc];
            }
        }
        return $tableau_de_valeur;
    }

    /** permet d'utiliser un module charger
     * @param string $nom_module
     * @return mixed
     */
    public function &Utiliser_le_module(string $nom_module)
    {
        return $this->list_modules_instancier[$nom_module]['module'];
    }
}