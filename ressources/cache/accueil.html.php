<?php

use Eukaruon\configs\CMD;
use Eukaruon\modules\Modules_habillage;
use Eukaruon\modules\Modules_objets;
use Eukaruon\pilote;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <?php
    if (Modules_objets::session('utilisateur', 'inscript')):
        Modules_objets::autoload('page_produits');
    endif
    ?>
    <title>test de page</title>
    <?php Modules_objets::StyleTheme('grey', true) ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php Modules_objets::ScriptTheme('grey', true) ?>
</head>
<body>
<div id="page">

    <?php if (Modules_objets::session('utilisateur', 'inscript')):
        /* Vide si utilisateur inscrit */
    else: ?>
        <header>
            <div id="colone_menu">
                <?php Modules_objets::boutton_ajustable('Accueil', 'logoeukaruon-128_2.png'); ?>
                <?php Modules_objets::boutton_ajustable('sqdlkjsqdqsd', 'logoeukaruon-128_2.png'); ?>
                <?php Modules_objets::boutton_ajustable('Accqsdqsueil', 'logoeukaruon-128_2.png'); ?>
                <?php Modules_objets::boutton_ajustable('ssl', 'logoeukaruon-128_2.png'); ?>
                <?php Modules_objets::boutton_ajustable('eoiroirtirotiotirtiit', 'logoeukaruon-128_2.png'); ?>
            </div>
        </header>
        <main>
            <div id="colone_article">

                <?php Modules_objets::formulaire(
                    nom: 'inscription',
                    tableau_de_type: [
                        'Label text1' => ['name' => 'nom', 'type' => 'texte', 'value' => 'v1', 'placeholder' => '', 'class' => 'put'],
                        'Label text2' => ['name' => 'prenom', 'type' => 'password', 'value' => 'v2', 'placeholder' => '', 'class' => 'lut'],
                    ],
                    boutton_valider: 'ok',
                    page_dappel: 'test',
                    injection: ['nom' => ['<h3>INSCRIPTION</h3>', 'avant']]
                ) ?>

            </div>
            <div id="colone_infos">

                <?php
                $pilote = new pilote();
                $Modules_pages = $pilote->Charger_le_module(
                    module_a_charger: 'Modules_pages',
                    modules_primaire: [CMD::PAGEENCACHE, CMD::MODULES_BDD]
                );


                $output = Modules_habillage::de("  la réponse est Non   ")
                    ->appliquer("trim")
                    ->appliquer("htmlentities")
                    ->appliquer("h1", ['style' => 'color:red;'])
                    ->appliquer("body")
                    ->appliquer("html")
                    ->recuperer();


                echo $output;

                ?>
            </div>
        </main>


    <?php endif ?>
</div>
</body>
</html>