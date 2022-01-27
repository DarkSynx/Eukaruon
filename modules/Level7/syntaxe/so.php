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

class so extends syntaxe implements syntaxing
{

    const REGEX_G = '/(?:(?:.+\W*)(?:(?:\;.*)|(?:(?:\[(?:(?:[^\[\]]|(?R))\W*.*\W*)\]\W*){0,1}(?:\((?:(?:[^\(\)]|(?R))\W*.*\W*)\)\W*){0,1}(?:\<(?:(?:[^\<\>]|(?R))\W*.*\W*)\>\W*){0,1}(?:(?:\{(?:(?:(?:[^\{\}]|(?R)))*)\})|(?:\<(?:(?:(?:[^\<\>]|(?R)))*)\>)|(?:\((?:(?:(?:[^\(\)]|(?R)))*)\))|(?:\[(?:(?:(?:[^\[\]]|(?R)))*)\])))))/';


    private $_format = '';
    private $_contener_curly_start = '';
    private $_contener_curly_end = '';
    private $_contener_end = '';

    private $_gen_val = '';


    public function initialisation(): string
    {

        if ($this->_match_rafter) {


            $tab = $this->_global_exploite->get_table_global();

            preg_match_all(self::REGEX_G, $this->_match_curly, $matche);
            list($ptrue, $pfalse) = array_map('trim', $matche[0]);
            //list($ptrue, $pfalse) = explode('||',trim($this->_match_curly));

            $this->_match_curly = $this->analyse($this->_match_rafter, $tab) ? $ptrue : $pfalse;

        } else {
            //$this->_global_exploite->add_item_table_global($this->_match_curly,$this->_match_hook);
            var_dump($this->_global_exploite->get_table_global());
            $this->_match_curly = '';
        }
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


}

?>