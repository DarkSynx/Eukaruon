<?php

namespace Eukaruon\modules\l7;

class l7
{

    const REGEX_G = '/(?:(?:.+\W*)(?:(?:\;.*)|(?:(?:\[(?:(?:[^\[\]]|(?R))\W*.*\W*)\]\W*){0,1}(?:\((?:(?:[^\(\)]|(?R))\W*.*\W*)\)\W*){0,1}(?:\<(?:(?:[^\<\>]|(?R))\W*.*\W*)\>\W*){0,1}(?:(?:\{(?:(?:(?:[^\{\}]|(?R)))*)\})|(?:\<(?:(?:(?:[^\<\>]|(?R)))*)\>)|(?:\((?:(?:(?:[^\(\)]|(?R)))*)\))|(?:\[(?:(?:(?:[^\[\]]|(?R)))*)\])))))/';
    const REGEX_N = '/(?:[!#$%&=@]{0,1}([!#$%&=@]*\w+\.*\!*\w*))\W*(?:\[((?:[^\[\]]|(?R))\W*.*\W*)\]){0,1}\W*(?:\(((?:[^\(\)]|(?R))\W*.*\W*)\)){0,1}\W*(?:\<((?:[^\<\>]|(?R))\W*.*\W*)\>){0,1}\W*(?:(?:\;|\{((?:.*(?:[^\{\}]|(?R))\W*.*\W*)*)\})|(?:\<((?:.*(?:[^\<\>]|(?R)).*\W*)*)\>)|(?:\x28((?:.*(?:[^\x28\x29]|(?R)).*\W*)*)\x29)|(?:\[((?:.*(?:[^\[\]]|(?R)).*\W*)*)\]))/';
    const NAMESPC = 'l7\\elements\\';
    const TABULATION_ONOFF = true;
    public $matchesG = array();
    public $sub_matches = array();
    public $cphp_eol = '';
    public $exploder_syntaxe_parent = null;
    public $exploder_syntaxe_encaps = 0;
    private $_map_syntaxe = array();
    private $_data = '';
    private $_special_syntaxe = array();

    public function __construct(&$array_file_list)
    {
        $this->load_syntaxe($array_file_list);
    }

    private function load_syntaxe($array_file_list): void
    {
        $gestglobal = new gestionglobal();
        foreach ($array_file_list as $path => $entry) {
            $inc++;

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

    public function getdata(): string
    {
        return $this->_data;
    }

    public function start($data): void
    {
        $this->_data = trim($data);
        $this->cphp_eol = (self::TABULATION_ONOFF == false) ? '' : PHP_EOL;
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

        $data = implode($this->cphp_eol, array_map([$this, 'foreachmap'], $matchesG));

        if ($encaps > 0) {
            $encaps--;
            $this->exploder_syntaxe_encaps = $encaps;
        }

        return $data;
    }

    public function foreachmap(&$val)
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
            self::TABULATION_ONOFF
        );
        return $val;

    }


}


?>