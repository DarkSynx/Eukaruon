<?php namespace Eukaruon\modules;


/** a pour objectif de securisé les donnés
 *  en les encapsulants dans cette objet
 *  mais aussi la gestion du passage d'information
 *  de page en page via les variables de session
 */
class Modules_securiser
{
    /**
     * @var array
     */
    private array $_valeur_encapsuler = array();
    /**
     * @var string
     */
    private string $_iv = '';
    /**
     * @var string
     */
    private string $_identifiant_unique = '';

    /**
     *
     */
    public function __construct()
    {
        $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        /* peut être ajouté l'utilisation de l'identifiant unique en BDD pour renforcer la sécurité */
    }

    /** permet de spécifier un identifiant unique
     * @param $identifiant
     */
    public function ajouter_identifiant_unique($identifiant)
    {
        $this->_identifiant_unique = hash('sha256', $identifiant);
    }

    /** valeur qui sera encapsuler
     * @param string $nom_variable
     * @param $valeur
     */
    public function valeur_a_encapsuler(string $nom_variable, $valeur)
    {
        $this->_valeur_encapsuler[$nom_variable] = $valeur;
    }

    /** valeur qui sera sécurisé chiffré
     * @param string $nom_variable
     * @param $valeur
     * @param string $clee
     * @param bool $encrypt
     */
    public function valeur_asecuriser(string $nom_variable, $valeur, string $clee, bool $encrypt = false)
    {
        $this->_valeur_encapsuler[$nom_variable]['clee'] = md5($clee);
        if ($encrypt) {
            $valeur = openssl_encrypt($valeur, 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $this->_iv);
        }
        $this->_valeur_encapsuler[$nom_variable]['valeur'] = $valeur;
    }

    /** valeur sera déchiffré
     * @param string $nom_variable
     * @param string $clee
     * @param bool $decrypt
     * @return false|mixed|string|null
     */
    public function valeur_desecuriser(string $nom_variable, string $clee, bool $decrypt = false)
    {
        if ($this->_valeur_encapsuler[$nom_variable]['clee'] == md5($clee)) {
            if ($decrypt) {
                return openssl_decrypt($this->_valeur_encapsuler[$nom_variable]['valeur'], 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $this->_iv);
            }
            return $this->_valeur_encapsuler[$nom_variable]['valeur'];
        }
        return null;
    }

    /** valeur en session qui sera chiffré
     * @param string $nom_variable
     * @param $valeur
     * @param string $clee
     * @param bool $encrypt
     */
    public function sauvgarder_en_session_securiser(string $nom_variable, $valeur, string $clee, bool $encrypt = false)
    {
        $_SESSION[$nom_variable]['iv'] = $this->_iv;
        $_SESSION[$nom_variable]['clee'] = hash('sha256', $clee);
        if ($encrypt) {
            $valeur = openssl_encrypt($valeur, 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $this->_iv);
        }
        $_SESSION[$nom_variable]['valeur'] = $valeur;
    }

    /** valeur en session qui sera déchiffré
     * @param string $nom_variable
     * @param string $clee
     * @param bool $decrypt
     * @return false|mixed|string|null
     */
    public function valeur_en_session_securiser(string $nom_variable, string $clee, bool $decrypt = false)
    {
        if ($_SESSION[$nom_variable]['clee'] == hash('sha256', $clee)) {
            if ($decrypt) {
                return openssl_decrypt($_SESSION[$nom_variable]['valeur'], 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $_SESSION[$nom_variable]['iv']);
            }
            return $_SESSION[$nom_variable]['valeur'];
        }
        return null;
    }


    /** sauvgarde simple d'une valeur en session
     * @param string $nom_variable
     * @param $valeur
     * @return array
     */
    public function sauvgarder_en_session(string $nom_variable, $valeur): array
    {
        $_SESSION[$nom_variable] = $valeur;
        return [$nom_variable => $valeur];
    }

    /** recupérer une valeur par son label en sessions
     * @param string $nom_variable
     * @return mixed
     */
    public function valeur_en_session(string $nom_variable)
    {
        return $_SESSION[$nom_variable];
    }
}