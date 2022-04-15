<?php

namespace Eukaruon\modules;

class Modules_actions
{


    protected $_head = "<!--- php";
    protected $_foot = "--->";

    public function __construct()
    {

    }

    public static function activiter(string $nom_fonction, array $arguments = [])
    {

        foreach ($arguments as $k => $v) {
            if ($v[0] != '$') $v = "'$v'";
            $arguments[$k] = $v;
        }
        $fc_arguments = implode(',', $arguments);
        /* echo "<?php $nom_fonction($fc_arguments); ?>";*/
        echo "<?='ok'; ?>";
    }
}