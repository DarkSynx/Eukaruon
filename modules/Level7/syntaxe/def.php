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
	
*/

namespace Eukaruon\modules\Level7\syntaxe;

use Eukaruon\modules\Level7\syntaxe;
use Eukaruon\modules\Level7\syntaxing;

class def extends syntaxe implements syntaxing
{

    const SPECIAL_SYNTAXE = true;
    const SPECIAL_SYNTAXE_CHAR = '%';

    private $_format = '';
    private $_contener_curly_start = '';
    private $_contener_curly_end = '';
    private $_contener_end = '';

    private $_gen_val = '';


    public function initialisation(): string
    {
        $this->_use_post_work = true;
        return '';
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
        $returnvalue = '';


        // // --- SWITCH1 ---//
        switch ($this->_match_func) {
            case 'BUNDLE':
            case 'bundle':
                $this->_global_exploite->add_item_bundle_global($this->_match_curly, $this->_match_hook);
                break;
            case 'VAR':
            case 'var':
                $tab = $this->_global_exploite->get_table_global();
                //echo 'var:' . $this->_match_hook . '=' . $this->_match_curly . PHP_EOL ;

                $val_gen_type = $this->_match_curly;

                /** utilisation de
                 * def.var[foo](type){value}
                 * ou
                 * def.var[foo]{type:value}
                 * si vous utilisé des fonctions
                 * def.var[foo](type){ function }
                 * sera préférable
                 */
                //echo '=>1ok' . $val_gen_type . PHP_EOL;
                if ($this->_match_bracket) {

                    // // --- SWITCH2 ---//
                    switch ($this->_match_bracket) {
                        case 'VAR':
                        case 'var':
                            $val_gen_type = $tab[$this->_match_curly];
                            break;
                        case 'BOOL':
                        case 'bool':
                            $val_gen_type = (bool)$this->_match_curly;
                            break;
                        case 'INT':
                        case 'int':
                            $val_gen_type = (int)$this->_match_curly;
                            break;
                        case 'FLOAT':
                        case 'float':
                            $val_gen_type = (float)$this->_match_curly;
                            break;
                        case 'ARRAY':
                        case 'array': // [val1,val2,val3...]

                            $var_array = explode(',', $this->_match_curly);
                            $var_array = array_map('trim', $var_array);
                            $array_gen = array();
                            foreach ($var_array as $val) {
                                if (strstr($val, '=>') !== false) {
                                    $xp = explode('=>', $val);
                                    $array_gen[$xp[0]] = $xp[1];
                                } else {
                                    $array_gen[] = $val;
                                }
                            }
                            $val_gen_type = $array_gen; //A,B,C,D

                            break;
                        case 'str':
                        case 'STR':
                        case 'STRING':
                        case 'string':
                        default:
                            $val_gen_type = (string)$this->_match_curly;
                    }
                    // // --- ENDSWITCH2 ---//

                } else {
                    //echo '=>2ok' . $val_gen_type . PHP_EOL;
                    $type = strstr($val_gen_type, ':', true);
                    //echo 'type=>' . $type . PHP_EOL;
                    if ($type !== false) {

                        $valx = substr(strstr($val_gen_type, ':'), 1);

                        // // --- SWITCH3 ---//
                        switch ($type) {
                            case 'VAR':
                            case 'var':
                                $val_gen_type = $tab[$valx];
                                break;
                            case 'BOOL':
                            case 'bool':
                                $val_gen_type = (bool)$valx;
                                break;
                            case 'INT':
                            case 'int':
                                $val_gen_type = (int)$valx;
                                break;
                            case 'FLOAT':
                            case 'float':
                                $val_gen_type = (float)$valx;
                                break;
                            case 'ARRAY':
                            case 'array': // [val1,val2,val3...]
                                //echo 'arrayok' . PHP_EOL;
                                $var_array = explode(',', $valx);
                                $var_array = array_map('trim', $var_array);
                                $array_gen = array();
                                foreach ($var_array as $val) {
                                    if (strstr($val, '=>') !== false) {
                                        $xp = explode('=>', $val);
                                        $this->type_gen($xp[1], $tab);
                                        $array_gen[$xp[0]] = $xp[1];
                                    } else {
                                        $this->type_gen($val, $tab);
                                        $array_gen[] = $val;
                                    }
                                }
                                $val_gen_type = $array_gen; //A,B,C,D

                                break;
                            case 'str':
                            case 'STR':
                            case 'STRING':
                            case 'string':
                            default:
                                $val_gen_type = (string)$valx;
                        }
                        // // --- ENDSWITCH3 ---//

                    }


                }


                $this->_global_exploite->add_item_table_global($val_gen_type, $this->_match_hook);
                //var_dump($this->_global_exploite->get_table_global());
                break;
            // // // END CASE : DEF.VAR // // //
            case 'USE':
            case 'use':
                $returnvalue = $this->tabulations . trim($this->_global_exploite->get_bundle_value($this->_match_hook)) . $this->php_eol_ctr;
                break;
            default: // $this->_match_func
                $returnvalue = $this->tabulations . trim($this->_global_exploite->get_bundle_value($this->_match_func)) . $this->php_eol_ctr;

        }
        // // --- ENDSWITCH1 ---//

        return $returnvalue;


    }


}


?>