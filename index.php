<?php

use Eukaruon\Euka;
use Eukaruon\pilote;

$time_start = microtime(true);

include 'pilote.php';
$pilote = new pilote(
    modules_primaire: pilote::MODULES_PRIMAIRE
//,forcer_sessionid: '12345678910111213141516'
);

/* Zone de code Statistique ou Autre */
// ici c'est dédier à du code produit avant la premier page
// comme pour des statistiques ou actions autre

/* Fin */
include 'Euka.php';
$Euka = new Euka();
$Euka->index($pilote); // vous devez coder votre premier page dans Euka.php->index()


$time_end = microtime(true);
$time = $time_end - $time_start;

echo PHP_EOL . PHP_EOL . "temps d'execution : $time secondes" . PHP_EOL;