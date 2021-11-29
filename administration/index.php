<?php

use Eukaruon\administration\systeme\systeme;

ini_set("session.cookie_secure", 1);
session_set_cookie_params(['samesite' => 'secure']);
session_start();
include "chemins.php";

include DATA_ADMIN . 'administration_data.php';
include DATA_ADMIN . 'exploiter_data.php';
include DATA_ADMIN . 'gestionnaire_administratif.php';

$gestionnaire_administratif = new gestionnaire_administratif();

/* cas 1 : l'utilisateur n'a ni cookie ni session ou il manque un des éléments */
/* cas 2 : tout les élements sont là mais aucun n'est bon */
/* cas 3 : tout est ok */
/* cas 4 : c'est une authentification */
/* cas 5 : c'est une erreur d'authentification */

/* 1 on vérifie si l'utilisateur à tout les éléments cookie et session
    si oui : on vérifie

           0 si trop d'erreur et temps de session terminer
                si oui : on réinitialise les variables
                         et [rafraichir la page]
                si non : affiche la [page banissement]

            1 le temps de session : temps dépassé
                   si oui : on réinitialise les variables
                            et [rafraichir la page]
            2 on vérifie l'authentification : cookie key + session key
                    si oui : et que l'on est supérieur à la moitier du temps on actualise
                             et on affiche la [page administration]
                    si non : on incrémente d'une erreur
                             et on affiche [page d'authentifiaction]
    si non : on initialise les variables
             et on affiche la [page d'authentifiaction]



 *
 *
 *
 */

if (array_key_exists('auth', $_GET) && $_GET['auth'] == 'identification') {
    verifier_authentification($gestionnaire_administratif);
}


// 1 on vérifie si l'utilisateur à tout les éléments cookie et session
if ((
        array_key_exists('key', $_COOKIE) &&
        array_key_exists('key', $_SESSION) &&
        array_key_exists('time', $_SESSION) &&
        array_key_exists('error', $_SESSION)
    ) && (
        strlen($_COOKIE['key']) == 128 &&
        strlen($_SESSION['key']) == 128
    )) {
//si oui : on vérifie

    //  0 si trop d'erreur et temps de session terminer
    if ($_SESSION['error'] >= 10 && time() > $_SESSION['time']) {
        //si oui : on réinitialise les variables

        setcookie('key', 'null', time() + 3600, NULL, NULL, true, true);

        $_SESSION['key'] = '';
        $_SESSION['time'] = 0;
        $_SESSION['error'] = 0;

        //         et [rafraichir la page]
        rafraichir();

    } // si non : affiche la [page banissement]
    elseif ($_SESSION['error'] >= 10 && time() <= $_SESSION['time']) {
        page_bannissement();
    } // si non : page authentification
    else {
        echo "1";


        //  1 le temps de session : temps dépassé
        if (time() > $_SESSION['time']) {
            // si oui : on réinitialise les variables
            setcookie('key', 'null', time() + 3600, NULL, NULL, true, true);
            $_SESSION['key'] = '';
            $_SESSION['time'] = 0;
            $_SESSION['error'] = 0;
            rafraichir();
        }

        // 2 on vérifie l'authentification : cookie key + session key
        if ($_SESSION['key'] == $_COOKIE['key'] && time() <= $_SESSION['time']) {
            //et que l'on est supérieur à la moitier du temps on actualise
            if (time() >= ($_SESSION['time'] / 2)) {
                $_SESSION['time'] = time() + 60 * 30;
                $_SESSION['error'] = 0;
            }
            echo "ok";
            // et on affiche la [page administration]
            afficher_administration();
        }

        if ($_SESSION['key'] == $_COOKIE['key'] && time() > $_SESSION['time']) {
            page_authentification();
        } else { //  si non : on incrémente d'une erreur
            $_SESSION['error'] += 1;
            //et on affiche [page d'authentifiaction]
            page_authentification();
        }

    }

} else { // si non : on initialise les variables
    setcookie('key', 'null', time() + 3600, NULL, NULL, true, true);
    $_SESSION['key'] = '';
    $_SESSION['time'] = 0;
    $_SESSION['error'] = 0;
    page_authentification();
}


