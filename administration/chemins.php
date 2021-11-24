<?php
$racine_admin = defined("REROOT_ADMIN") ? REROOT_ADMIN : dirname(__FILE__) . '/';
define('RACINE_ADMIN', $racine_admin);
const DATA_ADMIN = RACINE_ADMIN . 'Data' . '/';
const SYSTEME_ADMIN = RACINE_ADMIN . 'systeme' . '/';
const JQUI_ADMIN = SYSTEME_ADMIN . 'jquery-ui-1.13.0' . '/';
const MODULES_ADMIN = SYSTEME_ADMIN . 'modules' . '/';

define('CHEMIN_SITE', dirname(__FILE__, 2) . '/');