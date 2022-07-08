<?php namespace Eukaruon\modules;

class Modules_habillage
{

    protected $value;
    protected $injection;
    protected $arbre_noms;

    private string $_big_data = '';

    private array $liste_tag =
        ['a', 'abbr', 'acronym', 'address', 'applet', 'area', 'article', 'aside',
            'audio', 'b', 'base', 'basefont', 'bdi', 'bdo', 'big', 'blockquote', 'body', 'button', 'canvas', 'caption',
            'center', 'cite', 'code', 'col', 'colgroup', 'data', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog',
            'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'font', 'footer', 'form',
            'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'html', 'i', 'iframe', 'ins',
            'kbd', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'meter', 'nav', 'noframes', 'noscript',
            'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'picture', 'pre', 'progress', 'q', 'rp', 'rt',
            'ruby', 's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'style',
            'sub', 'summary', 'sup', 'svg', 'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time',
            'title', 'tr', 'track', 'tt', 'u', 'ul', 'var', 'video', 'wbr'];
    private array $liste_tag_exception = ['img', '!DOCTYPE', 'br', 'hr', 'input', 'link', 'meta'];

    public function __construct(array $value = ['', 0], array $injection = [], string $arbre_noms = '')
    {
        $this->arbre_noms = $arbre_noms;
        $this->injection = $injection;
        $this->value = $value;
    }


    public function appliquer($function, array|string|null $argument = array(), $no_aid = false): static
    {
        // call_user_func => $function($this->value)
        //return static::of(call_user_func($function,$this->value));
        /* if (is_array($argument) && !array_key_exists('data-aid', $argument)) {
             $argument['data-aid'] = 'atid_' . $function . '_' . time();
         }*/

        //      if ($function == 'text') {
        //            return static::de(call_user_func_array([$this, 'text'], [$this->value, $this->injection, $this->arbre_noms, $argument]));
        //        } elseif ($function == 'text_apres') {
        //            return static::de(call_user_func_array([$this, 'text_apres'], [$this->value, $this->injection, $this->arbre_noms, $argument]));
        //        } elseif (in_array($function, $this->liste_tag)) {
        //            return static::de(call_user_func_array([$this, 'tag'], [$function, $this->value, $this->injection, $this->arbre_noms, $argument]));
        //        } elseif (in_array($function, $this->liste_tag_exception)) {
        //            return static::de(call_user_func_array([$this, 'tag_exception'], [$function, $this->value, $this->injection, $this->arbre_noms, $argument]));
        //        } else {
        //            return static::de(call_user_func_array([$this, $function], [$this->value, $this->injection, $this->arbre_noms, $argument]));
        //        }

        $forme1 = false;
        switch (true) {
            case in_array($function, $this->liste_tag):
                $selecteur = 'tag';
                break;
            case in_array($function, $this->liste_tag_exception):
                $selecteur = 'tag_exception';
                break;
            default:
                $forme1 = true;
                $selecteur = $function;
        }

        return static::de(
            call_user_func_array(
                [$this, $selecteur],
                ($forme1 ?
                    [$this->value, $this->injection, $this->arbre_noms, $argument, $no_aid] :
                    [$function, $this->value, $this->injection, $this->arbre_noms, $argument, $no_aid]
                )
            )
        );

    }

    public static function de(array|string|null $value = null, array $injection = [], string $arbre_noms = '')
    {

        if (is_null($value)) $value = ['', 0];
        if (is_string($value)) $value = [$value, 0];
        return new static($value, $injection, $arbre_noms);
    }

    public function recuperer(array|null $output = null): array
    {

        if (!is_null($output)) {
            return [$output[0] . $this->value[0], $this->value[1]];
        }

        return $this->value;
    }

    public function afficher(array|null $output = null): array
    {

        if (!is_null($output)) {
            $this->value = [$output[0] . $this->value[0], $this->value[1]];
        }

        //$this->_big_data .= $this->value[0];
        echo $this->value[0];
        return $this->value;
    }

    private function trim($value)
    {
        return [trim($value[0]), $value[1]];
    }

    private function htmlentities($value)
    {
        return [htmlentities($value[0]), $value[1]];
    }

    private function doctype($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return ["<!DOCTYPE $argument", $value[1]];
    }

