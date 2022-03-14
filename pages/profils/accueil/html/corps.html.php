<?php

use Eukaruon\configs\CMD;
use Eukaruon\modules\Modules_objets as MO;
use Eukaruon\pilote;

define('THEMEUSE', 'grey');

?>
<!DOCTYPE html>
<html lang="{{LANGUE}}">
<head>
    <meta charset="UTF-8"/>
    <?php
    if (MO::session('utilisateur', 'inscript')):
        MO::autoload('page_produits');
    endif
    ?>
    <title>{{TITRE}}</title>
    <?php MO::StyleTheme(THEMEUSE, true) ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div id="page">
    <?php if (MO::session('utilisateur', 'inscript')):
        /* Vide si utilisateur inscrit */
    else: ?>
        <header>
            <div id="colone_menu">
                <?php MO::boutton_ajustable('', 'svg/fi-rr-chart-tree.svg', 'logo_boutton_ajustable', theme: THEMEUSE); ?>
                <?php MO::boutton_ajustable('Accueil', 'svg/fi-rr-home.svg', theme: THEMEUSE); ?>
                <?php MO::boutton_ajustable('Explore', 'svg/fi-rr-comment-alt.svg', theme: THEMEUSE); ?>
                <?php MO::boutton_ajustable('Messages', 'svg/fi-rr-envelope.svg', theme: THEMEUSE); ?>
                <?php MO::boutton_ajustable('Notification', 'svg/fi-rr-bell.svg', theme: THEMEUSE); ?>
                <?php MO::boutton_ajustable('Profile', 'svg/fi-rr-settings.svg', class: 'boutton_ajustable_config', theme: THEMEUSE); ?>
            </div>
        </header>
        <!-- - MAIN - -->
        <main>
            <div id="colone_article">
                <div id="bar_principal"><h2>Accueil</h2></div>
                <div id="lot">
                    <?php
                    MO::formulaire(
                        nom: 'inscription',
                        tableau_de_type: [
                            'Label text1' => ['name' => 'nom', 'type' => 'texte', 'value' => 'v1', 'placeholder' => '', 'class' => 'put'],
                            'Label text2' => ['name' => 'prenom', 'type' => 'password', 'value' => 'v2', 'placeholder' => '', 'class' => 'lut'],
                        ],
                        boutton_valider: 'ok',
                        page_dappel: 'test',
                        injection: ['nom' => [
                            MO::tag('h3', 'INSCRIPTION', retour: true)
                            , 'avant']]
                    )
                    ?>
                </div>
                <div id="pied_article">
                    <?php MO::tag('img', '', ['src' => 'ressources/themes/images/animated-loading-icon.gif']) ?>
                </div>
            </div>
            <!-- - COLONE INFO - -->
            <div id="colone_infos">
                <div id="bar_secondaire">
                    <?php
                    $bar_de_recherche = MO::bare_de_texte(id: 'bar_de_recherche', retour: true);
                    MO::boutton_ajustable($bar_de_recherche, image: '/svg/fi-rr-search.svg', id: 'zone_de_recherche', theme: THEMEUSE);
                    ?>
                </div>
                <div id="lot_informations">
                    <div class="capsule">Block de TEST</div>
                    <div class="capsule">Block de TEST</div>
                    <div class="capsule">Block de TEST</div>
                    <div class="capsule">Block de TEST</div>
                    <div class="capsule">Block de TEST</div>

                    <?php
                    $pilote = new pilote();
                    $Modules_pages = $pilote->Charger_le_module(
                        module_a_charger: 'Modules_pages',
                        modules_primaire: [CMD::PAGEENCACHE, CMD::MODULES_BDD]
                    );

                    ?>
                </div>
            </div>
        </main>


    <?php endif ?>
</div>
<?php MO::ScriptTheme('grey', true) ?>
</body>
</html>