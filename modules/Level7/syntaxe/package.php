<?php

namespace Eukaruon\modules\Level7\syntaxe;

use Eukaruon\modules\Level7\syntaxe;
use Eukaruon\modules\Level7\syntaxing;

class package extends syntaxe implements syntaxing
{

    const SPECIAL_SYNTAXE = true;
    const SPECIAL_SYNTAXE_CHAR = '@';

    private $_format = '';
    private $_contener_curly_start = '';
    private $_contener_curly_end = '';
    private $_contener_end = '';


    public function initialisation(): string
    {
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