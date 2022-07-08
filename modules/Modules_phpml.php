<?php

namespace Eukaruon\modules;

use DOMDocument;

class Modules_phpml
{

    private string $_data = '';

    public function __construct($data, $lien = false)
    {
        $this->_data = $data;
        $this->phpml($lien);
    }

    // CMAV: class tag
    public function phpml(bool $lien = false, $pageGenerer = '')
    {
        if ($lien) $chaine = file_get_contents(PROFILS . $this->_data);

        $dom = new DOMDocument();
        $dom->loadXML($this->_data);
        $start = $dom->getElementsByTagName('phpml')[0];

        echo '//--------------------\\<br/>';
        var_dump($this->childNodes_exploitation($start->childNodes, $pageGenerer));
        echo '//--------------------\\<br/>';

    }

    private function childNodes_exploitation($start_childNodes, $pageGenerer)
    {
        $balise = '';
        foreach ($start_childNodes as $childNode) {

            var_dump($childNode);

            if ($childNode->nodeType == 1) {

                echo '--------------------[<br/>';
                $exploit_childNode = ($childNode->childElementCount > 0 ?
                    $this->childNodes_exploitation($childNode->childNodes, $pageGenerer)
                    : $childNode->nodeValue);
                echo ']--------------------<br/>';


                $balise .= $this->generer(
                    $childNode->nodeName,
                    $this->get_attributs($childNode->attributes),
                    $exploit_childNode,
                );


                //var_dump($balise);


            } elseif ($childNode->nodeType == 3) {
                $balise .= $childNode->nodeValue;

            }


        }

        return $balise;
    }

    private function get_attributs($childNode_attributes): array
    {
        $Attributs = ['actions' => null, 'exploit' => null, 'inserts' => null, 'autres' => array()];
        if ($childNode_attributes) {
            foreach ($childNode_attributes as $attributes) {
                switch ($attributes->name) {
                    case 'actions':
                        $Attributs['actions'] = $attributes->value;
                        break;
                    case 'exploit':
                        $Attributs['exploit'] = $attributes->value;
                        break;
                    case 'inserts':
                        $Attributs['inserts'] = $attributes->value;
                        break;
                    default:
                        $Attributs['autres'][$attributes->name] = $attributes->value;
                }
            }
        }
        return $Attributs;
    }


    //_______________________________________________________
    public function appel_balise($balise, $attributs, $text)
    {
        return self::{'tag_' . $balise}($balise, $attributs, $text);
    }

    public function generer(string $balise, array $attributs, string $text): string
    {
        if (self::balise_existe($balise)) {
            return $this->appel_balise($balise, $attributs, $text);
        } else {
            $attr = '';
            foreach ($attributs['autres'] as $name_attr => $attribut) {
                $attr .= chr(32) . "$name_attr=\"$attribut\"";
            }
            return "<$balise$attr>$text</$balise>";
        }
    }

    public static function tag_test($balise, $attributs, $text): string
    {
        return '[TEST::TEST]';
    }

    public static function balise_existe($balise)
    {
        return method_exists('Eukaruon\modules\Modules_phpml', 'tag_' . $balise);
    }

}