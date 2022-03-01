<?php

namespace Eukaruon\modules;

class Modules_objets
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

    //364x463
    // 1280 < icone > icone + texte
    public static function boutton_ajustable($texte, $image = '', $id = '', $class = '')
    {
        if ($id == '') $id = time();
        if ($class != '') $class = chr(32) . $class;

        $output = Modules_habillage::de()
            ->appliquer('img', ['src' => 'ressources/themes/images/' . $image])
            ->appliquer('div', ['class' => 'boutton_ajustable_img'])
            ->recuperer();

        $output .= Modules_habillage::de($texte)
            ->appliquer('span')
            ->appliquer('div', ['class' => 'boutton_ajustable_text'])
            ->recuperer();

        Modules_habillage::de($output)
            ->appliquer('div', ['class' => 'boutton_ajustable_block'])
            ->appliquer('div', ['id' => 'boutton_ajustable_' . $id, 'class' => 'boutton_ajustable' . $class])
            ->afficher();

    }

    public static function session($clef, $egale_a)
    {
        return (array_key_exists($clef, $_SESSION) && $_SESSION[$clef] == $egale_a);
    }

    public static function styletheme($name, $actualiser = false)
    {
        Modules_habillage::de()
            ->appliquer("link", ['href' => 'ressources/themes/' . $name . '/style.css' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();
    }

    public static function autoload($url)
    {
        Modules_habillage::de()
            ->appliquer('meta', ['http-equiv' => 'REFRESH', 'content' => '0; url=' . $url])
            ->afficher();
    }

    public static function scripttheme($name, $actualiser = false)
    {
        Modules_habillage::de()
            ->appliquer("script", ['src' => 'ressources/themes/' . $name . '/actions.js' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();
    }


    public static function formulaire(string $nom, $tableau_de_type, $boutton_valider = 'valider', $method = 'POST', $page_dappel = '', $injection = '', $stylecss = '', $output = '')

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

        Modules_habillage::de($output)
            ->appliquer("form", ['action' => '',
                'id' => 'formulaire_' . $nom,
                'class' => 'objet_formulaire',
                'method' => $method])
            ->afficher();

    }


}