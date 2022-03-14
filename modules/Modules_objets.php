<?php

namespace Eukaruon\modules;

class Modules_objets //extends Modules_habillage
{


    public static function bar_de_recherche()
    {

    }

    public static function menu_deroulant()
    {

    }

    public static function bar_de_chargement()
    {

    }

    public static function boutton_action()
    {

    }

    public static function bar_slider()
    {

    }

    public static function tab()
    {


    }

    public static function bare_de_texte($texte = '', $valeur = '', $type = 'text', $id = '', $class = '', $retour = false)
    {

        if ($id == '') $id = 'input_baredetexte_' . time();
        if ($class != '') $class = chr(32) . $class;

        $output = Modules_habillage::de()
            ->appliquer('input', [
                'titre' => $type,
                'value' => $valeur,
                'placeholder' => $texte,
                'id' => $id,
                'class' => 'input_baredetexte' . $class])
            ->recuperer();
        if (!$retour) echo $output;
        return $output;
    }

    //364x463
    // 1280 < icone > icone + texte
    public static function boutton_ajustable($donnee, $image = '', $id = '', $class = '', $theme = 'grey')
    {

        if ($id == '') $id = 'boutton_ajustable_' . time();
        if ($class != '') $class = chr(32) . $class;

        $output = Modules_habillage::de()
            ->appliquer('img', ['src' => "ressources/themes/images/$theme/$image"])
            ->appliquer('div', ['class' => 'boutton_ajustable_img'])
            ->recuperer();

        $output .= Modules_habillage::de($donnee)
            ->appliquer('span')
            ->appliquer('div', ['class' => 'boutton_ajustable_text'])
            ->recuperer();

        Modules_habillage::de($output)
            ->appliquer('div', ['class' => 'boutton_ajustable_block'])
            ->appliquer('div', ['id' => $id, 'class' => 'boutton_ajustable' . $class])
            ->afficher();

    }

    public
    static function session($clef, $egale_a)
    {
        return (array_key_exists($clef, $_SESSION) && $_SESSION[$clef] == $egale_a);
    }

    public
    static function styletheme($name, $actualiser = false)
    {

        Modules_habillage::de()
            ->appliquer("link", ['href' => 'ressources/themes/' . $name . '/style.css' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();

        return $name;
    }

    public
    static function autoload($url)
    {
        Modules_habillage::de()
            ->appliquer('meta', ['http-equiv' => 'REFRESH', 'content' => '0; url=' . $url])
            ->afficher();
    }

    public
    static function scripttheme($name, $actualiser = false)
    {
        Modules_habillage::de()
            ->appliquer("script", ['src' => 'ressources/themes/' . $name . '/actions.js' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();
    }


    public
    static function formulaire(string $nom, $tableau_de_type, $boutton_valider = 'valider', $method = 'POST', $page_dappel = '', $injection = '', $stylecss = '', $output = '', $retour = false)

    {

        $list_identifiant = array();
        foreach ($tableau_de_type as $label => $valeur) {
            $key_existe_dans_injection = key_exists($valeur['name'], $injection);

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'avant') {
                $output .= $injection[$valeur['name']][0];
            }
            $list_identifiant[] = '"' . $valeur['name'] . '"';

            $output .= Modules_habillage::de()
                ->appliquer("input", $valeur)
                ->appliquer("text", $label)
                ->appliquer("label")
                ->appliquer("div")
                ->recuperer();

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'apres') {
                $output .= $injection[$valeur['name']][0];
            }

        }
        $list_identifiant_pour_js = '[' . implode(',', $list_identifiant) . ']';

        $output .= Modules_habillage::de()
            ->appliquer("hr")
            ->appliquer("br")
            ->recuperer();

        $output .= Modules_habillage::de()
            ->appliquer("input", ['type' => 'button', 'value' => $boutton_valider, 'onclick' => 'formulaire_action("formulaire_' . $nom . '",' . $list_identifiant_pour_js . ',"' . $page_dappel . '" );'])
            ->recuperer();

        $output .= Modules_habillage::de()->appliquer("br")->recuperer();

        $output .= Modules_habillage::de()
            ->appliquer("script", ['src' => 'passerelle/js/passe.js'])
            ->recuperer();

        if ($stylecss != '') {
            $stylecss = str_replace(
                ['[%id%]', '[%form%]'],
                ['formulaire_' . $nom, 'form#formulaire_' . $nom],
                $stylecss);

            $output .= Modules_habillage::de($stylecss)
                ->appliquer("style")
                ->recuperer();
        }

        $output = Modules_habillage::de($output)
            ->appliquer("form", ['action' => '',
                'id' => 'formulaire_' . $nom,
                'class' => 'objet_formulaire',
                'method' => $method])
            ->appliquer('div', ['class' => 'capsule'])
            ->recuperer();

        if (!$retour) echo $output;
        return $output;
    }

    public static function tag($tag = 'span', $donee = '', $option = [], $retour = false)
    {
        $output = Modules_habillage::de($donee)
            ->appliquer($tag, $option)
            ->recuperer();

        if (!$retour) echo $output;
        return $output;
    }


}