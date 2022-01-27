<?php namespace Eukaruon\modules;

use FilesystemIterator;

/** Ce module à pour objectif la gestion des pages il gérera donc aussi bien la construction de profils
 * que l'affichage de ceux si
 * - set_identifiant_utilisateur
 * - preparer_page
 * - get_profile
 * - construction_page
 * - mise_en_cache
 * - preparation_mise_encache
 * - copyer_fichiers_dans
 * - cedossier_existe_il
 * - generer
 * - recuperer_generer
 * - recuperer_cache
 * - get_page_specifique
 * - get_verification_clee_authentification_temporaire
 * - set_actualisation_temps_restant
 * - get_verifier_page_specifique_demander
 * - charger_page_valider
 */
class Modules_pages extends Modules_outils
{
    /** Verification de la clee d'authentification temporaire
     * @var null
     */
    protected ?string $verification_clee = null;

    /** Contiendra le temps restant en TimeStamp
     * @var null
     */
    protected ?int $temps_restant = null;

    /** Contient la dernier page spécifique connu de l'utilisateur
     * @var int
     */
    protected ?int $page_specifique = -1;

    /** Identifiant utilisateur
     * @var null
     */
    protected ?string $identifiant_utilisateur = null;

    /** Ici sera stocker temporairement le nom de la variable à charger
     * si vous voulez faire d'autre vérification d'avant d'afficher la page
     * elle est contenu ici avant d'etre envoyer dans $charger_nom_page_valider
     * utilise la methode charger_page_valider
     * @var null
     */
    protected ?string $charger_nom_page_non_valider = null;
    /** s'utilise avec $charger_nom_page_non_valider et permet une fois rempli d'être
     * utilisé comme variable qui contient le nom de la page si évidement la valeur est
     * null ou -1 alors vous renvoyez à la page principal accueil
     * @var null
     */
    protected ?string $charger_nom_page_valider = null;
    //protected $donnee_gestionnaire = array();


    //--------------------------------------------------------


    // Attention post_construct est là pour nous éviter de réinstancier l'objet inutilement
    // donc comme l'objet est déjà instancier vous pouvez relancer les fonction de __construct dans
    // post_construct
    public function post_construct(&$donnee_gestionnaire)
    {
        $this->Ajouter_donnee_dans_gestionnaire($donnee_gestionnaire);
        // pour charger une donnée voir avec load_donnee_gestionnaire();
    }

    /** Définit l'identifiant utilisateur
     * @param $identifiant_utilisateur
     */
    public function set_identifiant_utilisateur($identifiant_utilisateur)
    {
        $this->identifiant_utilisateur = $identifiant_utilisateur;
    }


    /** Permet de préparer la Page par rapport au Cookies de l'utilisateur s'il a
     * une clé d'authentification mais aussi une page spécifique définit
     * sinon il est renvoyer en page d'acceuil
     * @param bool $page_authentification
     * @param string $clee_authentification_temporaire
     * @param int $page_specifique_demander
     */
    public function &preparer_page(bool $get_utilisateur_bdd_ok, int $page_specifique_demander = -1): int
    {
        $this->page_specifique = ($get_utilisateur_bdd_ok ? $page_specifique_demander : -1);
        return $this->page_specifique;
    }

    /** Permet de récuperer le contenu de la page créé dans generer
     * pour en suite l'utiliser dans l'application qui affiche la page
     * à l'utilisateur
     * @param string $nom_page
     * @return false|string
     */
    public function recuperer_cache(string $nom_page): bool|string
    {
        //return file_get_contents($page);
        return CACHE . $nom_page . '.html.php';
    }

    public function affichage($page)
    {
        header('Content-type: text/html; charset=utf-8');
        include_once($page);
        // die();
    }

    public function afficher_la_page(?int $numero_de_page = null): bool|string
    { // par défault vide va renvoyer à l'acceuil

        //var_dump($numero_de_page);
        if (is_null($numero_de_page)) {
            $numero_de_page = $this->page_specifique;
        }

        $page_en_cache = $this->Donnee_selectionner_du_gestionnaire('Page_en_cache');
        $tableau = $page_en_cache->get_page_en_cache();

        if (array_key_exists($numero_de_page, $tableau)) {
            $page_demander = $tableau[$numero_de_page];
        } else {
            $numero_de_page = -1;
            $page_demander = $tableau[$numero_de_page];
        }

        //var_dump($page_demander);
        //var_dump($this->recuperer_cache($tableau[$numero_de_page]));

        if (file_exists(CACHE . $page_demander . '.html.php')) {
            return $this->recuperer_cache($tableau[$numero_de_page]);
        }

        // !! erreur journal ici
        return $this->recuperer_cache($tableau[-1]);

    }

