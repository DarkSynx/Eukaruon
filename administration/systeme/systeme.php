<?php

class systeme
{

    private array $_list_modules = array();
    private string $_menu = '';
    private string $_style = '';

    public function demarrer()
    {
        $this->recuprer_les_modules();
        $this->code_menu();
        $this->page_authentification();
    }


    private function recuprer_les_modules(): void
    {
        $callback = function ($class_fichier) {
            if (is_file(MODULES_ADMIN . $class_fichier->getBasename())) {
                $nom_fichier = $class_fichier->getBasename('.php');
                //echo $nom_fichier . '<br>' . PHP_EOL;
                include_once MODULES_ADMIN . $nom_fichier . '.php';
                $compte_place = count($this->_list_modules);
                $this->_list_modules[$compte_place] = [$nom_fichier, new $nom_fichier(), null];

                if (is_dir(MODULES_ADMIN . $nom_fichier)) {
                    if (file_exists(MODULES_ADMIN . $nom_fichier . '/css/style.css')) {
                        $this->_style .= file_get_contents(MODULES_ADMIN . $nom_fichier . '/css/style.css') . PHP_EOL;
                    }
                    if (file_exists(MODULES_ADMIN . $nom_fichier . '/config.json')) {
                        $this->_list_modules[$compte_place][2] = json_decode(file_get_contents(MODULES_ADMIN . $nom_fichier . '/config.json'), true);
                    }
                }
            }
        };

        array_map($callback, iterator_to_array(
            new FilesystemIterator(MODULES_ADMIN, FilesystemIterator::SKIP_DOTS)));

    }


    private function code_menu()
    {
        $tabmenu = '';
        $tabcont = '';
        foreach ($this->_list_modules as $nombre => $valeur) {
            list($nom, $objet, $infos) = $valeur;
            $nom = str_replace('_', chr(32), $nom);
            if ($infos !== null) {
                if (array_key_exists('nom', $infos)) {
                    $nom = $infos['nom'];
                }
            }
            $tabmenu .= '<li><a href="#tabs-' . $nombre . '">' . $nom . '</a></li>' . PHP_EOL;
            $tabcont .= '<div id="tabs-' . $nombre . '" class="tabscontenu">' . $objet->contenu() . '</div>' . PHP_EOL;
        }

        if (array_key_exists('tabselect', $_GET) && $_GET['tabselect'] !== '') {
            $tabid = (string)filter_input(INPUT_GET, 'tabselect', FILTER_SANITIZE_STRING);
        } else {
            $tabid = '0';
        }

        $this->_menu = <<<MENU

<div id="tabs">
	<ul>
        {$tabmenu}
	</ul>
    {$tabcont}
</div>
<script>$( document ).ready(function() { 
    var mytabs = $('#tabs');
    mytabs.tabs();        
    var index = $('#tabs a[href="#tabs-{$tabid}"]').parent().index();
    mytabs.tabs("option", "active", index);
})</script>

MENU;
    }

    private function page_authentification($code_charger = '', $style_specifique = '')
    {
        $code_charger = $this->_menu . $code_charger;
        $style_specifique = $this->_style . $style_specifique;
        echo <<<DEMARRER
<!doctype html>
<html lang="fr">
<head>
<title>administration</title>
<link href="systeme/jquery-ui-1.13.0/jquery-ui.css" rel="stylesheet">
<script src="systeme/jquery-3.6.0.min.js"></script>
<script src="systeme/jquery-ui-1.13.0/jquery-ui.js"></script>
<script src="systeme/interface.js"></script>
<style>
html, body {margin: 0;}
body {  
 font-family: "Segoe UI", sans-serif;
 background-color:#454545;

}
#tabs{
/*position:absolute; left:0; right:0; top:0px; bottom:0;*/
/*position:relative;*/
width:90%;
height:800px;
padding:5px;
overflow:hidden;
margin:auto;

display: flex;
flex-direction: column;
}
.tabscontenu {
   position: relative;
   flex: 1;
   border: 1px solid #C0C0C0 !important;
   padding: 15px !important;
   }

h3 { background-color: #454545; color:#FFF; padding-left:15px; }
{$style_specifique}
</style>
</head>
<body>
 {$code_charger}
</body>
</html>
DEMARRER;
    }
}