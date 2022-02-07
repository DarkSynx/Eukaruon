<?php

namespace Eukaruon\modules;

class Modules_objets
{

    public function formulaire(string $nom, $tableau_de_type, $boutton_valider = 'valider', $method = 'POST', $page_dappel = '', $injection = '', $stylecss = '', $output = '')
    {

        $list_identifiant = array();
        foreach ($tableau_de_type as $valeur) {
            $key_existe_dans_injection = key_exists($valeur['name'], $injection);

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'avant') {
                $output .= $injection[$valeur['name']][0];
            }
            $list_identifiant[] = '"' . $valeur['name'] . '"';
            $output .= Modules_habillage::de()
                ->appliquer("input", $valeur)
                ->appliquer("text", 'texte de label')
                ->appliquer("label")
                ->recuperer();

            if ($key_existe_dans_injection && $injection[$valeur['name']][1] == 'apres') {
                $output .= $injection[$valeur['name']][0];
            }

        }
        $list_identifiant_pour_js = '[' . implode(',', $list_identifiant) . ']';

        $output .= Modules_habillage::de()
            ->appliquer("input", ['type' => 'button', 'value' => '$boutton_valider', 'onclick' => 'formulaire_action("formulaire_' . $nom . '",' . $list_identifiant_pour_js . ',"' . $page_dappel . '" );'])
            ->recuperer();

        $output .= Modules_habillage::de()
            ->appliquer("script", ['src' => 'passerelle/js/passe.js'])
            ->recuperer();

        if ($stylecss != '') {
            $output .= Modules_habillage::de($stylecss)
                ->appliquer("style")
                ->recuperer();
        }

        $output = Modules_habillage::de($output)
            ->appliquer("form", ['action' => '', 'id' => 'formulaire_' . $nom, 'method' => $method])
            ->recuperer();

        echo $output;
    }

    /*
     *  <?php $Modules_objets = new Modules_objets(); ?>
     *  <?php $Modules_objets->formulaire([
     *      'name' => [
     *                  'type'=>'texte',
     *                  'value'=>'value',
     *                  'placeholder' => ''
     *                  'id' => ''
     *                  'class' => ''
     *                  ]
     *  ]) ?>
     *
     */

    //     $output = Modules_habillage::de("  la réponse est Non   ")
    //        ->appliquer("trim")
    //        ->appliquer("htmlentities")
    //        ->appliquer("h1", ['style' => 'color:red;'])
    //        ->appliquer("body")
    //        ->appliquer("html")
    //        ->recuperer();
}