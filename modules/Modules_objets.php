<?php

namespace Eukaruon\modules;

use Eukaruon\modules\Modules_habillage as MH;


/**
 *
 */
class Modules_objets //extends Modules_habillage
{


    /** pas encore implimenter
     *
     */
    public static function bar_de_recherche()
    {

    }

    /** pas encore implimenter
     *
     */
    public static function menu_deroulant()
    {

    }

    /** pas encore implimenter
     *
     */
    public static function bar_de_chargement()
    {

    }

    /** pas encore implimenter
     *
     */
    public static function boutton_action()
    {

    }

    /** pas encore implimenter
     *
     */
    public static function bar_slider()
    {

    }

    /** pas encore implimenter
     *
     */
    public static function tab()
    {


    }

    /** permet de créé un input appeler bare de texte pardefault
     * mais celui-ci est modifiable
     * @param string $texte
     * @param string $valeur
     * @param string $type
     * @param string $id
     * @param string $class
     * @param false $retour
     * @return array
     */
    public static function bare_de_texte($texte = '', $valeur = '', $type = 'text', $id = '', $class = '', $retour = false)
    {

        if ($id == '') $id = 'input_baredetexte_' . time();
        if ($class != '') $class = chr(32) . $class;

        $output = MH::de()
            ->appliquer('input', [
                'titre' => $type,
                'value' => $valeur,
                'placeholder' => $texte,
                'id' => $id,
                'class' => 'input_baredetexte' . $class])
            ->recuperer();
        if (!$retour) echo $output[0];
        return $output;
    }


    /** permet de créé un bouton ajustable
     * @param $texte
     * @param string $image
     * @param string $id
     * @param string $class
     * @param string $theme
     * @param string $lien
     * @param array $injecter
     * @param false $no_tag_a
     */
    public static function boutton_ajustable($texte, $image = '', $id = '', $class = '', $theme = 'grey', $lien = '', array $injecter = [], $no_tag_a = false)
    {

        if ($id == '') $id = 'boutton_ajustable_' . time();
        if ($class != '') $class = chr(32) . $class;

        $output = MH::de(injection: $injecter, arbre_noms: $id)
            ->appliquer('img', ['src' => "ressources/themes/images/$theme/$image"])
            ->appliquer('div', ['class' => 'boutton_ajustable_img'])
            ->recuperer();


        $output = MH::de([$texte, $output[1]], injection: $injecter, arbre_noms: $id)
            ->appliquer('span')
            ->appliquer('div', ['class' => 'boutton_ajustable_text'])
            ->recuperer($output);


        $output = MH::de($output, injection: $injecter, arbre_noms: $id)
            ->appliquer('div', ['class' => 'boutton_ajustable_block'])
            ->appliquer('div', ['id' => $id, 'class' => 'boutton_ajustable' . $class])
            ->recuperer();

        $no_tag_a ?
            MH::de($output, injection: $injecter, arbre_noms: $id)->afficher()
            :
            MH::de($output, injection: $injecter, arbre_noms: $id)
                ->appliquer('a', ['href' => $lien])
                ->afficher();

    }

    /**permet la gestion de la clé de session
     * @param $clef
     * @param $egale_a
     * @return bool
     */
    public static function session($clef, $egale_a)
    {
        return (array_key_exists($clef, $_SESSION) && $_SESSION[$clef] == $egale_a);
    }