function verifier_authentification($gestionnaire_administratif)
{
    // si id + pswd est ok
    if ($gestionnaire_administratif->verifier_motdepasse()) {
        /*
         * partie qui definit un identifiant en cookie et session
         * et le vérifier à chaque actualisation
         * et un temps de session qui se réactualise
         */

        $newtime = time() + 60 * 30;
        $_SESSION['time'] = $newtime;

        $clee_de_session = hash('sha512',
            time() .
            openssl_random_pseudo_bytes(8, $rater) .
            session_id()
        );

        setcookie('key', $clee_de_session, $newtime, NULL, NULL, true, true);
        $_SESSION['key'] = $clee_de_session;
        $_SESSION['error'] = 0;

        /*-----------*/
        afficher_administration();


    } else {
        $newtime = time() + 60 * 30;
        setcookie('key', 'nul', $newtime, NULL, NULL, true, true);
        $_SESSION['key'] = '';
        $_SESSION['time'] = time() + 60 * 30;
        $_SESSION['error']++;
        rafraichir();
    }
}

function afficher_administration()
{
    include SYSTEME_ADMIN . 'systeme.php';
    $systeme = new systeme();
    $systeme->demarrer();
    exit;
}

function rafraichir()
{
    echo '<!doctype html><html lang="fr"><head><meta http-equiv="refresh" content="0;URL=./"><title>refresh</title></head><body>refresh...</body></html>';
    exit;
}

function page_authentification()
{
    $keyC = !array_key_exists('key', $_COOKIE) ?: $_COOKIE['key'];
    $keyS = !array_key_exists('key', $_SESSION) ?: $_SESSION['key'];
    $time = !array_key_exists('time', $_SESSION) ?: $_SESSION['time'];
    $timex = time();
    $timez = $timex <= $timex;

    $error = array_key_exists('error', $_SESSION) ? $_SESSION['error'] : '?';
    echo <<<AUTENTIFICATION
<!doctype html>
<html lang="fr">
<head>
<title>administration</title>
<style>
#box{border: 2px solid red; position: fixed; left:0; top:0; right:0; bottom:0; display: flex; align-items: center; 
justify-content: center;}
#idt{position: absolute; text-align: center; border: 2px solid red;  height:auto; width:250px;}
</style>
</head>
<body>
<div id="box">
<div id="idt">
<p>Identification requise : {$error} |  {$keyC} == {$keyS} | {$timex} <= {$time} : {$timez} </p>
<form action="./index.php?auth=identification" method="post" >
<p><input type="text" name="identifiant" value="aaa@aaa.com" placeholder="identifiant" required></p>
<p><input type="password" name="motdepasse" value="test" placeholder="motdepasse" required></p>
<p><input type="submit" name="valider" value="valider"></p>
</form>
</div>
</div>
</body>
</html>
AUTENTIFICATION;
    exit;

}

function page_bannissement()
{
    $datesession = array_key_exists('time', $_SESSION) ? date("H:i:s", $_SESSION['time']) : '?';
    $error = array_key_exists('error', $_SESSION) ? $_SESSION['error'] : '?';
    echo <<<BANNISSEMENT
<!doctype html>
<html lang="fr">
<head>
<title>administration</title>
<style>
#box{border: 2px solid red; position: fixed; left:0; top:0; right:0; bottom:0; display: flex; align-items: center; 
justify-content: center;}
#idt{position: absolute; text-align: center; border: 2px solid red;  height:auto; width:250px;}
</style>
</head>
<body>
<div id="box">
<div id="idt">
<p>Identification ERREUR : [ {$error} ]</p>
<p>Prochaine authentification dans  : [ {$datesession} ]</p>
</div>
</div>
</body>
</html>
BANNISSEMENT;
    exit;
}