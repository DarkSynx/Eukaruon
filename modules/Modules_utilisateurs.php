<?php namespace Eukaruon\modules;


use Eukaruon\configs\DonneeUniqueServeur;

/**
 * module utilisateur l'objectif de ce module
 * c'est de proposé un ensemble d'outils qui permet
 * la gestion des utilisateurs
 */
class Modules_utilisateurs extends Modules_outils
{


    /** Va recevoir un tableau $results->fetchArray(SQLITE3_ASSOC)
     * il faudra donc l'exploité comme un tableau associatif
     * @var null
     */
    protected null|bool|array $utilisateur_bdd = null;

    /** Determine si l'utilisateur est dans la base de donnée ou pas
     * @var bool|null
     */
    protected bool $utilisateur_bdd_ok = false;


    /**
     * @var bool
     */
    protected bool $utilisateur_bloquer = false; // true l'utilisaeur est ok false bloquer
    /**
     * @var int
     */
    protected int $utilisateur_page_direction = -1; // la page ou l'on va rediriger l'utilisateur
    /**
     * @var int
     */
    protected int $date_blocage = 0; // la date de deblocage de l'utilisateur


    //--------------------------------------------------------

    /**
     * permet l'authentification de l'utilisateur
     * et la création des cookies necessaire à l'utilisation
     * des pages
     */
    public function authentfication_utilisateur()
    {

    }

    /**
     * @param $donnee_gestionnaire
     * @return mixed|void
     */
    public function post_construct(&$donnee_gestionnaire)
    {
        $this->Ajouter_donnee_dans_gestionnaire($donnee_gestionnaire);
        $this->utilisateur_est_il_ok();
    }

    /** Cette fonction à pour objectif de vérifier l'existance de l'utilisateur
     *  en base de donnée sur plusieurs points spécifique
     * ----------------------------------------
     *  partie vraiment à refaire
     *  ne déclenchant que l'analyse qu'a condition que remplir la condition
     *  d'avoir une :
     *  - clee_identification = clé hash sha1
     *  - unite_identification = session_id
     *  - clee_authentification_cookie = Clé générer hash password_hash CRYPT_BLOWFISH :
     * $clee_authentification_serveur + $identifiant_unique + $DonneeUniqueServeur::IDSERVEUR
     *  - Dpu_acces = le cookie qui contiendra le numero de la dernier page d'acces
     *
     *  en remplissant cette spécificité on peut démarrer l'annalyse de si l'utilisateur est celui du serveur
     *  sinon on le renvoie directement l'accueil -1
     */
    public function utilisateur_est_il_ok()
    {
        $this->utilisateur_bdd_ok = $this->verifier_lutilisateur();
    }

