<?php


if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $url = "https";
} else {
    $url = "http";
}
$url .= "://";
$url .= $_SERVER['HTTP_HOST'];

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $url .= '/Eukaruon';
}

//$url .= $_SERVER['REQUEST_URI'];
/**
 *
 */
define('SITE_WEB', $url . '/');


/**
 *
 */
const RESSOURCES_WEB = SITE_WEB . 'ressources' . '/';

/**
 *
 */
const FORMULAIRES_WEB = RESSOURCES_WEB . 'formulaire' . '/';