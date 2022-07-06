<?php

namespace Eukaruon\modules;

class Modules_test
{


    public function test()
    {

        if (!empty($_POST['send'])) {
            $Boutique = $_POST['Boutique'];
            $id_boutique = $_POST['id_boutique'];
            $ip = $_POST['ip'];
            $Actif = $_POST['Actif'];
            $connexion = mysqli_connect(
                'localhost', 'login', 'Motdepasse', 'ip_boutique')
            or die('Erreur de connexion: ' . mysqli_error($connexion));
            $result = mysqli_query($connexion,
                "INSERT INTO `boutiques_IP` (Boutique, id_boutique, ip, Actif) VALUES ('$Boutique','$id_boutique','$ip','$Actif')");
            if ($result) {
                $db_msg = 'Les informations de cette nouvelle boutique sont enregistrées avec succès.';
                $type_db_msg = 'success';
            } else {
                $db_msg = 'Erreur lors de la tentative d\'enregistrement des informations de cette nouvelle boutique.';
                $type_db_msg = 'error';
            }
        }
        if (!empty($_POST['update'])) {
            $Boutique = $_POST['Boutique'];
            $id_boutique = $_POST['id_boutique'];
            $ip = $_POST['ip'];
            $Actif = $_POST['Actif'];
            $connexion = mysqli_connect(
                hostname: 'localhost',
                username: 'login',
                password: 'motdepasse',
                database: 'ip_boutique') or die('Erreur de connexion: ' . mysqli_error($connexion));
            $result = mysqli_query($connexion,
                "UPDATE `boutiques_IP` SET (Boutique, ip, Actif)=('$Boutique','$ip','$Actif') WHERE id_boutique = ('$id_boutique')");
            if ($result) {
                $db_msg = 'Les informations de cette boutique sont mis a jour avec succès.';
                $type_db_msg = 'success';
            } else {
                $db_msg = 'Erreur lors de la tentative de mise à jour des informations de cette nouvelle boutique.';
                $type_db_msg = 'error';
            }

            if (!empty($db_msg)) {
                $db_msg_empty = "<p class='$type_db_msg Message'>$db_msg</p>";
            }
        }
        echo <<<AFFICHAGE
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<link rel="stylesheet" href="ip_boutique.css" />
<script type="text/javascript" src="ip_boutique.js"></script>
</head>
<body>
<div id="box">
<form id="form" enctype="multipart/form-data" onsubmit="return validate()" method="post">
<h3>Formulaire</h3>
<label>Boutique: <span>*</span></label>
<input type="text" id="Boutique" name="Boutique" placeholder="Ville de la Boutique"/>
<label>id_boutique: <span>*</span></label>
<input type="text" id="id_boutique" name="id_boutique" placeholder="ID de la boutique"/>
<label>ip: <span>*</span></label>
<input type="text" id="ip" name="ip" placeholder="IP de la box"/>
<label>Actif:</label>
<input type="text" id="Actif" name="Actif" placeholder="Actif..."/>
<input type="submit" name="send" value="Envoyer les informations dans la BDD"/>
<input type="submit" name="update" value="Mettre à jour les informations dans la BDD"/>
<div id="statusMessage">
$db_msg_empty
<div id="exportcsv"><a href="csv_export.php"> Exporter la table boutiques-IP au format CSV </a></div>
</div>
</form>
</div>
</body>
</html>
AFFICHAGE;


    }
}