    /** permet de vérifier l'utilisateur si celui-ci est bien
     * en BDD et s'il est valide
     * @return bool
     */
    public function verifier_lutilisateur(): bool
    {
        /* ----------------------------------- */
        /* Attention !!! VERIFIER $pointeur_utilisateur et son
         * suivis
         *
            * Etape 0 VERIFICATION IP
         * on vérifie que l'IP n'est pas bloqué
         * par un potentiel brut force avec la table ip
         *
         * 3 tentatives sans déclenchement
         * tentative max = 10
         * date_deblocage = tentative min | blocage exponentiel
         *
         */
        $test_ip = $this->verifier_ip_bloquer();
        if (is_null($test_ip)) { // n'est pas en BDD
            $this->utilisateur_bloquer = false;
            $this->utilisateur_page_direction = 0; // retour page inscription
            return false;
        } elseif ($test_ip === false) { // est bloqué
            // l'utilisateur est renvoyer
            $this->utilisateur_bloquer = true;
            $this->utilisateur_page_direction = -1; // retour page accueil
            return false;
        } else { // Utilisateur OK
            $this->utilisateur_bloquer = false;
            if ($this->verifier_cookie_existe()) {

                // vérifier unite_identification == identifiant de session
                $unite_identification = $_COOKIE['unite_identification'];

                // $clee_identification = password_hash($_COOKIE['unite_identification'] . $IDserveur,  PASSWORD_BCRYPT);
                $clee_identification = $_COOKIE['clee_identification'];

                // $clee_authentification = password_hash($idsession . uniqid() . time(), PASSWORD_BCRYPT);
                $clee_authentification = $_COOKIE['clee_authentification'];

                // identifiant de la page charger
                $idpage_identifiant = $_COOKIE['idpage_identifiant'];

                // Attention !!!!! revoir cette nouvel information
                // pointeur utilisateur permet s'il est présent d'indiquer
                // l'utilisateur
                // $pointeur_utilisateur = $_COOKIE['pointeur_utilisateur'] ?? null;

                if (
                    $unite_identification == session_id()
                    // && $pointeur_utilisateur !== null
                ) {

                    if (password_verify(
                        $_COOKIE['unite_identification'] .
                        $this->DonneeUniqueServeur_IDSERVEUR(),
                        $clee_identification)) {

                        $results = $this->Modules_bdd_recherche(
                            'idsession'
                            , $unite_identification
                            , DonneeUniqueServeur::BDD_INSCRIPTION
                            , DonneeUniqueServeur::BDD_INSCRIPTION_TABLE
                        /*,$pointeur_utilisateur*/
                        );

                        $trouver = false;
                        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                            if ($row['idsession'] == $unite_identification) {
                                $clee_authentification_serveur = $row['clee_authentification'];
                                $identifiant_unique_serveur = $row['identifiant_unique'];
                                $trouver = true;
                                break;
                            }
                        }

                        if ($trouver) {
                            if (password_verify(
                                $clee_authentification_serveur .
                                $identifiant_unique_serveur .
                                $this->DonneeUniqueServeur_IDSERVEUR(),
                                $clee_authentification)) {
                                if (intval($idpage_identifiant) >= -1) {
                                    $this->utilisateur_page_direction = $idpage_identifiant;
                                    return true;
                                }
                            }
                        }
                    }
                } // return false && -1
            } // return false && -1
        } // return false && -1
        $this->utilisateur_page_direction = -1;
        return false;
    }

    /** Cette fonction permet de vérifier si l'adresse IP est bloqué ou non et au passage
     *  la rajoute à la BDD si $ajout_a_la_liste est à true. À vous de voir si vous
     *  gardez une trace d'accès dans votre base de donnée ou pas j'ai du coup ajouté le retour
     *  null.
     *
     *  - si l'utilisateur n'est pas bloqué retourne : true
     *  - si l'utilisateur est bloqué retourn : false
     *  - si l'utilisateur est pas dans la base retourn : null
     *
     * @param bool $ajout_a_la_liste
     * @return bool
     */
    public function verifier_ip_bloquer(bool $ajout_a_la_liste = false): null|bool
    {

        $temp_actuel = time();
        if (!array_key_exists('date_blocage', $_SESSION)) $_SESSION['date_blocage'] = 0;
        if (!array_key_exists('blocage_definitif', $_SESSION)) $_SESSION['blocage_definitif'] = false;

        // s'il y a un temp blocage temporaire dépassé ou un blocage definitif actif
        if ($_SESSION['date_blocage'] < $temp_actuel || $_SESSION['blocage_definitif']) {


            $ip = $this->obtenir_ip_utilisateur();
            if (is_null($ip)) {
                return false;
            }

            $results = $this->Modules_bdd_recherche(
                'ip'
                , $ip
                , DonneeUniqueServeur::BDD_IP
                , DonneeUniqueServeur::BDD_IP_TABLE
            );

            // retracer les donné dans $this->utilisateur_bdd
            while ($this->utilisateur_bdd = $results->fetchArray(SQLITE3_ASSOC)) {
                if ($this->utilisateur_bdd['ip'] == $ip) {

                    /* code d'actualisation date_blocage */
                    if ($_SESSION['date_blocage'] < $this->utilisateur_bdd['date_deblocage']) {
                        $_SESSION['date_blocage'] = $this->utilisateur_bdd['date_deblocage'];
                    }
                    if ($this->utilisateur_bdd['blocage_definitif'] == 1) {
                        $_SESSION['blocage_definitif'] = $this->utilisateur_bdd['blocage_definitif'];
                    }
                    return ($this->utilisateur_bdd['blocage'] == 0 && $this->utilisateur_bdd['blocage_definitif'] == 0); // si == 0 retourn true sinon false
                }
            }


            if ($ajout_a_la_liste) {
                // si l'ip n'est pas dans la Bdd on va l'ajouter
                $this->Modules_bdd_ajouter([
                    'ip' => $ip,
                    'tentative' => 0, // le nombre de tentative
                    'date_tentative' => time(), // la dernier tentative
                    'blocage' => 0, // permet de définir un blocage dans le temps
                    'date_deblocage' => time(), // le temps prochain de déblocage
                    'blocage_definitif' => 0   // équivalent à un ban
                ]);
                return true;
            }
            return null;

        } else {
            return false;
        }

    }

    /**
     * Retrieves the best guess of the client's actual IP address.
     * Takes into account numerous HTTP proxy headers due to variations
     * in how different ISPs handle IP addresses in headers between hops.
     */
    public function obtenir_ip_utilisateur(): null|string
    {
        // Check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        // Check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if multiple IP addresses exist in var
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if ($this->validate_ip($ip))
                    return $ip;
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];


        if (array_key_exists('REMOTE_ADDR', $_SERVER) && !empty($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];

        return null;
    }

    /**
     * Ensures an IP address is both a valid IP address and does not fall within
     * a private network range.
     *
     * @access public
     * @param string $ip
     * @return bool
     */
    public function validate_ip(string $ip): bool
    {
        if (
            filter_var($ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) === false)
            return false;
        //self::$ip = $ip;
        return true;
    }

    /*------------------------------------------------------------------------------------*/

    /** Vérifie les Cookies
     * @return int
     */
    // pointeur_utilisateur
    /**
     * @return int
     */
    public function verifier_cookie_existe(): int
    {
        return (

            array_key_exists('clee_identification', $_COOKIE) &&
            array_key_exists('unite_identification', $_COOKIE) &&
            array_key_exists('clee_authentification', $_COOKIE) &&
            array_key_exists('idpage_identifiant', $_COOKIE) &&
            // array_key_exists('pointeur_utilisateur', $_COOKIE) &&

            strlen($_COOKIE['clee_identification']) == 60 && // représente le hash BlowFish
            $_COOKIE['clee_identification'] != '' &&
            $_COOKIE['clee_identification'] != chr(32) &&

            strlen($_COOKIE['unite_identification']) > 0 &&
            $_COOKIE['unite_identification'] != '' &&
            $_COOKIE['unite_identification'] != chr(32) &&

            strlen($_COOKIE['clee_authentification']) == 60 && // représente le hash BlowFish
            $_COOKIE['clee_authentification'] != '' &&
            $_COOKIE['clee_authentification'] != chr(32) &&

            strlen($_COOKIE['idpage_identifiant']) > 0 &&
            $_COOKIE['idpage_identifiant'] != '' &&
            $_COOKIE['idpage_identifiant'] != chr(32)

            /*&&
            strlen($_COOKIE['pointeur_utilisateur']) > 0 &&
            $_COOKIE['pointeur_utilisateur'] != '' &&
            $_COOKIE['pointeur_utilisateur'] != chr(32)*/
        );
    }

    /** permet de récuperer utilisateur_page_direction
     * la page ou l'on va rediriger l'utilisateur
     * @return int
     */
    public function get_utilisateur_page_direction(): int
    {
        return $this->utilisateur_page_direction;
    }

    /** phase d'inscription
     * créé un utilisateur à finir
     * $clee_authentification . $identifiant_unique . $DonneeUniqueServeur::IDSERVEUR
     * SERVEUR :
     * idsession
     * date_session
     * clee_authentification :
     * indentifiant_unique  : openssl_random_pseudo_bytes(8)
     *
     * CLIENT:
     * clee_identification : $_COOKIE['unite_identification'] . $this->DonneeUniqueServeur_IDSERVEUR()
     * unite_identification : idsession
     * clee_authentification : $clee_authentification_serveur . $identifiant_unique_serveur . $this->DonneeUniqueServeur_IDSERVEUR()
     * idpage_identifiant : number
     */
    public function cree_utilisateur()
    {
        $DonneeUniqueServeur = &$this->donnee_gestionnaire['DonneeUniqueServeur'];

        /* variable pour le compte utilisateur coté DB */
        for ($a = 0; $a < 5; $a++) {
            $identifiant_unique = openssl_random_pseudo_bytes(8, $rater);
            if ($rater) break;
        }
        $idsession = session_id();
        $date_session = time() + (60 * 60 * 24);
        $clee_authentification = hash('sha256', $idsession . uniqid() . time());
        /*-------------------------------------*/
        /* ajouter au serveur les informations */
        /* BDD:inscription / lien / utilisateur */
        /* réalisé une requette brute avec jointure */


        /*-------------------------*/
        /* variable pour coté cookie */
        //$pointeur_utilisateur = hash('sha256', $this->DonneeUniqueServeur_IDSERVEUR() . $identifiant_unique . time());

        $clee_identification = password_hash(
            $idsession . DonneeUniqueServeur::IDSERVEUR,
            PASSWORD_BCRYPT);

        $unite_identification_cookie = $idsession;

        $clee_authentification_cookie = password_hash(
            $clee_authentification . $identifiant_unique . DonneeUniqueServeur::IDSERVEUR,
            PASSWORD_BCRYPT);

        $idpage_identifiant = -1;

        setcookie("clee_identification", $clee_identification);
        setcookie("unite_identification", $unite_identification_cookie);
        setcookie("clee_authentification", $clee_authentification_cookie);
        setcookie("idpage_identifiant", $idpage_identifiant);
        //setcookie("pointeur_utilisateur", $pointeur_utilisateur);


        /* ajouter en Bdd */
        // ajouter l'utilisateur en base de donnée


    }


    /* supprimer un utilisateur */
    /* bloquer un utilisateur */
    /* authentifier un utilisateur */


    /*-------------------------------------*/
    /** recupérer l'information de si l'utilisateur est bien en base de donnée
     * @return bool
     */
    public function get_utilisateur_bdd_ok()
    {
        return $this->utilisateur_bdd_ok;
    }

    /** Recupere l'utilisateur
     * @return null
     */
    public function get_utilisateur_bdd()
    {
        return $this->utilisateur_bdd;
    }


    /** récuperer les informations DonneeUniqueServeur
     * @return mixed
     */
    public function &get_DonneeUniqueServeur()
    {
        return $this->DonneeUniqueServeur();
    }
}

