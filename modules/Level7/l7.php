<?php

namespace Eukaruon\modules\Level7;

/**
 *
 */
class l7
{

    /**
     *
     */
    const REGEX_G = '/[!#$%&=@]?(?:[!#$%&=@]?\w*(?:\.\w*)?)\s?(?#
)(?#
---------------------
)(?:\[(?:(?:(?(R)\w++|[^[\]]*+)|(?R)).*)\]\s*)?(?#
)(?:\((?:(?:(?(R)\w++|[^()]*+)|(?R)).*)\)\s*)?(?#
)(?:\<(?:(?:(?(R)\w++|[^<>]*+)|(?R)).*)\>\s*)?(?#
)(?|(?#
)(?:\{(?:[^{}]|(?R))*\})|(?#
)\;)/';
    /**
     *
     */
    const REGEX_N = '/[!#$%&=@]?([!#$%&=@]?\w*(?:\.\w*)?)\s?(?#
)(?#
---------------------
)(?:\[((?:(?(R)\w++|[^[\]]*+)|(?R)).*)\]\s*)?(?#
)(?:\(((?:(?(R)\w++|[^()]*+)|(?R)).*)\)\s*)?(?#
)(?:\<((?:(?(R)\w++|[^<>]*+)|(?R)).*)\>\s*)?(?#
)(?|(?#
)(?:\{((?:[^{}]|(?R))*)\})|\;)/';

    /**
     *
     */
    const NAMESPC = 'Eukaruon\\modules\\Level7\\syntaxe\\';
    /**
     * @var array
     */
    public $matchesG = array();
    /**
     * @var array
     */
    public $sub_matches = array();
    /**
     * @var string
     */
    public $cphp_eol = '';
    /**
     * @var null
     */
    public $exploder_syntaxe_parent = null;
    /**
     * @var int
     */
    public $exploder_syntaxe_encaps = 0;
    /**
     * @var array
     */
    private $_map_syntaxe = array();
    /**
     * @var string
     */
    private $_data = '';
    // const TABULATION_ONOFF = false;
    /**
     * @var bool|mixed
     */
    private bool $tabulation_onoff;
    /**
     * @var array
     */
    private $_special_syntaxe = array();

    /**
     * @param $liste_syntaxe
     * @param false $tabulation
     */
    public function __construct(&$liste_syntaxe, $tabulation = false)
    {
        $this->load_syntaxe($liste_syntaxe);
        $this->tabulation_onoff = $tabulation;
        $this->cphp_eol = ($tabulation == false) ? '' : PHP_EOL;
    }

    /**
     * @param $array_file_list
     */
    private function load_syntaxe($array_file_list): void
    {
        $gestglobal = new gestionglobal();
        foreach ($array_file_list as $path => $entry) {
            //$inc++;

            //$exp_path = explode('.', $entry->getFilename());
            $exp_path = pathinfo($entry->getFilename());
            $exp_path_name_space = self::NAMESPC . $exp_path['filename'];
            $this->_map_syntaxe[$exp_path_name_space] = new $exp_path_name_space($gestglobal);

            /*	generateur de class spécial: html => #
                permet d'ajouter un caractére spécial qui représente une class
                par exemple html sera représenter par le caractére spécial # ce qui
                permet d'appeler une sous fonction de html par exemple input :
                #input se qui vas appeler la class html et input de la class.
                c'est à vous d'imprémenter le comportement de input dans la class
                dans html ce n'est pas une méthode mais ça pourrait l'être

                define constant in class
                    const SPECIAL_SYNTAXE		= true;
                    const SPECIAL_SYNTAXE_CHAR	= '%';
            */
            if ($this->_map_syntaxe[$exp_path_name_space]::SPECIAL_SYNTAXE) {
                $special_syntaxe_car = $this->_map_syntaxe[$exp_path_name_space]::SPECIAL_SYNTAXE_CHAR;
                $this->_special_syntaxe[$special_syntaxe_car] = $exp_path['filename'];
            }
        }
        // SPECIAL FUNCTION
        print_r($this->_special_syntaxe);

    }

    /**
     * @return string
     */
    public function getdata(): string
    {
        return $this->_data;
    }

    /**
     * @param $data
     */
    public function start($data): void
    {
        $this->_data = trim($data);

        // gestion post include
        $cursize = strlen($this->_data) - 1;
        $size_loaded = strlen('loaded' . chr(32));
        for ($cursor = 0; $cursor < $cursize; $cursor++) {

            $position = strpos($this->_data, 'loaded' . chr(32), $cursor);
            if ($position === false) break;

            $position2 = strpos($this->_data, ';', $position);
            $ensemble = substr($this->_data, $position, ($position2 - $position));
            $extractlink = trim(str_replace('loaded' . chr(32), '', $ensemble), "\"");
            $this->_data = str_replace($ensemble . ';', file_get_contents($extractlink), $this->_data);
            $cursor = $position2;

        }

        $this->_data = $this->exploder_syntaxe($this->_data);
        //return $this->_data;
    }

    /**
     * @param $data
     * @param null $parent
     * @param int $encaps
     * @param string $tabulation
     * @return string
     */
    public function exploder_syntaxe(&$data, &$parent = null, $encaps = 0, $tabulation = ''): string
    {


        preg_match_all(self::REGEX_G, $data, $matchesG);

        $matchesG = array_map('trim', $matchesG[0]);

        if (count($matchesG) == 0) {
            //$tabulation  = ($tab_actived ? str_repeat("\t", ($encaps - 1)) : '');
            return $tabulation . $data;
        }

        $this->exploder_syntaxe_parent = $parent;
        $this->exploder_syntaxe_encaps = $encaps;
        $map_array = array_map([$this, "foreachmap"], $matchesG);
        $data = implode($this->cphp_eol, $map_array);

        if ($encaps > 0) {
            $encaps--;
            $this->exploder_syntaxe_encaps = $encaps;
        }

        return $data;
    }

    /**
     * @param $val
     * @return mixed
     */
    private function foreachmap($val)
    {


        preg_match(self::REGEX_N, $val, $this->sub_matches);
        $this->sub_matches = array_map('trim', $this->sub_matches);


        if (count($parts = pathinfo($this->sub_matches[1])) > 3)
            $this->sub_matches[1] = $parts['filename'];


        $index_map_syntaxe =
            self::NAMESPC . (

            array_key_exists($this->sub_matches[0][0], $this->_special_syntaxe) ?
                $this->_special_syntaxe[$this->sub_matches[0][0]] :
                (
                array_key_exists(self::NAMESPC . $this->sub_matches[1], $this->_map_syntaxe
                ) ?
                    $this->sub_matches[1]
                    :
                    'notexcute'
                )

            );


        $this->_map_syntaxe[$index_map_syntaxe]->pre_initialisation(
            $this, $val, $parts['extension'],
            $this->exploder_syntaxe_parent,
            $this->exploder_syntaxe_encaps,
            $this->tabulation_onoff
        );
        return $val;

    }


}


?>