    /** parmet de créé la balise pour charger un fichier css
     * @param $name
     * @param false $actualiser
     * @return mixed
     */
    public static function css($name, $actualiser = false)
    {
        MH::de()
            ->appliquer("link", ['href' => 'ressources/themes/' . $name . '/style.css' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();

        return $name;
    }

    /** permet de créé la balise meta pour un autoload d'une page
     * @param $url
     */
    public static function autoload($url)
    {
        MH::de()
            ->appliquer('meta', ['http-equiv' => 'REFRESH', 'content' => '0; url=' . $url])
            ->afficher();
    }

    /** permet de créé la balise de chargement d'un script
     * @param $name
     * @param false $actualiser
     */
    public static function scripttheme($name, $actualiser = false)
    {
        MH::de()
            ->appliquer("script", ['src' => 'ressources/themes/' . $name . '/actions.js' . ($actualiser ? '?t=' . time() : ''), 'rel' => "stylesheet"])
            ->afficher();
    }


    /** permet la fabrication d'un formulaire
     * MO::formulaire(
     * nom: 'inscription',
     * tableau_de_type: [
     * 'Label text1' => ['name' => 'nom', 'type' => 'texte', 'value' => 'v1', 'placeholder' => '', 'class' => 'put'],
     * 'Label text2' => ['name' => 'prenom', 'type' => 'password', 'value' => 'v2', 'placeholder' => '', 'class' => 'lut'],
     * ],
     * boutton_valider: 'ok',
     * page_dappel: 'test',
     * injection: [
     * 'nom' => [
     * MO::tag('h3', 'INSCRIPTION', retour: true)
     * , 'avant']
     * ]
     * );
     * @param string $nom
     * @param $tableau_de_type
     * @param string $boutton_valider
     * @param string $method
     * @param string $page_dappel
     * @param string $injection
     * @param string $stylecss
     * @param array $output
     * @param false $retour
     * @return array
     */
    public static function formulaire(string $nom, $tableau_de_type, $boutton_valider = 'valider', $method = 'POST', $page_dappel = '', $injection = '', $stylecss = '', array $output = ['', 0], $retour = false)

    {

        $list_identifiant = array();
        foreach ($tableau_de_type as $label => $valeur) {
            $key_existe_dans_injection = key_exists($valeur['name'], $injection);

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'avant') {

                //$output = MH::concatenation($output, $injection[$valeur['name']][0]);
                $output = MH::de($injection[$valeur['name']][0])->recuperer($output);
            }

            $list_identifiant[] = '"' . $valeur['name'] . '"';

            $output =
                MH::de(['', $output[1]])
                    ->appliquer("input", $valeur)
                    ->appliquer("text", $label)
                    ->appliquer("label")
                    ->appliquer("div")
                    ->recuperer($output);

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'apres') {
                //$output = MH::concatenation($output, $injection[$valeur['name']][0]);
                $output = MH::de($injection[$valeur['name']][0])->recuperer($output);
            }

        }
        $list_identifiant_pour_js = '[' . implode(',', $list_identifiant) . ']';

        $output =
            MH::de(['', $output[1]])
                ->appliquer("hr")
                ->appliquer("br")
                ->recuperer($output);

        $output =
            MH::de(['', $output[1]])
                ->appliquer("input", ['type' => 'button', 'value' => $boutton_valider, 'onclick' => 'formulaire_action("formulaire_' . $nom . '",' . $list_identifiant_pour_js . ',"' . $page_dappel . '" );'])
                ->recuperer($output);

        $output =
            MH::de(['', $output[1]])->appliquer("br")->recuperer($output);

        $output =
            MH::de(['', $output[1]])
                ->appliquer("script", ['src' => 'passerelle/js/passe.js'])
                ->recuperer($output);

        if ($stylecss != '') {
            $stylecss = str_replace(
                ['[%id%]', '[%form%]'],
                ['formulaire_' . $nom, 'form#formulaire_' . $nom],
                $stylecss);

            $output =
                MH::de([$stylecss, $output[1]])
                    ->appliquer("style")
                    ->recuperer($output);
        }

        $output = MH::de($output)
            ->appliquer("form", ['action' => '',
                'id' => 'formulaire_' . $nom,
                'class' => 'objet_formulaire',
                'method' => $method])
            ->appliquer('div', ['class' => 'capsule'])
            ->recuperer();

        if (!$retour) echo $output[0];
        return $output;
    }

    /** permet de créé une balise spécifique dont un span pardéfaut
     * @param string $tag
     * @param string $donee
     * @param array $argument
     * @param false $retour
     * @param string $id
     * @param string $class
     * @return array
     */
    public static function tag($tag = 'span', $donee = '', $argument = [], $retour = false, $id = '', $class = '')
    {
        if ($id !== '') $argument['id'] = $id;
        if ($class !== '') $argument['class'] = $class;

        $output = MH::de($donee)
            ->appliquer($tag, $argument)
            ->recuperer();

        if (!$retour) echo $output[0];
        return $output;
    }

