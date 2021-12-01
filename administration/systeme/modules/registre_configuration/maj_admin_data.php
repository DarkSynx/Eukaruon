<?php
session_start();
include 'administration_outils.php';

define("REROOT_ADMIN", dirname(__FILE__, 4) . '/');
include REROOT_ADMIN . 'chemins.php';

$administration_outils = new administration_outils();

if ($administration_outils->tester_la_validiter_de_la_demande()) {


    $_POST['password_administration'] = password_hash($_POST['password_administration'], PASSWORD_BCRYPT, ["cost" => 8]);

    $fp = fopen(DATA_ADMIN . 'administration_data.php', 'w');
    fwrite($fp, '<?php namespace Eukaruon\\administration\\data; class administration_data { protected array $data_acces = ' . var_export_style($_POST) . ';}');
    fclose($fp);

    //var_dump($_POST);
    echo 'mise à jour réussite ! <br>';
} else {
    echo 'mise à jour Erreur !';
}


function var_export_style($valeur): string
{
    return str_replace(
        ['array (', ')', "[\n    ]", "=> \n    [", "[\n]", "=> \n  ["],
        ['[', ']', '[]', "=> [", '[]', "=> ["], var_export($valeur, true));
}