<?php

use Eukaruon\configs\CMD;
use Eukaruon\modules\Modules_actions as AC;
use Eukaruon\modules\Modules_objets as MO;
use Eukaruon\pilote;

define('THEMEUSE', 'grey');

MO::doctype();
MO::tete();

MO::meta(['charset' => 'UTF-8']);
if (MO::session('utilisateur', 'inscript'))
    MO::autoload('page_produits');

MO::titre('{{TITRE}}');
MO::css(THEMEUSE, true);
MO::js(src: 'https://code.jquery.com/jquery-3.6.0.min.js');

MO::corp();
MO::debutDiv(id: 'page');

if (MO::session('utilisateur', 'inscript')) {
    // Vide si utilisateur inscrit
} else {

    MO::debutHeader();
    echo '{{TITRE}}';
    AC::activiter('test', ['argument1', '$vare2', 'argument3']);
    MO::debutDiv(id: 'colone_menu');


    MO::boutton_ajustable(
        texte: '',
        image: 'svg/fi-rr-chart-tree.svg',
        class: 'logo_boutton_ajustable',
        theme: THEMEUSE,
        lien: '',
        injecter: ['tag' => 'img', 'injecter' => 'test_dinjection']
    );
    MO::boutton_ajustable(
        texte: 'Accueil',
        image: 'svg/fi-rr-home.svg',
        theme: THEMEUSE,
        lien: 'page_accueil'
    );
    MO::boutton_ajustable(
        texte: 'Explore',
        image: 'svg/fi-rr-comment-alt.svg',
        theme: THEMEUSE,
        lien: 'page_explore'
    );
    MO::boutton_ajustable(
        texte: 'Messages',
        image: 'svg/fi-rr-envelope.svg',
        theme: THEMEUSE,
        lien: 'page_messages'
    );
    MO::boutton_ajustable(
        texte: 'Notification',
        image: 'svg/fi-rr-bell.svg',
        theme: THEMEUSE,
        lien: 'page_notification'
    );
    MO::boutton_ajustable(
        texte: 'Options',
        image: 'svg/fi-rr-settings.svg',
        class: 'boutton_ajustable_config',
        theme: THEMEUSE,
        lien: 'page_options'
    );

    MO::finDiv();
    MO::finHeader();

    MO::debutMain();
    MO::debutDiv(id: 'colone_article');
    MO::debutDiv(id: 'bar_principal');
    MO::tag('h2', 'Accueil');
    MO::finDiv();

    MO::debutDiv(id: 'lot');
    MO::formulaire(
        nom: 'inscription',
        tableau_de_type: [
            'Label text1' => ['name' => 'nom', 'type' => 'texte', 'value' => 'v1', 'placeholder' => '', 'class' => 'put'],
            'Label text2' => ['name' => 'prenom', 'type' => 'password', 'value' => 'v2', 'placeholder' => '', 'class' => 'lut'],
        ],
        boutton_valider: 'ok',
        page_dappel: 'test',
        injection: [
            'nom' => [
                MO::tag('h3', 'INSCRIPTION', retour: true)
                , 'avant']
        ]
    );

    MO::finDiv();
    MO::debutDiv(id: 'pied_article');
    MO::tag('img', '', ['src' => 'ressources/themes/images/animated-loading-icon.gif']);
    MO::finDiv();
    MO::finDiv();
    MO::debutDiv(id: 'colone_infos');
    MO::debutDiv(id: 'bar_secondaire');

    // <!-- - COLONE INFO - -->

    MO::boutton_ajustable(
        MO::bare_de_texte(id: 'bar_de_recherche', retour: true)[0],
        image: '/svg/fi-rr-search.svg',
        id: 'zone_de_recherche',
        theme: THEMEUSE,
        no_tag_a: true
    );

    MO::finDiv();
    MO::debutDiv(id: 'lot_informations');

    MO::div(donee: 'Block de TEST', class: 'capsule');
    MO::div(donee: 'Block de TEST', class: 'capsule');
    MO::div(donee: 'Block de TEST', class: 'capsule');
    MO::div(donee: 'Block de TEST', class: 'capsule');
    MO::div(donee: 'Block de TEST', class: 'capsule');
    MO::div(donee: 'Block de TEST', class: 'capsule');

    $pilote = new pilote();
    $Modules_pages = $pilote->Charger_le_module(
        module_a_charger: 'Modules_pages',
        modules_primaire: [CMD::PAGEENCACHE, CMD::MODULES_BDD]
    );

    MO::finDiv();
    MO::finDiv();
    MO::Mainfin();
}

MO::debutDiv('END');
MO::ScriptTheme('grey', true);
MO::fin();
