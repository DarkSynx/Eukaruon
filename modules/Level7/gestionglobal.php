<?php

namespace Eukaruon\modules\Level7;

class gestionglobal
{

    public $table_global = array(); // var
    public $bundle_global = array(); // function

    public function __construct()
    {
        //echo 'load: gestionglobal' . PHP_EOL;
    }

    public function get_table_global()
    {
        return $this->table_global;
    }

    public function add_item_table_global($val = '', $key = '')
    {
        if ($key == '') {
            $this->table_global[] = $val;
        } else {
            $this->table_global[$key] = $val;
        }
    }

    public function remove_item_table_global($key)
    {
        unset($this->table_global[$key]);
    }

    public function get_bundle_global()
    {
        return $this->bundle_global;
    }

    public function get_bundle_value($key)
    {
        return $this->bundle_global[$key];
    }

    public function add_item_bundle_global($val = '', $key = '')
    {
        if ($key) {
            $this->bundle_global[$key] = $val;
        }
    }

    public function remove_item_bundle_global($key)
    {
        unset($this->bundle_global[$key]);
    }

}

?>