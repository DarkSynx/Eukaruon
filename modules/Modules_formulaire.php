<?php

namespace Eukaruon\modules;

use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 *
 */
class Modules_formulaire
{
    /**
     * @var mixed
     */
    private array $code;

    /**
     *
     */
    private const VERSION = '1.0.2a';

    /**
     * @var array|mixed
     */
    private array $_element_check_name = array();

    /**
     * @var string|mixed|null
     */
    private string|null $_nom_formulaire = null;

    /**
     *
     */
    private const CHEMIN_PATRON = SOUSMODULES;

    /**
     * @var array
     */
    private array $_tab_nom_type_valeur = array();

    /**
     * @param $code
     */
    public function __construct(array $tableau)
    {
        $code = $tableau[0];
        $this->_element_check_name = $tableau[1];

        $this->code = $code;

        //var_dump($this->_nom_formulaire);
        if (is_null($this->_nom_formulaire))
            $this->_nom_formulaire = $code['name'] ?? 'formulaire_' . time();
        //var_dump($this->_nom_formulaire);

    }

    /**
     * @param string|null $action
     * @param string $method
     * @param string|null $id
     * @param string|null $class
     * @param string|null $name
     * @return static
     */
    public static function defini(string|null $action = null, string $method = 'post', string|null $id = null,
                                  string|null $class = null, string|null $name = null): static
    {
        return new static([self::identite_formulaire($action, $method, $id, $class, $name), array()]);
    }

    /**
     * @param $action
     * @param $method
     * @param $id
     * @param $class
     * @param $name
     * @return array
     */
    #[ArrayShape([0 => "string", 1 => "string", 'name' => ""])]
    private static function identite_formulaire($action, $method, $id, $class, $name): array
    {
        $gen_infos = '';
        if (strlen($action) > 0) $gen_infos .= " action=\"$action\"";
        if (strlen($method) > 0) $gen_infos .= " method=\"$method\"";
        if (strlen($id) > 0) $gen_infos .= " id=\"$id\"";
        if (strlen($class) > 0) $gen_infos .= " class=\"$class\"";
        if (strlen($name) > 0) $gen_infos .= " class=\"$name\"";


        return ["<form $gen_infos>", '', 'name' => $name];
    }

    /**
     * @param ...$tableau
     * @return $this
     */
    public function elements(...$tableau): static
    {
        $tableau_element = array();
        $tableau_fusionner = [$this->code[0], $this->code[1]];
        foreach ($tableau as $valeur) {
            $tableau_element = call_user_func_array([$this, 'element'], [$valeur]);
            $tableau_fusionner = [
                $tableau_fusionner[0] . PHP_EOL . $tableau_element[0][0],
                $tableau_fusionner[1] . PHP_EOL . $tableau_element[0][1],
            ];
        }
        $tableau_fusionner['name'] = $this->_nom_formulaire;
        //$tableau_fusionner['donnee'] = array();

        return new static([$tableau_fusionner, $tableau_element[1]]);
    }


    /**
     * @param $tableau
     * @return array
     * @throws Exception
     */
    private function element($tableau): array
    {
        if (array_key_exists($tableau[3], $this->_element_check_name)) {
            $element = $this->_element_check_name[$tableau[3]];
            throw new Exception("( Erreur [$tableau[2] : $tableau[3]] nom déjà utilisé par [$element : $tableau[3]] )");
        }

        if (!empty($tableau[3]))
            $this->_element_check_name[$tableau[3]] = $tableau[2];

        //$this->_tab_nom_type_valeur[$tableau[]]
        //var_dump($this->_element_check_name);

        return [$tableau, $this->_element_check_name];
    }

    /* *
     * RECODER TOUTES CETTE PARTIE POUR RENDRE PAR DEFAULT
     *
     *
     *
     * */

    /**
     * @param string|null $chemin_fichier_php
     * @param bool $instancier
     * @param bool $debug
     * @param bool $afficher
     * @return array
     */
    public function generer(
        string|null $chemin_fichier_php = null,
        string|null $fichier_post_traitement = null,
        string|null $identifiant_utilitsation = null,
        bool        $instancier = false,
        bool        $debug = false,
        bool        $exploiter = false,
        bool        $un_fichier_unique = true,

    ): array
    {
        //var_dump($this->_element_check_name);

        $this->code[0] .= PHP_EOL . '</form>';

        $donnee_php = $this->gen_class_exploite(
            $this->code[1],
            $instancier,
            $debug,
            $this->code[0],
            $chemin_fichier_php,
            $exploiter,
            $un_fichier_unique,
            $fichier_post_traitement,
            $identifiant_utilitsation
        );

        return [
            $this->code[0],
            $donnee_php
        ];

    }


    /**
     * @param $donnee
     * @param $instancier
     * @param $debug
     * @param $code
     * @param $chemin_fichier_php
     * @param $exploiter
     * @param $un_fichier_unique
     * @param $post_traitement
     * @return string
     */
    private function gen_class_exploite(
        $donnee,
        $instancier,
        $debug,
        $code,
        $chemin_fichier_php,
        $exploiter,
        $un_fichier_unique,
        $post_traitement,
        $identifiant_utilitsation
    ): string
    {
        $auto_code_gen = '';


        if ($instancier) {
            $vardump = '';
            if ($debug) $vardump = 'var_dump($recolte->analyse());';
            $auto_code_gen = "
                new recolte();
                $vardump";
        }

        //var_dump($this->_element_check_name);

        $patron_class_recup = file_get_contents(self::CHEMIN_PATRON . 'patron_class_recolte.php');
        $patron_class_recup = str_replace('/* <!-- [DONNEE] --> */', $donnee, $patron_class_recup);

        if (!is_null($identifiant_utilitsation)) {
            $patron_class_recup = str_replace(
                '"<!-- [PHP-INJECTION-IDUSE-OK] -->"',
                'true',
                $patron_class_recup);

            $patron_class_recup = str_replace(
                '"<!-- [PHP-INJECTION-IDUSE] -->"',
                $identifiant_utilitsation,
                $patron_class_recup);
        } else {
            $patron_class_recup = str_replace(
                '"<!-- [PHP-INJECTION-IDUSE-OK] -->"',
                'false',
                $patron_class_recup);

            $patron_class_recup = str_replace(
                '"<!-- [PHP-INJECTION-IDUSE] -->"',
                time(),
                $patron_class_recup);
        }

        if (!is_null($post_traitement)) {
            $patron_class_recup = str_replace(
                '"<!-- [PHP-INJECTION-LIEN] -->"',
                '\'' . $post_traitement . '\'',
                $patron_class_recup);
        }

        $patron_class_recup = str_replace(
            ['"<!-- [JS_GEN_NAME] -->"', '"<!-- [POST_NAME_EXIST] -->"'],
            '"' . implode('","', array_keys($this->_element_check_name)) . '"',
            $patron_class_recup);

        $patron_class_recup = str_replace(
            '"<!-- [JS_GEN_TYPE] -->"',
            '"' . implode('","', array_values($this->_element_check_name)) . '"',
            $patron_class_recup);

        $patron_class_recup .= $auto_code_gen;


        if ($un_fichier_unique) {
            //<!-- [HTML_GEN] -->
            $patron_class_recup = str_replace('<!-- [HTML_GEN] -->',
                str_replace("'", "\'", $code),
                $patron_class_recup);
        }

        if (!is_null($chemin_fichier_php)) {

            file_put_contents($chemin_fichier_php, $patron_class_recup);

            if ($exploiter) {
                include $chemin_fichier_php;
            }
        }


        return $patron_class_recup;
    }


}