    /** permet de créé une balise div
     * @param string $donee
     * @param array $argument
     * @param false $retour
     * @param string $id
     * @param string $class
     * @return array
     */
    public static function div($donee = '', $argument = [], $retour = false, $id = '', $class = '')
    {
        if ($id !== '') $argument['id'] = $id;
        if ($class !== '') $argument['class'] = $class;

        $output = MH::de($donee)
            ->appliquer('div', $argument)
            ->recuperer();

        if (!$retour) echo $output[0];
        return $output;
    }

    /* ------------------------------------------ */
    // 'img', '!DOCTYPE', 'br', 'hr', 'input', 'link'

    /** permet l'ajout du !DOCTYPE en début de page
     * @param string[] $argument
     */
    public static function doctype($argument = ['html'])
    {
        MH::de()
            ->appliquer('!DOCTYPE', $argument, no_aid: true)
            ->afficher();
    }


    /** permet de créé le Header balise de début et de fin
     * @param array|string $argument
     */
    public static function debutHeader(array|string $argument = [])
    {
        $argument == 'END' ?
            MH::de()->appliquer('endheader', no_aid: true)->afficher()
            :
            MH::de()->appliquer('startheader', $argument)->afficher();
    }

    /**
     * permet de créé la balise header de fin si debutHeader('END') vous semble
     * pas logique. sachez que les deux sont possible
     */
    public static function finHeader()
    {
        MH::de()->appliquer('endheader', no_aid: true)->afficher();
    }

    /** permet de créé le Main balise de début et de fin
     * @param array|string $argument
     */
    public static function debutMain(array|string $argument = [])
    {
        $argument == 'END' ?
            MH::de()->appliquer('endmain', no_aid: true)->afficher()
            :
            MH::de()->appliquer('startmain', $argument)->afficher();
    }

    /**
     * permet de créé le Main balise de fin
     */
    public static function Mainfin()
    {
        MH::de()->appliquer('endmain', no_aid: true)->afficher();
    }

    /** permet de créer une balise meta
     * @param array $argument
     */
    public static function meta($argument = [])
    {
        MH::de()->appliquer('meta', $argument, no_aid: true)->afficher();
    }

    /** permet de créé le titre de la page
     * @param $valeur
     */
    public static function titre($valeur)
    {
        MH::de($valeur)->appliquer('title', no_aid: true)->afficher();
    }

    /** permet de créer une div spécifique
     * @param array|string $argument
     * @param string $id
     * @param string $class
     */
    public static function debutDiv(array|string $argument = [], $id = '', $class = '')
    {
        if ($id !== '') $argument['id'] = $id;

        if ($class !== '') $argument['class'] = $class;

        $argument == 'END' ?
            MH::de()->appliquer('enddiv', no_aid: true)->afficher()
            :
            MH::de()->appliquer('startdiv', $argument)->afficher();
    }

    /**
     * permet de créer la balise div de fin
     */
    public static function finDiv()
    {
        MH::de()->appliquer('enddiv', no_aid: true)->afficher();
    }


    /** permet de créé la balise de chargement d'un fichier javascript
     * @param array $argument
     * @param string $src
     */
    public static function js($argument = [], $src = '')
    {
        if ($src !== '') $argument['src'] = $src;

        MH::de()->appliquer('script', $argument, no_aid: true)->afficher();
    }

    /** permet de créé les balises de début <HTML><HEAD>
     * @param string[] $argument
     */
    public static function tete($argument = ['lang' => 'fr'])
    {
        MH::de()->appliquer('startHTML', $argument, no_aid: true)->afficher();
        MH::de()->appliquer('startHead', [], no_aid: true)->afficher();
    }

    /** permet de créé les balises de corps </HEAD><BODY> et </BODY></HTML>
     * @param array|string $argument
     */
    public static function corp(array|string $argument = [])
    {
        if ($argument == 'END') {
            MH::de()->appliquer('endBody')->afficher();
            MH::de()->appliquer('endHTML')->afficher();
        } else {
            MH::de()->appliquer('endHead')->afficher();
            MH::de()->appliquer('startBody', $argument, no_aid: true)->afficher();
        }
    }

    /** permet de créé la fin de la page </BODY></HTML>
     * @param array $argument
     */
    public static function fin($argument = [])
    {
        MH::de()->appliquer('endBody')->afficher();
        MH::de()->appliquer('endHTML')->afficher();
    }
}