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

class php extends syntaxe implements syntaxing
{

    private $_format = '';
    private $_contener_curly_start = '<?php';
    private $_contener_curly_end = '?>';
    private $_contener_end = '?>';

    private $_gen_val = '';


    public function initialisation(): string
    {
        $tab = $this->_global_exploite->get_table_global();

        $injectvar = '';

        if ($this->_match_bracket == 'table_global') {
            $var_export = str_replace(["\n", chr(32)], '', var_export($tab, true));
            $injectvar .= $this->tabulations . '$TABLE_GLOBAL = ' . $var_export . ';' . $this->php_eol_ctr;
        }

        if ($this->_match_hook) { //echo 'ok' . PHP_EOL;

            $get_var_in_hook = explode(',', $this->_match_hook);
            foreach ($get_var_in_hook as $val) {
                $var_export = str_replace(["\n", chr(32)], '', var_export($tab[$val], true));
                $injectvar .= $this->tabulations . "$$val = " . $var_export . ';' . $this->php_eol_ctr;

            }

        }


        $this->_gen_val = chr(32) . $this->php_eol_ctr . $injectvar . $this->tabulations . $this->_match_curly;


        $this->_match_curly = '';
        $this->_del_match_curly = true;
        return '';
    }

    public function get_replace(): string
    {
        return $this->_contener_end;
    }

    public function get_replace_contener_start()
    {
        $this->return_start = $this->_contener_curly_start . $this->_gen_val;
        $this->return_end = $this->_contener_curly_end;
    }


}

?>