<?php namespace Eukaruon\modules;

class Modules_habillage
{

    protected $value;
    private array $liste_tag =
        ['!DOCTYPE', 'a', 'abbr', 'acronym', 'address', 'applet', 'area', 'article', 'aside',
            'audio', 'b', 'base', 'basefont', 'bdi', 'bdo', 'big', 'blockquote', 'body', 'button', 'canvas', 'caption',
            'center', 'cite', 'code', 'col', 'colgroup', 'data', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog',
            'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'font', 'footer', 'form',
            'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'html', 'i', 'iframe', 'ins',
            'kbd', 'label', 'legend', 'li', 'link', 'main', 'map', 'mark', 'meta', 'meter', 'nav', 'noframes', 'noscript',
            'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'picture', 'pre', 'progress', 'q', 'rp', 'rt',
            'ruby', 's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'style',
            'sub', 'summary', 'sup', 'svg', 'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time',
            'title', 'tr', 'track', 'tt', 'u', 'ul', 'var', 'video', 'wbr'];
    private array $liste_tag_exception = ['img', '!DOCTYPE', 'br', 'hr', 'input', 'link'];

    public function __construct($value)
    {
        $this->value = $value;
    }


    public function appliquer($function, array|string|null $argument = array()): static
    {
        // call_user_func => $function($this->value)
        //return static::of(call_user_func($function,$this->value));
        if ($function == 'text') {
            return static::de(call_user_func_array([$this, 'text'], [$this->value, $argument]));
        } elseif ($function == 'text_apres') {
            return static::de(call_user_func_array([$this, 'text_apres'], [$this->value, $argument]));
        } elseif (in_array($function, $this->liste_tag)) {
            return static::de(call_user_func_array([$this, 'tag'], [$function, $this->value, $argument]));
        } elseif (in_array($function, $this->liste_tag_exception)) {
            return static::de(call_user_func_array([$this, 'tag_exception'], [$function, $this->value, $argument]));
        } else {
            return static::de(call_user_func_array([$this, $function], [$this->value, $argument]));
        }
    }

    public static function de($value = '')
    {
        return new static($value);
    }

    public function recuperer()
    {
        return $this->value;
    }

    public function afficher()
    {
        echo $this->value;
    }

    private function trim($value)
    {
        return trim($value);
    }

    private function htmlentities($value)
    {
        return htmlentities($value);
    }

    private function text($value, $argument)
    {
        return "<span>$argument</span>$value";
    }

    private function text_apres($value, $argument)
    {
        return "$value<span>$argument</span>";
    }

    private function tag($tag, $value, $argument)
    {
        return "<$tag{$this->argumentifieur($argument)}>$value</$tag>";
    }

    private function argumentifieur(array $argument, string $arguments_generer = ''): string|null
    {
        if (count($argument) > 0) {
            foreach ($argument as $clee => $valeur) {
                $arguments_generer .= " $clee='$valeur'";
            }
            return $arguments_generer;
        }
        return $arguments_generer;
    }

    private function tag_exception($tag, $value, $argument)
    {
        return "<$tag{$this->argumentifieur($argument)}>$value";
    }

}