<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    {{META}}
    <title>test de page</title>
    {{HEAD}}
    {{JSTEST}}
</head>
<body>
<header>
    {{HEADER}}
</header>
<nav>{{NAV}}</nav>
<div id="lot_articles">
    test de produit
    <?php

    use Eukaruon\configs\CMD;
    use Eukaruon\pilote;

    $pilote = new pilote(
        modules_primaire: pilote::MODULES_PRIMAIRE
    //,forcer_sessionid: '12345678910111213141516'
    );

    $Modules_pages = $pilote->Charger_le_module(
        module_a_charger: 'Modules_pages',
        modules_primaire: [CMD::PAGEENCACHE, CMD::MODULES_BDD]
    );
    

    ?>
</div>

<footer>
    <div id="partie_haute">{{PARTIE_HAUTE}}</div>
    <div id="partie_central">{{PARTIE_CENTRAL}}</div>
    <div id="partie_basse">{{PARTIE_BASSE}}</div>
</footer>
<img alt="" src="{{IMAGE_TEST}}"/>
</body>
</html>