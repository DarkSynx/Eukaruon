<?php

namespace Eukaruon\modules;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class Modules_formulaire_textarea
{
    /**
     * @var mixed
     */
    protected mixed $preparation;


    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->preparation = $value;


    }

    /**
     * @param string|null $id
     * @param string|null $class
     * @param string|null $name
     * @param string|null $autocapitalize
     * @param string|null $autocomplete
     * @param string|null $autofocus
     * @param string|null $cols
     * @param bool|null $disabled
     * @param string|null $form
     * @param string|null $maxlength
     * @param string|null $minlength
     * @param string|null $placeholder
     * @param bool|null $readonly
     * @param string|null $required
     * @param string|null $rows
     * @param string|null $spellcheck
     * @param string|null $wrap
     * @param string|null $filtre
     * @param bool|null $encaps_b64
     * @return static
     */
    #[Pure] public static function defini(string|null $id = null,
                                          string|null $class = null,
                                          string|null $name = null,
                                          string|null $autocapitalize = null,
                                          string|null $autocomplete = null,
                                          string|null $autofocus = null,
                                          string|null $cols = null,
                                          bool|null   $disabled = null,
                                          string|null $form = null,
                                          string|null $maxlength = null,
                                          string|null $minlength = null,
                                          string|null $placeholder = null,
                                          bool|null   $readonly = null,
                                          string|null $required = null,
                                          string|null $rows = null,
                                          string|null $spellcheck = null,
                                          string|null $wrap = null,
                                          string|null $filtre = null,
                                          bool|null   $encaps_b64 = null,

    ): static
    {
        return new static(self::corp($id, $class, $name,
            $autocapitalize, $autocomplete, $autofocus, $cols, $disabled, $form, $maxlength, $minlength, $placeholder,
            $readonly, $required, $rows, $spellcheck, $wrap, $filtre, $encaps_b64));
    }


    /**
     * @param string|null $id
     * @param string|null $class
     * @param string|null $name
     * @param string|null $autocapitalize
     * @param string|null $autocomplete
     * @param string|null $autofocus
     * @param string|null $cols
     * @param bool|null $disabled
     * @param string|null $form
     * @param string|null $maxlength
     * @param string|null $minlength
     * @param string|null $placeholder
     * @param bool|null $readonly
     * @param string|null $required
     * @param string|null $rows
     * @param string|null $spellcheck
     * @param string|null $wrap
     * @param string|null $filtre
     * @param bool|null $encaps_b64
     * @return string[]
     */
    #[ArrayShape([0 => "string", 1 => "", 'name' => "null|string"])]
    private static function corp(string|null $id = null,
                                 string|null $class = null,
                                 string|null $name = null,
                                 string|null $autocapitalize = null,
                                 string|null $autocomplete = null,
                                 string|null $autofocus = null,
                                 string|null $cols = null,
                                 bool|null   $disabled = null,
                                 string|null $form = null,
                                 string|null $maxlength = null,
                                 string|null $minlength = null,
                                 string|null $placeholder = null,
                                 bool|null   $readonly = null,
                                 string|null $required = null,
                                 string|null $rows = null,
                                 string|null $spellcheck = null,
                                 string|null $wrap = null,
                                 string|null $filtre = null,
                                 bool|null   $encaps_b64 = null,
    ): array
    {
        $gen_id_class_name = '';
        if (!is_null($id)) $gen_id_class_name .= " id=\"$id\"";
        if (!is_null($class)) $gen_id_class_name .= " class=\"$class\"";
        if (!is_null($name)) $gen_id_class_name .= " name=\"$name\"";
        if (!is_null($autocapitalize)) $gen_id_class_name .= " autocapitalize=\"$autocapitalize\"";
        if (!is_null($autocomplete)) $gen_id_class_name .= " autocomplete=\"$autocomplete\"";
        if (!is_null($autofocus)) $gen_id_class_name .= " autofocus=\"$autofocus\"";
        if (!is_null($cols)) $gen_id_class_name .= " cols=\"$cols\"";
        if ($disabled) $gen_id_class_name .= " disabled";
        if (!is_null($form)) $gen_id_class_name .= " form=\"$form\"";
        if (!is_null($maxlength)) $gen_id_class_name .= " maxlength=\"$maxlength\"";
        if (!is_null($minlength)) $gen_id_class_name .= " minlength=\"$minlength\"";
        if (!is_null($placeholder)) $gen_id_class_name .= " placeholder=\"$placeholder\"";
        if ($readonly) $gen_id_class_name .= " readonly";
        if (!is_null($required)) $gen_id_class_name .= " required=\"$required\"";
        if (!is_null($rows)) $gen_id_class_name .= " rows=\"$rows\"";
        if (!is_null($spellcheck)) $gen_id_class_name .= " spellcheck=\"$spellcheck\"";
        if (!is_null($wrap)) $gen_id_class_name .= " wrap=\"$wrap\"";

        $test = <<<TEST
        /* 
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--- 
         * TEST : TEXTAREA : $name
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--- 
         */
        \$recolte['$name']['type'] = 'textarea';
        
        TEST;

        $test .= "/* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */" . PHP_EOL;
        $test .= "// Si \$_POST['$name'] existe " . PHP_EOL;
        $test .= "if(isset(\$_POST['$name'])){" . PHP_EOL . PHP_EOL . PHP_EOL;

        if (is_null($filtre)) $filtre = 'FILTER_SANITIZE_ADD_SLASHES';
        if ($encaps_b64) {
            $test .=
                "\$filtre_base_64 = filter_var(\$_POST['$name'],$filtre);" . PHP_EOL .
                "\$recolte['$name']['resultat'] = ((\$filtre_base_64 !== false && !empty(\$filtre_base_64) && \$filtre_base_64 !== '' ) ? base64_encode(\$filtre_base_64) : false);" . PHP_EOL;
        } else {
            $test .=
                "\$filtre_var = filter_var(\$_POST['$name'],$filtre);" . PHP_EOL .
                "\$recolte['$name']['resultat'] = ((\$filtre_var !== false && !empty(\$filtre_var) && \$filtre_var !== '' ) ? \$filtre_var : false);" . PHP_EOL;
        }

        return ["<textarea$gen_id_class_name >", $test, 'name' => $name];
    }

    /**
     * @param $text
     * @return $this
     */
    public function contenu($text): static
    {
        // call_user_func => $function($this->value)
        return static::queue(
            call_user_func_array(
                [$this, 'element'],
                [$text, $this->preparation])
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
        $this->preparation[0] .= '</textarea>';
        return [
            $this->preparation[0],
            $this->preparation[1] .
            '}' . PHP_EOL . '/* ISSET FIN -------------------- */' . PHP_EOL,
            'textarea',
            $this->preparation['name']
        ];
    }

    /**
     * @param $texte
     * @param $preparation
     * @return string[]
     */
    #[ArrayShape([0 => "string", 1 => "string", 'name' => "mixed"])]
    private function element($texte, $preparation): array
    {
        $test = $preparation[1];
        return ["$preparation[0]$texte", $test, 'name' => $preparation['name']];
    }

}