<?php namespace Eukaruon;


use FilesystemIterator;
use SQLite3;

include_once 'chemins.php';

$iinc = 0;

echo '<!DOCTYPE html><html lang="fr"><head><title>Eukaruon : Installation</title></head><body><h2>INSTALLATION</h2><div style="border:1px solid grey;width:95%;margin:auto;padding:15px;">';
$folder = dirname($_SERVER['PHP_SELF']);

$htaccess = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
# Do not enable rewriting for files or directories that exist
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /Eukaruon/index.php?page=$1 [L]
</IfModule>
<FilesMatch "\.(db|sqlite|sqlite2|sqlite3|sq|sq2|sq3)$">
Deny from all
</FilesMatch>
HTACCESS;


$httf = fopen(RACINE . '.htaccess', 'w');
fwrite($httf, $htaccess);
fclose($httf);

echo 'INSTALLATION: Config "htaccess" <br><p>' . $htaccess . '</p><hr>';


/* Fabrication des unique du serveur */
$cstrong = false;
while (!$cstrong) $bytes = openssl_random_pseudo_bytes(32, $cstrong);

$alpha_code = str_shuffle('ahoubipvcjqwdkrxelsyfmtzgn4702581369AHOUBIPVCJQWDKRXELSYFMTZGN') . ';_+#!@|-';
$maxium_caractere = strlen($alpha_code);
$tableau_alpha_code = [];
for ($curseur = 0; $curseur < $maxium_caractere; $curseur++) {
    $caractere_selectionner = $alpha_code[$curseur];
    $chiffre_en_lettre = strval($curseur);
    $taille_valeur = 2 - strlen($chiffre_en_lettre);
    $chiffre_en_lettre2 = str_repeat('0', $taille_valeur) . $chiffre_en_lettre;
    //var_dump($chiffre_en_lettre2);
    $tableau_alpha_code['%' . $chiffre_en_lettre2] = $caractere_selectionner;
}

//var_dump($tableau_alpha_code);
$recurcive_path = [];
rdir(RACINE, $recurcive_path);

$donnees_serveur = [
    'PHP_VERSION' => (PHP_VERSION_ID >= 80000 ? PHP_VERSION_ID : 'ERROR_VERSION_PAS_PHP8_OU_SUPERIEUR'),
    'IDSERVEUR' => bin2hex($bytes),
    'SQLITE_ENC' => hash('sha512', $bytes . time()),
    'MODULES_UTILISATEUR_INSCRIT' => 'Modules_bdd_sqlite',
    'ALPHACODE' => [$tableau_alpha_code, false],
    'LIEN_INTERNE_VALIDE' => [$recurcive_path, false]
];

echo 'INSTALLATION ' . $iinc++ . '/2: donnees_serveur <br>';


//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
function DbCreation($nom, $script, $iinc)
{
    global $donnees_serveur;
    @unlink(BDD . $nom);
    $donnees_serveur['BDD_' . strtoupper(basename($nom, ".db"))] = $nom;
    $donnees_serveur['BDD_' . strtoupper(basename($nom, ".db")) . '_TABLE'] = basename($nom, ".db");
    $db = new SQLite3(BDD . $nom);
    $db->exec($script);
    $db->close();
    echo "INSTALLATION : $iinc/2: $nom <br>";
}

DbCreation("inscription.db",
    'create table inscription
(
    id                    INTEGER not null
        constraint inscription_pk
            primary key autoincrement
        unique,
    idsession             TEXT,
    date_session          date,
    clee_authentification TEXT,
    identifiant_unique    TEXT,
    status                INT
);', $iinc++);

DbCreation("ip.db",
    'create table ip
(
    ip                TEXT not null
        constraint ip_pk
            primary key,
    tentative         INT,
    date_tentative    date,
    blocage           INT,
    date_deblocage    INT,
    blocage_definitif INT
);', $iinc++);

DbCreation("utilisateurs.db",
    'create table utilisateurs
(
    idutilisateur INTEGER not null
        primary key autoincrement
        unique,
    nom           TEXT,
    mail          TEXT,
    bio           TEXT,
    url           TEXT,
    entreprise    TEXT
);

create unique index utilisateurs_id_uindex
    on utilisateurs (idutilisateur);'
    , $iinc++);

//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-

$DUSStream = fopen(CONFIGS . 'DonneeUniqueServeur.php', 'w');

$attention = <<<INFOS
    /** Cette Application doit être utilisée Exclusivement en PHP 8 et Supérieur
    * Attention les données dans DonneeUniqueServeur ne doivent jamais 
    * devenir des données modifiable ou accessible en dehors d'une instanciation
    * il est imperative d'utilisé que des constantes. 
    */
INFOS;

fwrite($DUSStream, '<?php namespace Eukaruon\\configs; ' . PHP_EOL . $attention . PHP_EOL . 'class DonneeUniqueServeur {' . PHP_EOL);
foreach ($donnees_serveur as $cle => $valeurs) {
    if (is_array($valeurs) && $valeurs[1] === false) { //on désactive les simples cote
        fwrite($DUSStream, 'const ' . $cle . ' = ' . var_export_style($valeurs[0]) . ';' . PHP_EOL);
    } else {
        fwrite($DUSStream, 'const ' . $cle . ' = \'' . $valeurs . '\';' . PHP_EOL);
    }

    echo '➡ ' . $cle . '<br>';

}

echo '<hr>';

fwrite($DUSStream, 'const LISTING_VAR = ' . var_export(array_keys($donnees_serveur), true) . ';' . PHP_EOL);

/*
fwrite($DUSStream, 'public function get_linsting_var() { return  self::LISTING_VAR; }' . PHP_EOL);
foreach ($donnees_serveur as $cle => $valeurs) {
    fwrite($DUSStream, 'public function get_' . $cle . '() { return  self::' . $cle . '; }' . PHP_EOL);
}
*/

fwrite($DUSStream, '}');
fclose($DUSStream);

echo 'INSTALLATION ' . $iinc++ . '/2: donnees_serveur <br>';

echo '</div></body></html>';

/**
 * @param $valeur
 * @return string
 */
function var_export_style($valeur): string
{
    return str_replace(
        ['array (', ')', "[\n    ]", "=> \n    [", "[\n]", "=> \n  [", "\r", "\n", chr(32), '%'],
        ['[', ']', '[]', "=> [", '[]', "=> [", '', '', '', ''], var_export($valeur, true));
}

/**
 * @param string $path
 * @param array $recurcive_path
 * @return string
 */
function rdir(string $path, array &$recurcive_path): string
{
    if ($path != '') {
        $extract = substr(RACINE, 0, -1);
        $save_path = str_replace([$extract, '\\'], ['', '/'], $path);
        if ($save_path != '' && $save_path != '/') $recurcive_path[] = $save_path;

        $array_list = iterator_to_array(new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS));
        foreach ($array_list as $name) {
            $pathname = $name->getpathname();
            if (is_dir($pathname) && $name->getfilename()[0] != '.'
                && $name->getfilename() != 'administration'
                // on va exclure le dossier administration
            ) {
                $path = rdir($pathname, $recurcive_path);
            }
        }
        return $path;
    }
    return '';
}