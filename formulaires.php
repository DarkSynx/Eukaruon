<?php
include_once 'chemins.php';

if (isset($_GET['f'])) {
    $_GET['f'] = preg_replace('/[^[:alnum:]_]/', '', $_GET['f']);
    if (file_exists(FORMULAIRES . $_GET['f'] . '.php')) {
        include FORMULAIRES . $_GET['f'] . '.php';
    }
}
echo '';
