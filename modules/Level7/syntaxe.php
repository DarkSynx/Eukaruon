<?php

namespace Eukaruon\modules\Level7;

/**
 *
 */
class syntaxe
{

    /**
     *
     */
    const SPECIAL_SYNTAXE = false;
    /**
     *
     */
    const SPECIAL_SYNTAXE_CHAR = '';

    /**
     * @var string
     */
    public string $return_pre_initialisation = '';

    /**
     * @var string
     */
    public string $return_start = '';
    /**
     * @var string
     */
    public string $return_end = '';


    /**
     * @var string
     */
    public string $tabulations = '';
    /**
     * @var string
     */
    public string $php_eol_ctr = '';

    /**
     * @var bool
     */
    protected bool $_tab_actived = false;
    /**
     * @var int
     */
    protected int $_encaps = 0;

    /**
     * @var string|null
     */
    protected null|string $_match_raw = '';
    /**
     * @var string|null
     */
    protected null|string $_match_func = '';
    /**
     * @var string|null
     */
    protected null|string $_match_sub_func = '';
    /**
     * @var string|null
     */
    protected null|string $_match_hook = '';
    /**
     * @var string|null
     */
    protected null|string $_match_bracket = '';
    /**
     * @var string|null
     */
    protected null|string $_match_rafter = '';
    /**
     * @var string|null
     */
    protected null|string $_match_curly = '';

    /**
     * @var bool
     */
    protected bool $_del_match_raw = false;
    /**
     * @var bool
     */
    protected bool $_del_match_func = false;
    /**
     * @var bool
     */
    protected bool $_del_match_hook = false;
    /**
     * @var bool
     */
    protected bool $_del_match_bracket = false;
    /**
     * @var bool
     */
    protected bool $_del_match_rafter = false;
    /**
     * @var bool
     */
    protected bool $_del_match_curly = false;


    /**
     * @var string
     */
    protected string $_change_match_raw = '';
    /**
     * @var string
     */
    protected string $_change_match_func = '';
    /**
     * @var string
     */
    protected string $_change_match_hook = '';
    /**
     * @var string
     */
    protected string $_change_match_bracket = '';
    /**
     * @var string
     */
    protected string $_change_match_rafter = '';
    /**
     * @var string
     */
    protected string $_change_match_curly = '';

    /**
     * @var bool
     */
    protected bool $_use_post_work = false;


    /**
     * @var bool
     */
    protected bool $_sub_function = false;

    /**
     * @var
     */
    protected $_my_parent;

    /**
     * @var
     */
    protected $_global_exploite;

    /**
     * @param $global_exploite
     */
    public function __construct($global_exploite)
    {
        $this->_global_exploite = $global_exploite;
    }

    /**
     * @param $thisisit
     * @param string $matchesG
     * @param int|string|null $subfonction
     * @param syntaxe|null $parent
     * @param $encaps
     * @param $tab_actived
     * @return bool
     */
    public function pre_initialisation(&$thisisit, string &$matchesG, null|int|string &$subfonction, self|null &$parent, &$encaps, $tab_actived): bool
    {


        if (get_class($this) == ($thisisit::NAMESPC . 'notexcute')) {
            $matchesG = '';
            return false;
        }


        $this->_encaps = &$encaps;
        $this->_tab_actived = &$tab_actived;
        $this->php_eol_ctr = &$thisisit->cphp_eol;
        $this->_my_parent = &$parent;

        $this->tabulations = ($tab_actived ? str_repeat("\t", $encaps) : '');


        //var_dump($matche);
        $this->_match_raw = &$thisisit->sub_matches[0];
        $this->_match_func = &$thisisit->sub_matches[1];
        $this->_match_hook = &$thisisit->sub_matches[2];
        $this->_match_bracket = &$thisisit->sub_matches[3];
        $this->_match_rafter = &$thisisit->sub_matches[4];
        $this->_match_curly = &$thisisit->sub_matches[5];


        if ($subfonction) {
            $this->_match_func =  &$subfonction;
            $this->_sub_function = true;
        } else {
            $this->_sub_function = false;
        }


        $matchesG = $this->initialisation();


        if ($this->_del_match_raw) {
            $this->_match_raw = '';
            $thisisit->sub_matches[0] = '';
        }
        if ($this->_del_match_func) {
            $this->_match_func = '';
            $thisisit->sub_matches[1] = '';
        }
        if ($this->_del_match_hook) {
            $this->_match_hook = '';
            $thisisit->sub_matches[2] = '';
        }
        if ($this->_del_match_bracket) {
            $this->_match_bracket = '';
            $thisisit->sub_matches[3] = '';
        }
        if ($this->_del_match_rafter) {
            $this->_match_rafter = '';
            $thisisit->sub_matches[4] = '';
        }
        if ($this->_del_match_curly) {
            $this->_match_curly = '';
            $thisisit->sub_matches[5] = '';
        }

        if ($this->_change_match_raw != '') {
            $this->_match_raw = $this->_change_match_raw;
            $thisisit->sub_matches[0] = &$this->_change_match_raw;
        }
        if ($this->_change_match_func != '') {
            $this->_match_func = $this->_change_match_func;
            $thisisit->sub_matches[1] = &$this->_change_match_func;
        }
        if ($this->_change_match_hook != '') {
            $this->_match_hook = $this->_change_match_hook;
            $thisisit->sub_matches[2] = &$this->_change_match_hook;
        }
        if ($this->_change_match_bracket != '') {
            $this->_match_bracket = $this->_change_match_bracket;
            $thisisit->sub_matches[3] = &$this->_change_match_bracket;
        }
        if ($this->_change_match_rafter != '') {
            $this->_match_rafter = $this->_change_match_rafter;
            $thisisit->sub_matches[4] = &$this->_change_match_rafter;
        }
        if ($this->_change_match_curly != '') {
            $this->_match_curly = $this->_change_match_curly;
            $thisisit->sub_matches[5] = &$this->_change_match_curly;
        }


        $this->gest_gen_contener_matchesG_ternair($thisisit, $matchesG);

        return true;
    }