    // à tester !!
    public function recuperer_page_en_url($fonction_dutilisation = null): int|null
    {
        // utilisé ?page={numero}/{page_{nom}}
        if (array_key_exists('page', $_GET)) {
            $page_potentiel = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
            $page_potentiel = str_replace(['$', '.', '!', '*', '\'', '(', ')', ',', '{', '}', '|',
                '\\', '^', '~', '[', ']', '`', '<', '>', '#', '%', '"', ';',
                '/', '?', ':', '@', '&', '='], '', $page_potentiel);

            if (strlen($page_potentiel) == 0) return null;

            if (str_contains($page_potentiel, '_')) {
                $donnee_parties = explode('_', $page_potentiel);
                //var_dump($this->donnee_gestionnaire_ID);
                if ($donnee_parties[0] == 'ac') {

                    $page_en_cache = $this->Donnee_selectionner_du_gestionnaire('Page_en_cache');
                    $tableau = $page_en_cache->get_page_en_cache();
                    $flipped = array_flip($tableau);

                    $decode = $this->codec_ac($donnee_parties[1], decoder: true);
                    //var_dump($decode);

                    if ($decode === '') return null;

                    // var_dump($decode);
                    // ne pas oublier la gestion des ';' avec alpha_codec
                    // pour une gestion argument multiple
                    if (str_contains($decode, ';')) {
                        $separer = explode(';', $decode);
                        $this->arguments_url = $separer;
                        $decode = $separer[0];
                    }
                    // var_dump($this->arguments_url);
                    // var_dump($decode);

                    if (array_key_exists($decode, $flipped)) {
                        $page_num_demander = $flipped[$decode];
                    } else {
                        return null;
                    }

                    if (is_callable($fonction_dutilisation) && $fonction_dutilisation()) {
                        return intval($page_num_demander);
                    }

                    if (is_null($fonction_dutilisation)) {
                        return intval($page_num_demander);
                    } else {
                        return null;
                    }


                } else if ($donnee_parties[0] == 'page') {

                    $page_en_cache = $this->Donnee_selectionner_du_gestionnaire('Page_en_cache');
                    $tableau = $page_en_cache->get_page_en_cache();
                    $flipped = array_flip($tableau);

                    if (array_key_exists($donnee_parties[1], $flipped)) {
                        $page_num_demander = $flipped[$donnee_parties[1]];
                    } else {
                        return null;
                    }

                    if (is_callable($fonction_dutilisation) && $fonction_dutilisation()) {
                        return intval($page_num_demander);
                    }

                    if (is_null($fonction_dutilisation)) {
                        return intval($page_num_demander);
                    } else {
                        return null;
                    }

                } else {
                    return null;
                }

            } else {


                $page_potentiel = filter_var($page_potentiel, FILTER_SANITIZE_NUMBER_INT);

                if (strlen($page_potentiel) == 0) return null;

                if (is_callable($fonction_dutilisation) && $fonction_dutilisation()) {
                    return intval($page_potentiel);
                }

                if (is_null($fonction_dutilisation)) {
                    return intval($page_potentiel);
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }


    }


    /** Cette methode permet de récupérer le fichier .json d'un profil de page créé et d'en exploité les données
     * cette fonction est necessaire à la génération d'une page avant ça mise en cache
     * dans le Json il devra y avoir si dessous syntaxe pour definir l'exploitatation de L7
     * "donnees": {
     * "syntaxe": "L7"
     *  }
     * @param $nom_profil
     * @return array|bool|string
     */
    public
    function get_profile($nom_profil): array|bool|string
    {
        $donnee = file_get_contents(PROFILS . $nom_profil . '/' . $nom_profil . '.json');
        $tableau_donnee = json_decode($donnee, true);
        $nom_specifique = explode('_', basename($nom_profil));

        if (array_key_exists('syntaxe', $tableau_donnee['donnees']) && (
                $tableau_donnee['donnees']['syntaxe'] == 'L7' ||
                $tableau_donnee['donnees']['syntaxe'] == 'l7'
            )) {


            //$Modules_Level7 = new \Eukaruon\modules\Modules_Level7();

            if (empty($this->donnee_gestionnaire['Modules_Level7']) || is_null($this->donnee_gestionnaire['Modules_Level7'])) {
                include_once MODULES . 'Modules_Level7.php';
                $Modules_Level7 = new Modules_Level7(null, true);
                // var_dump($Modules_Level7);
            } else {
                var_dump($this->donnee_gestionnaire);
                $Modules_Level7 = &$this->donnee_gestionnaire['Modules_Level7'];
            }


            $donee_exploite = file_get_contents(PROFILS . $nom_profil . '/' . $nom_profil . '.l7');
            return $Modules_Level7->generer_l7($donee_exploite);

        } else {
            return $this->construction_page(
                $nom_profil,
                $tableau_donnee['corps'],
                $tableau_donnee['donnees'],
                (($nom_specifique[0] == 'page') ? true : false)
            );
        }
    }

    /** Cette methode permet de construire une page par rapport au profile de celle-ci
     * elle permet aussi d'interpreter les parties important du profil json et de construire les données
     * de la page à mettre en cache dans le futur
     * @param string $nom_profil
     * @param string $fichier_corps
     * @param array $parties_relier
     * @return array|false|string|string[]
     */
    public
    function construction_page(string $nom_profil, string|null $fichier_corps = '', array|null $parties_relier = array(), bool $pages_non_profil = false): array|bool|string
    {
        /*if ($pages_non_profil == true) {

            include_once PAGES . $nom_profil . '.php';
            $nom_espace_de_nom = 'Eukaruon\\pages\\' . $nom_profil;
            $pages_nom_de_profil = new $nom_espace_de_nom();

            return $pages_nom_de_profil->index();

        } else {*/
        $chemin_profils = PROFILS . $nom_profil;
        $corps = file_get_contents($chemin_profils . '/html/' . $fichier_corps);
        $tableau = array();
        $extensions = [
            "gif" => IMAGETYPE_GIF,
            "jpg" => IMAGETYPE_JPEG,
            "png" => IMAGETYPE_PNG,
            "swf" => IMAGETYPE_SWF,
            "psd" => IMAGETYPE_PSD,
            "bmp" => IMAGETYPE_BMP,
            "tiffii" => IMAGETYPE_TIFF_II,
            "tiffmm" => IMAGETYPE_TIFF_MM,
            "jpc" => IMAGETYPE_JPC,
            "jp2" => IMAGETYPE_JP2,
            "jpx" => IMAGETYPE_JPX,
            "jb2" => IMAGETYPE_JB2,
            "swc" => IMAGETYPE_SWC,
            "iff" => IMAGETYPE_IFF,
            "wbmp" => IMAGETYPE_WBMP,
            "xbm" => IMAGETYPE_XBM,
            "ico" => IMAGETYPE_ICO,
        ];

        /* on include une seules fois les fichier potentiellement multiple des profils */
        $class_charger = array();
        $langue_charger = array();
        foreach ($parties_relier as $valeur) {
            if (is_array($valeur) &&
                $valeur['type'] == 'CLASS' &&
                !array_key_exists($valeur['class'], $class_charger)
            ) {
                include_once $chemin_profils . '/' . $valeur['class'] . '.php';
                $class_charger[$valeur['class']] = new $valeur['class']();
            } elseif (is_array($valeur) &&
                $valeur['type'] == 'LANGUE' &&
                !array_key_exists($valeur['langue'], $langue_charger)
            ) {
                $langue_charger[$valeur['langue']] = json_decode(
                    file_get_contents($chemin_profils . '/langues/' . $valeur['langue'] . '.json')
                    , true);
            }
        }

        $list_methode = get_class_methods($this);
        foreach ($parties_relier as $valeur) {
            if (is_array($valeur)) {
                switch ($valeur['type']) {
                    case 'PAGE':
                        /* permet d'inclure des pages spécifique du dossier "html"
                           ou d'utiliser une page spécifique d'un autre profil ou dans pages
                           "autre" : "chemin vers celui-ci"
                           celui-ci point directement vers PAGES il faudra donc indiqué le nom
                           du profile/html/
                            l'avantage c'est de pouvoir concevoir des pages générique dans PAGES
                            qui n'ont pas besoin d'image ou de script puis de fabriqué des profils
                            de page plus complexe
                        */
                        $chemin = (array_key_exists('autre', $valeur) ? PAGES . $valeur['autre'] : $chemin_profils . '/html/');
                        $tableau[] = file_get_contents($chemin . $valeur['page']);
                        break;
                    case 'INCLURE_JS':
                        /* permet d'inclure des fichiers js  */
                        $tableau[] = file_get_contents($chemin_profils . '/inclure/js/' . $valeur['inclure_js']);
                        break;
                    case 'INCLURE_CSS':
                        /* permet d'inclure des fichiers css  */
                        $tableau[] = file_get_contents($chemin_profils . '/inclure/css/' . $valeur['inclure_css']);
                        break;
                    case 'CSS':
                        /* permet d'inclure des fichiers css du contenu */
                        $tableau[] = '/ressources/contenus/' . $nom_profil . '/css/' . $valeur['css'];
                        break;
                    case 'JS':
                        /* permet d'inclure des fichiers css  */
                        $tableau[] = '/ressources/contenus/' . $nom_profil . '/js/' . $valeur['js'];
                        break;
                    case 'IMG':
                        /* permet d'inclure des images  */
                        $tableau[] = '/ressources/contenus/' . $nom_profil . '/img/' . $valeur['img'];
                        break;
                    case 'LINK':
                        /* permet d'inclure un lien spécifique */
                        $tableau[] = $valeur['link'];
                        break;
                    case 'LANGUE':
                        /* permet d'inclure un text de langue  */
                        $tableau[] = $langue_charger[$valeur['langue']][$valeur['index']];
                        break;
                    case 'BASE64':
                        /* permet d'inclure des pages sparer  */

                        $tableau[] = 'data:' .
                            (
                                (!array_key_exists('mime', $valeur) ? null : $valeur['mime']) ??
                                (!array_key_exists('extension', $valeur) ? null : $extensions[$valeur['extension']]) ??
                                'application/octet-stream'
                            ) .
                            ';base64,' .
                            (array_key_exists('fichier', $valeur) ?
                                file_get_contents($chemin_profils . '/b64/' . $valeur['fichier']) :
                                $valeur['base64']);

                        break;
                    case 'CLASS':
                        /* permet l'utilisation de methode propre à une class dédier à la page fabriquer */
                        $tableau[] = $class_charger[$valeur['class']]->{$valeur['methode']}(
                            (!array_key_exists('options', $valeur) ?: $valeur['options'])
                        );
                        break;
                    case 'MODULES':
                        /* permet l'utilisation de methode interne à pages et des méthode publique et priver*/
                        if (in_array($valeur['methode'], $list_methode)) {
                            $tableau[] = $this->$valeur['methode'](
                                (!array_key_exists('options', $valeur) ?: $valeur['options'])
                            );
                        }
                        break;
                }
            } else {
                /* gestion de l'autolangue on fabrique un tag spécifique à cela
                 * qui sera gérer par javascript [[lg:<index>]] il est préférable
                 * que pour la gestion de la langue nous utilisons un format différent
                 * des moustaches '{{}}' cela permet de bien ce qui doit être generer
                 * dynamiquement du reste.
                 */
                if ($valeur[0] == '#') {
                    $tableau[] = '[[' . substr($valeur, 1) . ']]';
                } else {
                    $tableau[] = $valeur;
                }
            }


        }

        /* partie expérimental qui reconstruit les moustaches comme nous
        somme en back poste mise en cache alors nous pouvons prendre le
        temps mais sinon il suffirait de spécifier dans le .json du profil
        des valeurs clé avec leur moustache {{XYZ}} et remplacer ici bas
        $clee_moustache par array_keys($parties_relier)
        */

        $callback = fn(string $clee): string => '{{' . $clee . '}}';
        $clee_moustache = array_map($callback, array_keys($parties_relier));

        return str_replace($clee_moustache, $tableau, $corps);
        //}
    }

    /** cette fonction est là pour la mise en cache des données via son temps max soit 15j
     * et de pouvoir forcer cette mise en cache avec l'option $force
     * !!! attention ne pas mettre en cache les dates, variables de session et autres valeur suceptible de changer mise en cache
     * @param string $nom_fichier_genrer
     * @param bool $force
     * @param int|float $temps_max
     */
    public
    function mise_en_cache(
        string $nom_fichier_genrer,
        bool   $force = false,
        int    $temps_max = (60 * 60 * 24 * 14) // 3600s x 24h x 14j
    )
    {

        $chemin_encache = CACHE . $nom_fichier_genrer;
        /* on vérifie le temps max d'un fichier mise en cache */
        if (@filemtime($chemin_encache) < time() - $temps_max || $force) {

            //---------
            //ob_start();
            // revoir cette partie

            /* contenu mise en cache et pas afficher
             * echo htmlspecialchars($_COOKIE["tc"],  ENT_QUOTES, 'UTF-8');
             * die();
             */
            //echo //htmlspecialchars(
            //    file_get_contents(GENERER . $nom_fichier_genrer)
            //, ENT_QUOTES, 'UTF-8')
            // ;

            //$cache_contenu = ob_get_contents();
            //ob_end_flush();
            //------------

            $fd = fopen($chemin_encache . '.php', 'w');
            if ($fd) {
                fwrite($fd, file_get_contents(GENERER . $nom_fichier_genrer));
                fclose($fd);
            }

            //die();

        } else {

            /* remplacer le systéme par la suite par une gestion SQLITE
            pour une plus grande fluidité s'il y a une forte demande */

            $index_cache = file_get_contents(RESSOURCES . 'index_cache.json');
            $tableau_index_cache = json_decode($index_cache, true);
            $tableau_index_cache[$nom_fichier_genrer] = time();

            $fd = fopen(RESSOURCES . 'index_cache.json', 'w');
            if ($fd) {
                fwrite($fd, json_encode($tableau_index_cache));
                fclose($fd);
            }

            //include($chemin_encache);
        }


    }

    /** Cette methode est là pour la préparation à la mise en cache.
     * on prépare les dossiers leurs créations et destruction
     * si vous modifier le fonctionnement des profiles alors il faudra
     * faire des modifs ici
     *
     *  liste de dossier à copier avec leur contenus :
     *  - nom_de_profile
     *  - b64 <- les fichier sous forme base64 !!! attention je vous déconseil des fichiers supérieur à 1Mo
     *      les fichiers en base64 sont charger en bloque alors que par exemple les fichiers image
     *      les explorateur web se sont adapter pour un chargement progressif donc n'utilisez le base64 que
     *      pour des cas trés exceptionel ou vous ne voulez pas que cela soit contenu dans un Css ou dans la page
     *      la page générer
     *  - css : les fichier css
     *  - img <- les images
     *  - js <- les fichiers javascript
     *  - langues <- les fichiers de langues
     *  - scripts <- contiendra les scripts PHP back non javascript !
     *  - autres <- j'ai ajouter se dossier pour facilité l'évolution
     *      vous devrez mettre vos fichier spécifique ici
     * @param $nom_du_profil
     */
    public
    function preparation_mise_encache($nom_du_profil)
    {

        if ($this->cedossier_existe_il(CONTENUS . $nom_du_profil)) {
            // si oui on supprimer
            $liste_dossiers = new FilesystemIterator(CONTENUS . $nom_du_profil, FilesystemIterator::SKIP_DOTS);
            foreach ($liste_dossiers as $dossier) {
                /* normalement il n'y a pas de fichier dans le dossier de profile
                mais que des dossiers */
                $nom_dossier = $dossier->getBasename();
                $liste_fichiers = new FilesystemIterator(CONTENUS . $nom_du_profil . '/' . $nom_dossier, FilesystemIterator::SKIP_DOTS);
                foreach ($liste_fichiers as $fichier) {
                    $nom_fichier = $fichier->getBasename();
                    unlink(CONTENUS . $nom_du_profil . '/' . $nom_dossier . '/' . $nom_fichier);
                }
                rmdir(CONTENUS . $nom_du_profil . '/' . $nom_dossier);
            }

        } else {
            // on construit le nom s'il n'existe pas
            mkdir(CONTENUS . $nom_du_profil);
        }
        // si non on construit
        $dossier_liste = ['b64', 'css', 'img', 'js', 'langues', 'scripts', 'autres'];
        foreach ($dossier_liste as $dossier) {
            mkdir(CONTENUS . $nom_du_profil . '/' . $dossier, 0777, true);
        }

        /* on copy les fichiers contenu dans le profile */
        foreach ($dossier_liste as $dossier) {
            if (file_exists(PROFILS . $nom_du_profil . "/$dossier/")) {
                $this->copyer_fichiers_dans(
                    PROFILS . $nom_du_profil . "/$dossier/",
                    CONTENUS . $nom_du_profil . "/$dossier/");
            }
        }
    }

    /** Test de l'existance d'un dossier
     * @param $dossier
     * @return bool
     */
    private
    function cedossier_existe_il($dossier): bool
    {
        return (file_exists($dossier) && is_dir($dossier));
    }

    /** Cette methode permet de copier des fichiers vers une destination
     * @param $dossier_origine
     * @param $dossier_destination
     */
    private
    function copyer_fichiers_dans($dossier_origine, $dossier_destination)
    {
        var_dump($dossier_origine, $dossier_destination);
        $liste_fichiers = new FilesystemIterator($dossier_origine, FilesystemIterator::SKIP_DOTS);
        foreach ($liste_fichiers as $fichier) {
            $nom_fichier = $fichier->getBasename();
            /*
            $eclater = explode('_', $nom_fichier);
            if ($eclater[0] == 'page') {
                $nom_fichier .= '.php';
            }*/
            copy($dossier_origine . $nom_fichier, $dossier_destination . $nom_fichier);
        }
    }

    /** Permet d'ecrire la page avec les donnée générer dans la destination
     * GENERER , Attention c'est le dossier /ressources/generer ce qui n'a rien
     * avoir avec la mise en Cache on prépare avant la mise en cache les pages
     * @param string $nom_page
     * @param string $donnee_page
     */
    public
    function generer(string $nom_page, string $donnee_page)
    {
        $fp = fopen(GENERER . $nom_page, 'w');
        fwrite($fp, $donnee_page);
        fclose($fp);
    }

    /** Permet de récuperer le contenu de la page créé dans generer
     * pour en suite l'utiliser dans une application de visualisation
     * @param string $nom_page
     * @return false|string
     */
    public
    function recuperer_generer(string $nom_page): bool|string
    {
        return file_get_contents(GENERER . $nom_page);
    }

    /** Permet d'obtenir le numero de la page à afficher
     * c'est le dernier element qui indique ou se trouve l'utilisateur
     * @return int
     */
    public
    function get_page_specifique(): int
    {
        return $this->page_specifique;
    }


    /* !!!! à finir obligatoirement !!!!
        l'utilisateur à une clée_authentification_temporaire
        en cookie à vérifier avec la base de donnée
        cette clé est réinitialisé à chaque connexion login
        la session utilisateur est de 24h et donc renouveler
        à chaque fois que l'utilisateur utilise clée authentification temporaire
        aprés 12h. et renouveler pour 1 fois. donc 12h doivent être passé pour
        actualisé date_session et tout les 24h soit la deuxiémes fois de la connexion
        la clée_authentification_temporaire change dans le cookie utilisateur et
        dans clee_authentification
        la clée qui est créé n'est pas la même que la clé stocker
        la clée créé coté utilisateur est hashé avec l'identifiant serveur
        la clée créé coté utilisateur est pas hashé
        mais une fois hashé avec l'identifiant serveur permet de valider la clee coté
        utilisateur
        ----- V2 voir avec Identifiant unique (sel) et identifiant serveur pour produire
        une clée plus complexe à détourné pour évité le brute force pour trouvé
        identifiant unique du serveur

    voir Mudule_utilisateurs méthode cree_utilisateur()
    */

    /** Charger la page cette fonction est une sécurité qui permet au dernier moment
     * de bien charger la page voulu cela fait passé le nom de la page de la variable
     * [$charger_nom_page_non_valider] vers la variable [$charger_nom_page_valider]
     *
     */
    public
    function charger_page_valider()
    {
        $this->charger_nom_page_valider = $this->charger_nom_page_non_valider;
    }

    /** premet de vérifier si la clé d'authentification temporaire est valide
     * @param string $clee_authentification_temporaire
     * @return array
     */
    private
    function get_verification_clee_authentification_temporaire(string $clee_authentification_temporaire): array
    {
        return array();
    }

    /** permet d'actualisé le temps d'utilisation de la clé authentifiacation
     *
     */
    private
    function set_actualisation_temps_restant()
    {
    }

    /** vérifie si la page spécifique existe par exemple si le nombre demander est bien dans la liste
     * @param int $page_specifique_demander
     * @return false
     */
    private
    function get_verifier_page_specifique_demander(int $page_specifique_demander): bool
    {

        $Modules_bdd = &$this->Donnee_selectionner_du_gestionnaire('Modules_bdd');
        $results = $Modules_bdd->selectionner_BDD(
            $this->Module_utiliser_exploitant_BDD(),
            'utilisateurs.db',
            'pages',
            'id', $page_specifique_demander
        );

        /* a finir */
        $this->charger_nom_page_non_valider = null;
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            if ($row['id'] == $page_specifique_demander) {
                $this->charger_nom_page_non_valider = $row['nom'];
                break;
            }
        }
        if (is_null($this->charger_nom_page_non_valider)) {
            $this->charger_nom_page_non_valider = 'accueil'; // on charge la page d'accueil
        }

        return false;
    }


}
