<?php
//Define('SLASH', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/'));

// si nous utilisons l'administration;
$racine =
    defined("RACINE_ADMIN") ?
        dirname(RACINE_ADMIN, 1) . '/' :
        dirname(__FILE__) . '/';

/**
 *
 */
define('RACINE', $racine);

/**
 *
 */
const MODULES = RACINE . 'modules' . '/';
/**
 *
 */
const ERREURS = RACINE . 'erreurs' . '/';
/**
 *
 */
const ARCHIVES = ERREURS . 'archives' . '/';
/**
 *
 */
const LOGS = ERREURS . 'logs' . '/';
/**
 *
 */
const SOUSMODULES = MODULES . 'sousmodules' . '/';
/**
 *
 */
const INTERFACES = MODULES . 'interfaces' . '/';
/**
 *
 */
const CONFIGS = RACINE . 'configs' . '/';
/**
 *
 */
const JOURNAUX = RACINE . 'journaux' . '/';
/**
 *
 */
const BDD = RACINE . 'bdd' . '/';

/**
 *
 */
const INDEX = BDD . 'Index' . '/';

/**
 *
 */
const REVERS = BDD . 'Revers' . '/';

/**
 *
 */
const UTILISATEURS = BDD . 'Utilisateurs' . '/';

/**
 *
 */
const IP = BDD . 'IP' . '/';
/**
 *
 */
const PAGES = RACINE . 'pages' . '/';
/**
 *
 */
const PROFILS = PAGES . 'profils' . '/';
/**
 *
 */
const RESSOURCES = RACINE . 'ressources' . '/';
/**
 *
 */
const THEMES = RESSOURCES . 'themes' . '/';
/**
 *
 */
const GENERER = RESSOURCES . 'generer' . '/';
/**
 *
 */
const CACHE = RESSOURCES . 'cache' . '/';
/**
 *
 */
const TEMP = RESSOURCES . 'temp' . '/';
/**
 *
 */
const CONTENUS = RESSOURCES . 'contenus' . '/';
/**
 *
 */
const FORMULAIRES = RESSOURCES . 'formulaires' . '/';


/*
Define('RACINE_WEB', $_SERVER["DOCUMENT_ROOT"] . '/');
const MODULES_WEB = RACINE_WEB . 'modules/';
PROFILS PAGES
*/