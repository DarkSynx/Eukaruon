<?php

namespace Eukaruon\modules;

use JetBrains\PhpStorm\Pure;

/**
 *
 */
class Modules_formulaire
{
    /**
     * @var mixed
     */
    protected array $code = ['', '<?php '];


    /**
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @param $action
     * @param string $method
     * @return static
     */
    #[Pure] public static function defini($action, string $method = 'post'): static
    {
        return new static(self::identite_formulaire($action, $method));
    }

    /**
     * @param $action
     * @param $method
     * @return array
     */
    private static function identite_formulaire($action, $method): array
    {
        return ["<form action=\"$action\" $method=\"$method\">", ''];
    }

    /**
     * @param ...$tableau
     * @return $this
     */
    public function elements(...$tableau): static
    {
        $tableau_fusionner = [$this->code[0], $this->code[1]];
        foreach ($tableau as $valeur) {
            $tableau_element = call_user_func_array([$this, 'element'], [$valeur]);
            $tableau_fusionner = [
                $tableau_fusionner[0] . PHP_EOL . $tableau_element[0],
                $tableau_fusionner[1] . PHP_EOL . $tableau_element[1],
            ];
        }
        return static::gestion_element($tableau_fusionner);
    }

    /**
     * @param $element
     * @return static
     */
    #[Pure] public static function gestion_element($element): static
    {
        return new static($element);
    }

    /**
     * @param $tableau
     * @return array
     */
    public function element($tableau): array
    {
        return $tableau;
    }


    /**
     * @return array
     */
    public function generer(): array
    {
        $this->code[0] .= PHP_EOL . '</form>';
        return [
            $this->code[0],
            '\$recolte = array();' .
            $this->code[1]
        ];
    }

}