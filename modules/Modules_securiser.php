<?php namespace Eukaruon\modules;


/** a pour objectif de securisé les donnés
 *  en les encapsulants dans cette objet
 *  mais aussi la gestion du passage d'information
 *  de page en page via les variables de session
 */
class Modules_securiser
{
    private array $_valeur_encapsuler = array();
    private string $_iv = '';
    private String $_identifiant_unique = '';

    public function __construct()
    {
        $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        /* peut être ajouté l'utilisation de l'identifiant unique en BDD pour renforcer la sécurité */
    }

    public function ajouter_identifiant_unique($identifiant)
    {
        $this->_identifiant_unique = hash('sha256', $identifiant);
    }

    public function valeur_a_encapsuler(string $nom_variable, $valeur)
    {
        $this->_valeur_encapsuler[$nom_variable] = $valeur;
    }

    public function valeur_asecuriser(string $nom_variable, $valeur, string $clee, bool $encrypt = false)
    {
        $this->_valeur_encapsuler[$nom_variable]['clee'] = md5($clee);
        if ($encrypt) {
            $valeur = openssl_encrypt($valeur, 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $this->_iv);
        }
        $this->_valeur_encapsuler[$nom_variable]['valeur'] = $valeur;
    }

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

    public function sauvgarder_en_session_securiser(string $nom_variable, $valeur, string $clee, bool $encrypt = false)
    {
        $_SESSION[$nom_variable]['iv'] = $this->_iv;
        $_SESSION[$nom_variable]['clee'] = hash('sha256', $clee);
        if ($encrypt) {
            $valeur = openssl_encrypt($valeur, 'aes-256-cbc', $clee . $this->_identifiant_unique, $options = 0, $this->_iv);
        }
        $_SESSION[$nom_variable]['valeur'] = $valeur;
    }

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


    public function sauvgarder_en_session(string $nom_variable, $valeur): array
    {
        $_SESSION[$nom_variable] = $valeur;
        return [$nom_variable => $valeur];
    }

    public function valeur_en_session(string $nom_variable)
    {
        return $_SESSION[$nom_variable];
    }
}