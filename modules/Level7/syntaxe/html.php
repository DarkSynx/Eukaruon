<?php
/*	

	protected $_encaps 		= '';

	raw : func [hook] (bracket) <rafter> {curly}
	protected $_match_raw 		= '';
	protected $_match_func 		= '';
	protected $_match_hook 		= '';
	protected $_match_bracket 	= '';
	protected $_match_rafter 	= '';
	protected $_match_curly 	= '';
	
	// if you wana delet value in match
	protected $_del_match_raw 		= false;
	protected $_del_match_func 		= false;	
	protected $_del_match_hook 		= false;	
	protected $_del_match_bracket 	= false;	
	protected $_del_match_rafter 	= false;	
	protected $_del_match_curly 	= false;	

	// if you wana change value in match
	protected $_change_match_raw 		= '';
	protected $_change_match_func 		= '';
	protected $_change_match_hook 		= '';
	protected $_change_match_bracket 	= '';
	protected $_change_match_rafter 	= '';
	protected $_change_match_curly 		= '';

	
	when you use foo.bar[]()<>{}
	protected $_sub_function 	= false;
	$_match_func <- subfunction
	
*/

namespace Eukaruon\modules\Level7\syntaxe;

use Eukaruon\modules\Level7\syntaxe;
use Eukaruon\modules\Level7\syntaxing;

class html extends syntaxe implements syntaxing
{

    const SPECIAL_SYNTAXE = true;
    const SPECIAL_SYNTAXE_CHAR = '#';

    private $_format = '';
    private $_contener_curly_start = '<';
    private $_contener_curly_end = '</';
    private $_contener_end = '>';
    private $_tag = [
        '!doctype' => 2, 'abbr' => 0, 'acronym' => 0, 'address' => 0, 'a' => 0, 'applet' => 0, 'area' => 0, 'b' => 0,
        'base' => 0, 'basefont' => 0, 'bdo' => 0, 'bgsound' => 0, 'big' => 0, 'blink' => 0, 'blockquote' => 0, 'body' => 0,
        'br' => 0, 'button' => 0, 'caption' => 0, 'center' => 0, 'cite' => 0, 'code' => 0, 'col' => 0, 'colgroup' => 0,
        'commment' => 0, 'dd' => 0, 'del' => 0, 'dfn' => 0, 'dir' => 0, 'div' => 0, 'dl' => 0, 'dt' => 0,
        'em' => 0, 'embed' => 0, 'fieldset' => 0, 'font' => 0, 'form' => 0, 'frame' => 0, 'frameset' => 0, 'h1' => 0,
        'h2' => 0, 'h3' => 0, 'h4' => 0, 'h5' => 0, 'h6' => 0, 'head' => 0, 'hr' => 0, 'html' => 0,
        'i' => 0, 'iframe' => 0, 'img' => 0, 'input' => 2, 'ins' => 0, 'isindex' => 0, 'kbd' => 0, 'label' => 0,
        'layer' => 0, 'legend' => 0, 'li' => 0, 'link' => 0, 'map' => 0, 'marquee' => 0, 'menu' => 0, 'meta' => 0,
        'nextid' => 0, 'nobr' => 0, 'noembed' => 0, 'noframes' => 0, 'noscript' => 0, 'object' => 0, 'ol' => 0, 'option' => 0,
        'p' => 0, 'param' => 0, 'pre' => 0, 'q' => 0, 's' => 0, 'samp' => 0, 'script' => 0, 'select' => 0,
        'small' => 0, 'span' => 0, 'strike' => 0, 'strong' => 0, 'style' => 0, 'sub' => 0, 'sup' => 0, 'table' => 0,
        'tbody' => 0, 'td' => 0, 'textarea' => 0, 'tfoot' => 0, 'th' => 0, 'thead' => 0, 'title' => 0, 'tr' => 0,
        'tt' => 0, 'u' => 0, 'ul' => 0, 'var' => 0, 'wbr' => 0, 'xmp' => 0
    ];

    public function initialisation(): string
    {
        return '';
    }

    public function get_replace(): string
    {
        return $this->html_tag();
    }

    private function html_tag($position = '', $gen = '')
    {
        //switch($this->_match_func) {
        //var_dump($this->_match_func);
        //var_dump($this->_tag[$this->_match_func]);
        if ($this->_tag[$this->_match_func] == 0) { //0 -> <tag elements></tag>

            if ($position == 'start') {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end;
            } else if ($position == 'end') {
                return $this->_contener_curly_end . $this->_match_func . $this->_contener_end;
            } else {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end . $this->_contener_curly_end . $this->_match_func . $this->_contener_end;
            }
        } else if ($this->_tag[$this->_match_func] == 1) { // 1 -> <tag elements />

            if ($position == 'start') {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end;
            } else if ($position == 'end') {
                return $this->_contener_curly_end . $this->_match_func . $this->_contener_end;
            } else {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end . $this->_contener_curly_end . $this->_match_func . $this->_contener_end;
            }

        } else { //  -> <tag elements>

            if ($position == 'start') {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end;
            } else if ($position == 'end') {
                return '';
            } else {
                return $this->_contener_curly_start . $this->_match_func . ($this->_match_bracket != '' ? chr(32) . $this->_match_bracket : '') . $this->_contener_end;
            }

        }

        return $gen;
    }


    /*
        0 -> <tag elements></tag>
        1 -> <tag elements />
        2 -> <tag elements>
    */

    public function get_replace_contener_start()
    {
        $this->return_start = $this->html_tag('start');
        $this->return_end = $this->html_tag('end');
    }
}

?>