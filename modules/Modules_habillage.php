<?php namespace Eukaruon\modules;

class Modules_habillage
{

    protected $value;
    private array $liste_tag = ['h1', 'body', 'html'];
    private array $liste_tag_exception = ['img'];

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function appliquer($function, array|null $argument = array()): static
    {
        // call_user_func => $function($this->value)
        //return static::of(call_user_func($function,$this->value));
        if (in_array($function, $this->liste_tag)) {
            return static::de(call_user_func_array([$this, 'tag'], [$function, $this->value, $argument]));
        } elseif (in_array($function, $this->liste_tag_exception)) {
            return static::de(call_user_func_array([$this, 'tag_exception'], [$function, $this->value, $argument]));
        } else {
            return static::de(call_user_func_array([$this, $function], [$this->value, $argument]));
        }
    }

    public static function de($value)
    {
        return new static($value);
    }

    public function recuperer()
    {
        return $this->value;
    }

    private function trim($value)
    {
        return trim($value);
    }

    private function htmlentities($value)
    {
        return htmlentities($value);
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