    private function gentag($tag, $value, $argument)
    {
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;
        return ["<$tag{$this->argumentifieur($argument)}>$val0", $value[1]];
    }

    private function endtag($tag, $value)
    {
        return ["</$tag>", $value[1]];
    }

    private function startHTML($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->gentag('html', $value, $argument);
    }

    private function endHTML($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('html', $value);
    }

    private function startheader($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->gentag('header', $value, $argument);
    }

    private function endheader($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('header', $value);
    }

    private function startmain($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->gentag('main', $value, $argument);
    }

    private function endmain($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('main', $value);
    }

    private function startdiv($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->gentag('div', $value, $argument);
    }

    private function enddiv($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('div', $value);
    }

    private function startHead($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;
        return ["<head>$val0", $value[1]];
    }

    private function endHead($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('head', $value);
    }

    private function startBody($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->gentag('body', $value, $argument);
    }

    private function endBody($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        return $this->endtag('body', $value);
    }

    private function text($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;
        return ["<span>$argument</span>$val0", $value[1]];
    }

    private function text_apres($value, $injection, $arbre_noms, $argument, $no_aid)
    {
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;
        return ["$val0<span>$argument</span>", $value[1]];
    }

    public static function concatenation($output, $retour): array
    {
        // var_dump($output[1] , $retour[1]);
        return [$output[0] . $retour[0], $retour[1]];
    }

    private function argumentifieur(array $argument, string $arguments_generer = ''): string|null
    {
        if (count($argument) > 0) {
            foreach ($argument as $clee => $valeur) {
                if (is_int($clee)) {
                    $arguments_generer .= " $valeur";
                } elseif ($valeur === '') {
                    $arguments_generer .= " $clee";
                } else {
                    $arguments_generer .= " $clee='$valeur'";
                }
            }
            return $arguments_generer;
        }
        return $arguments_generer;
    }

    private function tag($tag, $value, $injection, $arbre_noms, $argument, $no_aid): array
    {
        // $this->value
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;

        if (!$no_aid) $argument['data-aid'] = $value[1];

        if (
            array_key_exists('tag', $injection) &&
            array_key_exists('injecter', $injection) &&
            !array_key_exists('id', $injection) &&
            $injection['tag'] == $tag &&
            $injection['injecter'] != ''
        ) {
            $inject = $injection['injecter'];
            return ["$inject<$tag{$this->argumentifieur($argument)}>$val0</$tag>", $value[1]];
        } else if (
            array_key_exists('tag', $injection) &&
            array_key_exists('injecter', $injection) &&
            array_key_exists('id', $injection) &&
            array_key_exists('id', $argument) &&
            $injection['tag'] == $tag &&
            $injection['id'] == $argument['id'] &&
            $injection['injecter'] != ''
        ) {
            $inject = $injection['injecter'];
            return ["$inject<$tag{$this->argumentifieur($argument)}>$val0</$tag>", $value[1]];
        } else {
            //var_dump($val0);
            $z = ["<$tag{$this->argumentifieur($argument)}>$val0</$tag>", $value[1]];
            return $z;
        }
    }


    private function tag_exception($tag, $value, $injection, $arbre_noms, $argument, $no_aid)
    {
        $val0 = $value[0];
        $this->value[1]++;
        $value[1]++;

        if (!$no_aid) $argument['data-aid'] = $value[1];

        if (
            array_key_exists('tag', $injection) &&
            array_key_exists('injecter', $injection) &&
            !array_key_exists('id', $injection) &&
            $injection['tag'] == $tag &&
            $injection['injecter'] != ''
        ) {
            $inject = $injection['injecter'];
            return ["$inject<$tag{$this->argumentifieur($argument)}>$val0", $value[1]];
        } else if (
            array_key_exists('tag', $injection) &&
            array_key_exists('injecter', $injection) &&
            array_key_exists('id', $injection) &&
            array_key_exists('id', $argument) &&
            $injection['tag'] == $tag &&
            $injection['id'] == $argument['id'] &&
            $injection['injecter'] != ''
        ) {
            $inject = $injection['injecter'];
            return ["$inject<$tag{$this->argumentifieur($argument)}>$val0", $value[1]];
        } else {
            return ["<$tag{$this->argumentifieur($argument)}>$val0", $value[1]];
        }
    }

}