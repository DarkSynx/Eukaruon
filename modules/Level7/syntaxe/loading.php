<?php
/*	
	raw : func [hook] (bracket) <rafter> {curly}
	protected $_match_raw 		= '';
	protected $_match_func 		= '';
	protected $_match_hook 		= '';
	protected $_match_bracket 	= '';
	protected $_match_rafter 	= '';
	protected $_match_curly 	= '';
	
	when you use foo.bar[]()<>{}
	protected $_sub_function 	= false;
	$_match_func <- subfunction
	
*/

namespace Eukaruon\modules\Level7\syntaxe;

use Eukaruon\modules\Level7\syntaxe;
use Eukaruon\modules\Level7\syntaxing;

class loading extends syntaxe implements syntaxing
{

    private $_format = '';
    private $_contener_curly_start = '';
    private $_contener_curly_end = '';
    private $_contener_end = '';


    public function initialisation(): string
    {

        $this->_match_curly = file_get_contents($this->_match_rafter);
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