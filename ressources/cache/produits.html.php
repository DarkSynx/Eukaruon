<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    {{META}}
    <title>test de page</title>
    {{HEAD}}
    {{JSTEST}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    {{HEADER}}
</header>
<nav>{{NAV}}</nav>
<div id="lot_articles">
    test de produit
    <form method="post">
        <label>test
            <input type="texte" name="test" value="test">
        </label>
        <input type="submit" value="valider">
    </form>
    <script>

    </script>

    <?php $Modules_objets = new Modules_objets() ?>

    <?php $Modules_objets->formulaire(
        nom: '1',
        tableau_de_type: [
            ['name' => 'nom', 'type' => 'texte', 'value' => 'v1', 'placeholder' => '', 'class' => 'put'],
            ['name' => 'prenom', 'type' => 'texte', 'value' => 'v2', 'placeholder' => '', 'class' => 'lut'],
        ],
        boutton_valider: 'ok',
        page_dappel: 'test',
        injection: ['nom' => ['code html injecter', 'avant']],
        stylecss: <<<Style
            .put{ 
                border: 1px solid red;
            }
        Style
    );
    ?>

    <?php

    use Eukaruon\configs\CMD;
    use Eukaruon\modules\Modules_habillage;
    use Eukaruon\modules\Modules_objets;
    use Eukaruon\pilote;

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

<footer>
    <div id="partie_haute">{{PARTIE_HAUTE}}</div>
    <div id="partie_central">{{PARTIE_CENTRAL}}</div>
    <div id="partie_basse">{{PARTIE_BASSE}}</div>
</footer>
<img alt=""/>
</body>
</html>