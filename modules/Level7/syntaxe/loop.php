<?php
/*	
	raw : func [hook] (bracket) <rafter> {curly}
	protected $_match_raw 		= '';
	protected $_match_func 		= '';
	protected $_match_hook 		= ''; // []
	protected $_match_bracket 	= ''; // ()
	protected $_match_rafter 	= ''; // <>
	protected $_match_curly 	= ''; // {}
	
	when you use foo.bar[]()<>{}
	protected $_sub_function 	= false;
	$_match_func <- subfunction
	
	&for(X;Y;Z){
	
	
	}
*/

namespace Eukaruon\modules\Level7\syntaxe;

use Eukaruon\modules\Level7\syntaxe;
use Eukaruon\modules\Level7\syntaxing;

class loop extends syntaxe implements syntaxing
{

    const SPECIAL_SYNTAXE = true;
    const SPECIAL_SYNTAXE_CHAR = '&';

    private $_format = '';
    private $_contener_curly_start = '';
    private $_contener_curly_end = '';
    private $_contener_end = '';

    private $_gen_val = '';


    public function initialisation(): string
    {
        //$this->_use_post_work = true;
        $this->work();
        return '';
    }

    public function work()
    {


        // // --- SWITCH1 ---//
        switch ($this->_match_func) {
            case 'repeat':
                $this->_match_curly = str_repeat($this->_match_curly, $this->_match_bracket);
                break;
            case 'for':
                $tab = $this->_global_exploite->get_table_global();
                $pexplod = explode(';', $this->_match_bracket);

                $detect = (
                (strpos($pexplod[1], '==') !== false) ? '==' :
                    ((strpos($pexplod[1], '!=') !== false) ? '!=' :
                        ((strpos($pexplod[1], '>=') !== false) ? '>=' :
                            ((strpos($pexplod[1], '<=') !== false) ? '<=' :
                                ((strpos($pexplod[1], '<>') !== false) ? '<>' :
                                    ((strpos($pexplod[1], '>') !== false) ? '>' :
                                        ((strpos($pexplod[1], '<') !== false) ? '<' :
                                            FALSE)))))));

                if ($detect !== FALSE) {
                    $xeval = explode($detect, $pexplod[1]);
                    $xeval = array_map('trim', $xeval);
                    $rall = PHP_EOL;
                    switch ($detect) {
                        case '===':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval === $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '!==':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval !== $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '==':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval == $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '!=':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval != $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '>=':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval >= $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '<=':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval <= $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '>':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval > $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;
                        case '<':
                            $gen = '';
                            for ($forval = $pexplod[0]; $forval < $tab[$xeval[1]]; $forval += $pexplod[2]) $gen .= $this->_match_curly . $rall;
                            $this->_match_curly = $gen;
                            break;

                    }


                }


                break;

            default:

        }


    }

    public function get_replace(): string
    {
        return $this->_contener_end;
    }

    public function get_replace_contener_start()
    {
        $this->return_start = $this->_contener_curly_start;
        $this->return_end = $this->_contener_curly_end;
    }

    public function post_work()
    {


    }
}

?>