    /** permet la gestion d'un projet L7
     * @param $l7_obj_used
     * @param $thematchesg
     */
    public function gest_gen_contener_matchesG_ternair(&$l7_obj_used, &$thematchesg): void
    {

        /* load la méthode de votre objet
        par exemple html->get_replace_contener_start()
        */
        $this->get_replace_contener_start();

        /* on doit générer $end par ce que sinon celui-ci peut ce retrouver
        avec les valeur d'une autre analyse par $l7_obj_used->exploder_syntaxe
        */
        $end = $this->gen_end();
        $endx = $this->gen_endx();

        /* ici cette fonctionalité prépare $thematchesg qui est
        $matchesG[0][key] ou val avec gen_start comme debut
        exemple <debut> et end </fin>
        entre les deux $l7_obj_used->exploder_syntaxe
        permet de continuer l'analyse des données à incorporer
        cela permet aussi de switcher sur 2 cas de figure
        la présentation <debut contenu > et <debut> contenu</fin>
        */

        $gen = (isset($l7_obj_used->sub_matches[5]) ?
            ($this->gen_start() .
                (
                ($l7_obj_used->sub_matches[5] == '') ?
                    $endx :
                    $l7_obj_used->exploder_syntaxe($this->_match_curly, $this, ($this->_encaps + 1), $this->tabulations) . $end
                )
            ) : ($this->tabulations . $this->get_replace()));

        if ($this->_use_post_work) {
            $gen = $this->post_work();
        }

        $thematchesg .= $gen;

    }

    /**
     * @return string
     */
    public function gen_end(): string
    {
        return ($this->return_end == '' ? '' : ($this->php_eol_ctr . $this->tabulations . $this->return_end));
    }

    /**
     * @return string
     */
    public function gen_endx(): string
    {
        return ($this->return_end == '' ? '' : ($this->tabulations . $this->return_end));
    }

    /**
     * @return string
     */
    public function gen_start(): string
    {
        return $this->return_start == '' ? '' : $this->tabulations . $this->return_start . $this->php_eol_ctr;
    }

    /**
     * @return mixed
     */
    public function get_gtable()
    {
        return $this->_global_exploite->get_table_global();
    }

