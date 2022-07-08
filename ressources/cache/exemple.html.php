<?php error_reporting(0); header("Content-type: text/html; charset=utf-8"); ?><?php $time_start = microtime(true); ?><!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>{{TITRE}}</title>
    <link href='ressources/themes/grey/style.css?t=1657304160' rel='stylesheet' data-aid='1'></link>
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
</head>
<body>
<div id='page'>
    <header>{{TITRE}}
        <div id='colone_menu'><a href data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable logo_boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'>test_dinjection<img
                                    src='ressources/themes/images/grey/svg/fi-rr-chart-tree.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'></span></div>
                    </div>
                </div>
            </a><a href='page_accueil' data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey/svg/fi-rr-home.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'>Accueil</span></div>
                    </div>
                </div>
            </a><a href='page_explore' data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey/svg/fi-rr-comment-alt.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'>Explore</span></div>
                    </div>
                </div>
            </a><a href='page_messages' data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey/svg/fi-rr-envelope.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'>Messages</span></div>
                    </div>
                </div>
            </a><a href='page_notification' data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey/svg/fi-rr-bell.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'>Notification</span></div>
                    </div>
                </div>
            </a><a href='page_options' data-aid='7'>
                <div id='boutton_ajustable_1657304160' class='boutton_ajustable boutton_ajustable_config' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey/svg/fi-rr-settings.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'>Options</span></div>
                    </div>
                </div>
            </a></div>
    </header>
    <main>
        <div id='colone_article'>
            <div id='bar_principal'><h2 data-aid='1'>Accueil</h2></div>
            <div id='lot'>
                <div class='capsule' data-aid='16'>
                    <form action id='formulaire_inscription' class='objet_formulaire' method='POST' data-aid='15'><h3
                                data-aid='1'>INSCRIPTION</h3>
                        <div data-aid='5'><label data-aid='4'><span>Label text1</span><input name='nom' type='texte'
                                                                                             value='v1' placeholder
                                                                                             class='put'
                                                                                             data-aid='2'></label></div>
                        <div data-aid='9'><label data-aid='8'><span>Label text2</span><input name='prenom'
                                                                                             type='password' value='v2'
                                                                                             placeholder class='lut'
                                                                                             data-aid='6'></label></div>
                        <br data-aid='11'>
                        <hr data-aid='10'>
                        <input type='button' value='ok'
                               onclick='formulaire_action("formulaire_inscription",["nom","prenom"],"test" );'
                               data-aid='12'><br data-aid='13'>
                        <script src='passerelle/js/passe.js' data-aid='14'></script>
                    </form>
                </div>
            </div>
            <div id='pied_article'><img src='ressources/themes/images/animated-loading-icon.gif' data-aid='1'></div>
        </div>
        <div id='colone_infos'>
            <div id='bar_secondaire'>
                <div id='zone_de_recherche' class='boutton_ajustable' data-aid='6'>
                    <div class='boutton_ajustable_block' data-aid='5'>
                        <div class='boutton_ajustable_img' data-aid='2'><img
                                    src='ressources/themes/images/grey//svg/fi-rr-search.svg' data-aid='1'></div>
                        <div class='boutton_ajustable_text' data-aid='4'><span data-aid='3'><input titre='text' value
                                                                                                   placeholder
                                                                                                   id='bar_de_recherche'
                                                                                                   class='input_baredetexte'
                                                                                                   data-aid='1'></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id='lot_informations'>
                <div class='capsule' data-aid='1'>Block de TEST</div>
                <div class='capsule' data-aid='1'>Block de TEST</div>
                <div class='capsule' data-aid='1'>Block de TEST</div>
                <div class='capsule' data-aid='1'>Block de TEST</div>
                <div class='capsule' data-aid='1'>Block de TEST</div>
                <div class='capsule' data-aid='1'>Block de TEST</div>
            </div>
        </div>
    </main>
</div>
<script src='ressources/themes/grey/actions.js?t=1657304160' rel='stylesheet' data-aid='1'></script><?php
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "temps d'execussion de la page: $time secondes\n"
?></body>
</html>