<?php

namespace Eukaruon\modules;

use Exception;


/**
 * ce Module est utilisé avec le module habillage et objets
 * pour réalisé du PHP décorator
 * ce module est donc là pour offrir des fonctionnalités
 * différente dit fonctionnalité en amont comme tester le temps
 * d'execution du script
 */
class Modules_actions
{

    /**
     *
     */
    public function __construct()
    {
        $this->_gen_var_phpml = array();
    }

    /**
     * @param $ac_class
     * @param $ac_type
     * @param $ac_element1
     * @param $ac_element2
     * @param $ac_methode
     * @param $exploit
     * @param $inserts
     * @param $contenu
     * @return mixed
     */
    private function appel_dobjet($ac_class, $ac_type, $ac_element1, $ac_element2, $ac_methode, $exploit, $inserts, $contenu)
    {
        $maclass = '\Eukaruon\modules\Modules_' . $ac_class;
        $appel_dobjet = new  $maclass($ac_type, $ac_element1, $ac_element2, $this->_gen_var_phpml);
        return $appel_dobjet->utilise_methode($ac_class . '_' . $ac_methode, $exploit, $inserts, $contenu);
    }

    /**
     * @param bool $test
     * @param $var
     * @param $varnom
     * @param string $message
     */
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

    /**
     * @param $qui
     * @param $fichier
     * @param array $args
     * @return mixed
     */
    public static function LoadInScripts($qui, $fichier, $args = [])
    {
        $classn = get_class($qui);
        include CONTENUS . "$classn/scripts/$fichier";
        $path_parts = pathinfo($fichier);
        return new $path_parts['filename'](...$args);
    }

    /**
     *
     */
    public static function debutTempTest()
    {
        echo '<?php $time_start = microtime(true); ?>';
    }

    /**
     *
     */
    public static function finTempTest()
    {
        echo '<?php 
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "temps d\'execussion de la page: $time secondes\n" 
        ?>';
    }
}


