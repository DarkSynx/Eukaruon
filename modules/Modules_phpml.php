<?php

namespace Eukaruon\modules;

use DOMDocument;

/**
 * ce module comme le module L7 permet de créé via la syntaxe XML un code
 * PHP et HTML sous la même syntaxe c'est à vous de propose l'implémentation
 * du code qui sera interpréter pour le moment ce module est expérimental
 * dans le futur il sera certainement plus complet avec des functions de base
 * exploitable come IF SWITCH VAR etc...
 */
class Modules_phpml
{

    /**
     * @var string
     */
    private string $_data = '';
    /**
     * @var string
     */
    private string $_gen_data = '';

    /**
     * @param $data
     * @param false $lien
     */
    public function __construct($data, $lien = false)
    {
        $this->_data = $data;
        $this->_gen_data = $this->phpml($lien);
    }

    /** permet d'obtenir la donné produite
     * votre code PHPML interpréter sera contenu dans
     * _gen_data
     * @return string
     */
    public function get_gen_data(): string
    {
        return $this->_gen_data;
    }

    // CMAV: class tag

    /** permet d'interpréter la donné PHPML ou un lien vers cette donnée
     * @param bool $lien_utiliser
     * @param string $pageGenerer
     * @return string
     */
    public function phpml(bool $lien_utiliser = false, $pageGenerer = ''): string
    {
        if ($lien_utiliser) $this->_data = file_get_contents($this->_data);

        $dom = new DOMDocument();
        $dom->loadXML($this->_data);
        $start = $dom->getElementsByTagName('phpml')[0];

        return $this->childNodes_exploitation($start->childNodes, $pageGenerer);

    }

    /** permet d'exploité et d'auto exploité en boucle les enfants d'une balise
     * @param $start_childNodes
     * @param $pageGenerer
     * @return string
     */
    private function childNodes_exploitation($start_childNodes, $pageGenerer)
    {
        $balise = '';
        foreach ($start_childNodes as $childNode) {
            //var_dump($childNode);
            if ($childNode->nodeType == 1) {
                //echo '--------------------[<br/>';
                $exploit_childNode = ($childNode->childElementCount > 0 ?
                    $this->childNodes_exploitation($childNode->childNodes, $pageGenerer)
                    : $childNode->nodeValue);
                //echo ']--------------------<br/>';
                $balise .= $this->generer(
                    $childNode->nodeName,
                    $this->get_attributs($childNode->attributes),
                    $exploit_childNode,
                );

            } elseif ($childNode->nodeType == 3) {
                $balise .= $childNode->nodeValue;
            }
        }
        return $balise;
    }

    /** permet de récupérer les attributs d'une balise avec une priorité pour
     * actions exploit et inserts les autres seront misent dans une sous cathégorie autre => ...
     * @param $childNode_attributes
     * @return array
     */
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

    /** permet d'appeler la fonction en lien avec la balise
     * pour créé une balise qui sera interpréter il faudra
     * la nommer tag_LeNomDeMaBalise
     * tag_ permettra de pas proposé de nom de fonction interdit en PHP
     * ou similaire aux fonction utilisé dans cette class
     * @param $balise
     * @param $attributs
     * @param $text
     * @return mixed
     */
    public function appel_balise($balise, $attributs, $text)
    {
        return self::{'tag_' . $balise}($balise, $attributs, $text);
    }

    /** créé une balise
     * "[$balise $attr]$text[/$balise]"
     * @param string $balise
     * @param array $attributs
     * @param string $text
     * @return string
     */
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

    /** implémentation test réalisé
     * si vous voulez créé une balise c'est comme si dessous
     * ce n'est donc pas trés compliqué à réalisé
     * @param $balise
     * @param $attributs
     * @param $text
     * @return string
     */
    public static function tag_test($balise, $attributs, $text): string
    {
        return '[TEST::TEST]';
    }

    /** vérificateur de si la balise existe ou pas
     * @param $balise
     * @return bool
     */
    public static function balise_existe($balise)
    {
        return method_exists('Eukaruon\modules\Modules_phpml', 'tag_' . $balise);
    }

}