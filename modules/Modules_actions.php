<?php

namespace Eukaruon\modules;

use DOMDocument;
use Exception;


class Modules_actions
{


    protected $_head = "<!--- php";
    protected $_foot = "--->";

    // REGEX 1
    const DETECT = '/(*UCP)<phpml\s*(?|(?#
------------------------------------------------------------------------------
)(?:\s*actions="(?:(?(R)\w++|[^""]*+)|(?R))*"\s*)?(?#
)(?:\s*exploit="(?:(?(R)\w++|[^""]*+)|(?R))*"\s*)?|(?#
------------------------------------------------------------------------------
)\s*:\w+\.?\w*(?|\@\w+(?:\#\w+)?|\((?:(?(R)\w++|[^()]*+)|(?R))*\))?\s*(?#
)(?:="(?:(?(R)\w++|[^""]*+)|(?R))*"\s*)?)(?#
------------------------------------------------------------------------------
)(?:inserts="(?:(?(R)\w++|[^""]*+)|(?R))*"\s*)?(?#
------------------------------------------------------------------------------
)\s*>(?:(?(R)\w++|[^><]*+)|(?R))*<\/phpml>|(?#
------------------------------------------------------------------------------
)<\w*\s*[[:word:][:cntrl:][:blank:]+*-="\'\/\\\\.,;:[\](){}\x20?#&%|@]*\s*>|(?#
------------------------------------------------------------------------------
)<!--\s*[[:word:][:cntrl:][:blank:]+*-="\'\/\\\\.,;:[\](){}\x20?#&%|@]*\s*-->|(?#
------------------------------------------------------------------------------
)/';

    // REGEX 2
    const SECTION = '/(*UCP)<phpml\s*(?|(?#
------------------------------------------------------------------------------
)(?:\s*actions="((?:(?(R)\w++|[^""]*+)|(?R))*)"\s*)?(?#
)(?:\s*exploit="((?:(?(R)\w++|[^""]*+)|(?R))*)"\s*)?|(?#
------------------------------------------------------------------------------
)\s*:(\w+\.?\w*(?|\@\w+(?:\#\w+)?|\((?:(?(R)\w++|[^()]*+)|(?R))*\))?)\s*(?#
)(?:="((?:(?(R)\w++|[^""]*+)|(?R))*)"\s*)?)(?#
------------------------------------------------------------------------------
)(?:inserts="((?:(?(R)\w++|[^""]*+)|(?R))*)"\s*)?(?#
------------------------------------------------------------------------------
)\s*>((?:(?(R)\w++|[^><]*+)|(?R))*)<\/phpml>|(?#
------------------------------------------------------------------------------
)<\w*\s*[[:word:][:cntrl:][:blank:]+*-="\'\/\\\\.,;:[\](){}\x20?#&%|@]*\s*>|(?#
------------------------------------------------------------------------------
)<!--\s*[[:word:][:cntrl:][:blank:]+*-="\'\/\\\\.,;:[\](){}\x20?#&%|@]*\s*-->|(?#
------------------------------------------------------------------------------
)/';

    const ACTIONS = '/(*UCP)(\w+)(?:\.(\w*))?(?|(\@)(\w+)(?:\#(\w+))?|(\()(.*)\))?/';

    private array $_gen_var_phpml;

    protected $_contenu_final = ""; // va contenir l'interprétation du contenu

    public function __construct()
    {
        $this->_gen_var_phpml = array();
    }

    // CMAV: class tag
    public function ctag2(string $chaine, bool $lien = false)
    {
        $fonctions = ['if', 'phpml'];


        $dom = new DOMDocument();

        $dom->loadXML($chaine);
        $start = $dom->getElementsByTagName('phpml')[0];

        foreach ($start->childNodes as $childNode) {
            // var_dump($childNode);
            echo $childNode->nodeName, PHP_EOL, ' | ';
            echo $childNode->getAttribute('actions'), PHP_EOL, ' | ';
            echo $childNode->getAttribute('exploit'), PHP_EOL, ' | ';
            echo $childNode->getAttribute('inserts'), PHP_EOL, '<hr/>';
        }


    }

    // CMAV: class tag
    public function ctag(string $chaine, bool $lien = false)
    {
        // var_dump($chaine);
        if ($lien) $chaine = file_get_contents(PROFILS . $chaine);

        $chaine_construction = $this->exploiter_les_lots($chaine);
        var_dump($chaine_construction);
    }

    private function exploiter_les_lots($chaine)
    {
        $tableau = array();
        preg_match_all(self::DETECT, $chaine, $matches, PREG_SET_ORDER, 0);
        unset($chaine);

        //var_dump($matches);
        $chaine_construction = '';
        foreach ($matches as $cle => $valeur) {

            if (str_starts_with($valeur[0], '<phpml') == true) {
                //var_dump($valeur[0]);
                $chaine_construction .= $this->exploiter_un_lot($valeur[0]);
            } else {
                $chaine_construction .= $valeur[0];
                unset($matches[$cle]);
            }
        }
        return $chaine_construction;
    }

    private function exploiter_un_lot($data)
    {
        $matches = array();
        preg_match(self::SECTION, $data, $matches, PREG_OFFSET_CAPTURE, 0);

        var_dump($matches);
        $actions = $matches[1][0];
        $exploit = $matches[2][0];
        $inserts = $matches[3][0];
        $contenu = $matches[4][0];

        // A C T I O N S ====================================================
        $this->error_afficher(
            $actions == '' || $actions == null,
            $actions,
            'action',
            'l\'argument actions phpml:objet.methode ou phpml actions="objet.methode est inexistant');

        preg_match(self::ACTIONS, $actions, $matches_actions, PREG_OFFSET_CAPTURE, 0);
        var_dump($matches_actions);

        $ac_class = $matches_actions[1][0] ?? null;
        $ac_methode = $matches_actions[2][0] ?? null;
        $ac_type = $matches_actions[3][0] ?? null; // @ ou (
        $ac_element1 = $matches_actions[4][0] ?? null; // @element1 ou (element1)
        $ac_element2 = $matches_actions[5][0] ?? null; // @element1#element2

        /* forme normal
        forme 8 : class.methode@element1#element2
        forme 7 : class.methode@element1
        forme 6 : class.methode(element1)
        forme 5 : class.methode

        forme reduite
        forme 4 : methode@element1#element2
        forme 3 : methode@element1
        forme 2 : methode(element1)
        forme 1 : methode
        */

        switch (true) {
            case (($ac_methode != null || $ac_methode != '')):
                echo "<br/>forme normal :<br/>";
                //forme normal

                $retour = $this->appel_dobjet($ac_class, $ac_type, $ac_element1, $ac_element2, $ac_methode, $exploit, $inserts, $contenu);
                break;

            case (($ac_methode == null || $ac_methode == '')):
                echo "<br/>forme reduite :<br/>";
                //forme reduite

                $retour = $this->appel_dobjet('phpml', $ac_type, $ac_element1, $ac_element2, $ac_class, $exploit, $inserts, $contenu);
                break;

            default:
                echo "<br/>erreur:<br/>";
                $this->error_afficher(
                    true,
                    $actions,
                    'action',
                    'erreur dans la conception de actions celui-ci doit etre sous ces 5 formes : 
                    <br>  class.methode@element1#element2
                    <br>  class.methode@element1
                    <br>  class.methode(element)
                    <br>  class.methode
                    <br>  methode -> appel phpml.methode
                    ');


        }

        if (is_array($retour) && $retour[0] == '_gen_var_phpml') {
            $this->_gen_var_phpml[$retour[1]] = $retour[2];
            return '';
        }

        return $retour;
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