    /**
     * @param string $val
     * @param string $key
     * @return mixed
     */
    public function add_gtable($val = '', $key = '')
    {
        return $this->_global_exploite->add_item_table_global($val, $key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function rmv_gtable($key = '')
    {
        return $this->_global_exploite->remove_item_table_global($key);
    }

    /**
     * @param string $data
     * @param array $tab
     * @return bool|string|null
     */
    protected function analyse(&$data = '', &$tab = array())
    {

        //$data = 'var:test == var:test1 and var:test2 == var:test3 or var:test4 != var:test5';
        $datafmt = explode('[#]', str_replace(['or', 'and'], '[#]', $data));
        $count_data = count($datafmt) - 1;


        $logique = array();
        $b = 0;
        for ($a = 0, $b = 0; $a < $count_data; $a = $b) {
            $b = strpos($data, chr(32) . 'or' . chr(32), $a);
            $logique[$b] = 'or';
        }
        for ($a = 0, $b = 0; $a < $count_data; $a = $b) {
            $b = strpos($data, chr(32) . 'and' . chr(32), $a);
            $logique[$b] = 'and';
        }

        ksort($logique);
        $logique = array_values($logique);


        foreach ($datafmt as &$val) {

            $detect = (
            (str_contains($val, '==')) ? '==' :
                ((str_contains($val, '!=')) ? '!=' :
                    ((str_contains($val, '>=')) ? '>=' :
                        ((str_contains($val, '<=')) ? '<=' :
                            ((str_contains($val, '<>')) ? '<>' :
                                ((str_contains($val, '>')) ? '>' :
                                    ((str_contains($val, '<')) ? '<' :
                                        ((str_contains($val, '%')) ? '%' : FALSE))))))));

            if ($detect !== FALSE) {

                //echo "\tok:1". PHP_EOL;
                $p = explode('[#]', str_replace(['===', '!==', '==', '!=', '>=', '<=', '<>', '>', '<', '%'], '[#]', $val));
                $p = array_map('trim', $p);
                //print_r($p);

                foreach ($p as &$v) {
                    $this->type_gen($v, $tab);
                }


                //echo "\t\t\t\tok:4 > " . $detect . PHP_EOL;

                switch ($detect) {
                    case '===':
                        $val = ($p[0] === $p[1]);
                        break;
                    case '!==':
                        $val = ($p[0] !== $p[1]);
                        break;
                    case '==':
                        $val = ($p[0] == $p[1]);
                        //echo "\t\t\t\t\tok:5 > " . $p[0] . '==' . $p[1] . '->' . $rt . PHP_EOL;
                        break;
                    case '!=':
                        $val = ($p[0] != $p[1]);
                        break;
                    case '>=':
                        $val = ($p[0] >= $p[1]);
                        break;
                    case '<=':
                        $val = ($p[0] <= $p[1]);
                        break;
                    case '<>':
                        $val = ($p[0] <> $p[1]);
                        break;
                    case '>':
                        $val = ($p[0] > $p[1]);
                        break;
                    case '<':
                        $val = ($p[0] < $p[1]);
                        break;
                    case '%':
                        $val = ($p[0] % $p[1]);
                        break;
                }

            }

        }

        $retenu = null;
        $p1 = null;
        $p2 = null;
        $cl = null;
        $cli = 0;
        foreach ($datafmt as &$val) {
            if (is_null($p1)) {
                $p1 = $val;
            } else {
                $p2 = $val;
            }
            if (array_key_exists($cli, $logique)) {
                $cl = $logique[$cli];
            }
            if (!is_null($p1) and !is_null($p2) and !is_null($cl)) {

                if ($cl == 'or') {
                    $p1 = ($p1 || $p2);
                } else {
                    $p1 = ($p1 && $p2);
                }

                $p2 = null;
                $cl = null;
                $cli++;
            }
        }

        //print_r($datafmt);

        return $p1;

    }

    /**
     * @param $v
     * @param $tab
     */
    protected function type_gen(&$v, &$tab)
    {


        $type = strstr($v, ':', true);


        if ($type !== false) {
            $valx = trim(substr(strstr($v, ':'), 1));
            //echo "\t\tok:2 > " . $type . PHP_EOL;

            switch ($type) {
                case 'VAR':
                case 'var':
                    //echo "\t\t\tok:3". PHP_EOL;
                    $v = $tab[$valx];
                    //print_r($p);
                    //echo '-------------' . PHP_EOL;
                    break;
                case 'INT':
                case 'int':
                    $v = (int)$valx;
                    break;
                case 'BOOL':
                case 'bool':
                    $v = (bool)$valx;
                    break;
                case 'FLOAT':
                case 'float':
                    $v = (float)$valx;
                    break;
                case 'ARRAY':
                case 'array': // [val1,val2,val3...]
                    $var_array0 = explode(",", $valx);
                    $var_array1 = array_map("trim", $var_array0);
                    $array_gen = [];
                    foreach ($var_array1 as $val) {
                        if (strstr($val, '=>') !== false) {
                            $xp = explode('=>', $val);
                            $this->type_gen($xp[1], $tab);
                            $array_gen[$xp[0]] = $xp[1];
                        } else {
                            $this->type_gen($val, $tab);
                            $array_gen[] = $val;
                        }
                    }
                    $v = $array_gen; //A,B,C,D

                    break;
                case 'str':
                case 'STR':
                case 'STRING':
                case 'string':
                default:
                    $v = (string)$valx;
            }

        }
    }

}

?>