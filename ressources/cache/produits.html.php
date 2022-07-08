<?php error_reporting(0);
header("Content-type: text/html; charset=utf-8"); ?>
<pre class='xdebug-var-dump' dir='ltr'>
<small>C:\Users\synxcinaty\PhpstormProjects\Eukaruon\modules\Modules_pages.php:566:</small><small>string</small> <font
            color='#cc0000'>'produits.html'</font> <i>(length=13)</i>
</pre><!DOCTYPE html>
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
    echo 'xxxxxxx';
    ?>";
</div>

<footer>
    <div id="partie_haute">{{PARTIE_HAUTE}}</div>
    <div id="partie_central">{{PARTIE_CENTRAL}}</div>
    <div id="partie_basse">{{PARTIE_BASSE}}</div>
</footer>
<img alt="" src="{{IMAGE_TEST}}"/>
</body>
</html>