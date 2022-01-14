<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    {{META}}
    <title>test de page</title>
    {{HEAD}}
    function javascripttest() {
    return "ok";
    }
</head>
<body>
<header>
    {{HEADER}}
</header>
<nav>{{NAV}}</nav>
<div id="lot_articles">
    article
    <?php
    echo 'xxxxxxx';
    ?>
</div>

<footer>
    <div id="partie_haute">{{PARTIE_HAUTE}}</div>
    <div id="partie_central">test de texte 1</div>
    <div id="partie_basse">{{PARTIE_BASSE}}</div>
</footer>
<img alt="" src="/ressources/contenus/accueil/img/toto.png"/>
</body>
</html>