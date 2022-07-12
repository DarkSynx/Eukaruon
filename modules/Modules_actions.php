<?php

namespace Eukaruon\modules;

use Exception;


class Modules_actions
{

    public function __construct()
    {
        $this->_gen_var_phpml = array();
    }

    private function appel_dobjet($ac_class, $ac_type, $ac_element1, $ac_element2, $ac_methode, $exploit, $inserts, $contenu)
    {
        $maclass = '\Eukaruon\modules\Modules_' . $ac_class;
        $appel_dobjet = new  $maclass($ac_type, $ac_element1, $ac_element2, $this->_gen_var_phpml);
        return $appel_dobjet->utilise_methode($ac_class . '_' . $ac_methode, $exploit, $inserts, $contenu);
    }

    private function error_afficher(bool $test, &$var, $varnom, string $message)
    {
        try {
            if ($test) {
                throw new Exception(
                    'Erreur => [ ' . $varnom . ':' . var_export($var, true) . ' ] ' . PHP_EOL .
                    $message . PHP_EOL
                );
            }
        } catch
        (Exception $e) {
            echo 'FATAL::' . $e->getMessage();
            exit;
        }
    }

    public static function LoadInScripts($qui, $fichier, $args = [])
    {
        $classn = get_class($qui);
        include CONTENUS . "$classn/scripts/$fichier";
        $path_parts = pathinfo($fichier);
        return new $path_parts['filename'](...$args);
    }

    public static function debutTempTest()
    {
        echo '<?php $time_start = microtime(true); ?>';
    }

    public static function finTempTest()
    {
        echo '<?php 
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "temps d\'execussion de la page: $time secondes\n" 
        ?>';
    }
}


