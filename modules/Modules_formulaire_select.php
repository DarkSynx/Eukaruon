<?php

namespace Eukaruon\modules;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class Modules_formulaire_select
{
    /**
     * @var mixed
     */
    protected mixed $preparation;

    /**
     * @var mixed
     */
    protected string $name;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->preparation = $value;

        if (isset($type['name']))
            $this->name = $type['name'];
    }

    /**
     * @param string|null $id
     * @param string|null $class
     * @param string|null $name
     * @param string|null $autocomplete
     * @param bool|null $autofocus
     * @param bool|null $disabled
     * @param string|null $form
     * @param bool|null $multiple
     * @param bool|null $required
     * @param string|null $size
     * @param string|null $filtre
     * @return static
     */
    #[Pure] public static function defini(string|null $id = null,
                                          string|null $class = null,
                                          string|null $name = null,
                                          string|null $autocomplete = null,
                                          bool|null   $autofocus = null,
                                          bool|null   $disabled = null,
                                          string|null $form = null,
                                          bool|null   $multiple = null,
                                          bool|null   $required = null,
                                          string|null $size = null,
                                          string|null $filtre = null
    ): static
    {
        return new static(self::corp($id, $class, $name, $autocomplete, $autofocus, $disabled, $form, $multiple, $required, $size, $filtre));
    }


    /**
     * @param string|null $id
     * @param string|null $class
     * @param string|null $name
     * @param string|null $autocomplete
     * @param bool|null $autofocus
     * @param bool|null $disabled
     * @param string|null $form
     * @param bool|null $multiple
     * @param bool|null $required
     * @param string|null $size
     * @param string|null $filtre
     * @return string[]
     */
    #[ArrayShape([0 => "string", 1 => "string", 'name' => "null|string"])]
    private static function corp(string|null $id = null,
                                 string|null $class = null,
                                 string|null $name = null,
                                 string|null $autocomplete = null,
                                 bool|null   $autofocus = null,
                                 bool|null   $disabled = null,
                                 string|null $form = null,
                                 bool|null   $multiple = null,
                                 bool|null   $required = null,
                                 string|null $size = null,
                                 string|null $filtre = null
    ): array
    {
        $gen_id_class_name = '';
        if (!is_null($id)) $gen_id_class_name .= " id=\"$id\"";
        if (!is_null($class)) $gen_id_class_name .= " class=\"$class\"";
        if (!is_null($name)) $gen_id_class_name .= " name=\"$name\"";
        if (!is_null($autocomplete)) $gen_id_class_name .= " autocomplete=\"$autocomplete\"";
        if ($autofocus) $gen_id_class_name .= " autofocus";
        if ($disabled) $gen_id_class_name .= " disabled";
        if (!is_null($form)) $gen_id_class_name .= " form=\"$form\"";
        if ($multiple) $gen_id_class_name .= " multiple";
        if ($required) $gen_id_class_name .= " required";
        if (!is_null($size)) $gen_id_class_name .= " size=\"$size\"";

        if (is_null($filtre)) $filtre = '/[^a-zA-Z0-1+-_.]/';

        $test = "\$_POST['$name'] = preg_replace('$filtre', '', \$_POST['$name']);" . PHP_EOL;
        $test .= "\$recolte['$name'][0] = preg_match('$filtre', \$_POST['$name']);" . PHP_EOL;

        return ["<select$gen_id_class_name>", $test, 'name' => $name];
    }

    /**
     * @param $valeur
     * @param $label
     * @return $this
     */
    public function option($valeur, $label): static
    {
        // call_user_func => $function($this->value)
        return static::queue(
            call_user_func_array(
                [$this, 'element'],
                [$valeur, $label, $this->preparation])
        );
    }

    /**
     * @param $fonction
     * @return static
     */
    #[Pure] private static function queue($fonction): static
    {
        return new static($fonction);
    }

    /**
     * @return array
     */
    public function finaliser(): array
    {
        $this->preparation[0] .= PHP_EOL . '</select>';
        return [
            $this->preparation[0],
            $this->preparation[1] .
            "\$recolte['{$this->preparation['name']}']['test'] = in_array(
                \$_POST['{$this->preparation['name']}'],
                \$recolte['{$this->preparation['name']}']['valeur']
                );" . PHP_EOL
        ];
    }

    /**
     * @param $valeur
     * @param $label
     * @param $preparation
     * @return string[]
     */
    #[ArrayShape([0 => "string", 1 => "string", 'name' => "mixed"])]
    private function element($valeur, $label, $preparation): array
    {
        $this->name = $preparation['name'];

        $test = $preparation[1] . "\$recolte['{$preparation['name']}']['valeur'][] = '$valeur';" . PHP_EOL;

        return ["{$preparation[0]}<option value=\"$valeur\">$label</option>" . PHP_EOL, $test, 'name' => $preparation['name']];
    }

}