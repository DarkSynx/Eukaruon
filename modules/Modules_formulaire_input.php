<?php

namespace Eukaruon\modules;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class Modules_formulaire_input
{

    /**
     *
     */
    private const ARGUMENT = [
        'accept',
        'alt',
        'autocomplete',
        'autofocus',
        'checked',
        'dirname',
        'disabled',
        'form',
        'formaction',
        'formenctype',
        'formmethod',
        'formnovalidate',
        'formtarget',
        'height',
        'list',
        'max',
        'maxlength',
        'min',
        'minlength',
        'multiple',
        'name',
        'pattern',
        'placeholder',
        'readonly',
        'required',
        'size',
        'src',
        'step',
        'type',
        'value',
        'width'
    ];
    /**
     *indique une fonction filtre de php à utilisé
     */
    private const FONCTION_PHPFILTER = 0;
    /**
     *indique une fonction php à utilisé
     */
    private const FONCTION_PHP = 1;
    /**
     *indique que c'est une fonction de cette class si
     */
    private const FONCTION_CLASS = 2;
    /**
     *indique que c'est un filtre preg_replace
     */
    private const FONCTION_PREGREPLACE = 3;
    /**
     *indique que c'est un type différent int float
     */
    private const MODE_TYPE = 3;
    /**
     *indique que c'est un filtre preg_match
     */
    private const FONCTION_PREGMATCH = 4;
    /**
     *
     */
    private const TYPE = [

        /* Une case à cocher qui permet de sélectionner/désélectionner une valeur. */
        'checkbox' => [
            'nettoyage' => ['/[^a-zA-Z0-9]/', self::FONCTION_PREGREPLACE],
            'validation' => [],
            'argument' => [
                'value' => 'text'
            ]],

        /* Un contrôle qui permet de définir une couleur, cela ouvre un sélecteur de couleur dans les navigateurs qui le
         prennent en charge. */
        'color' => [
            'nettoyage' => ['/[^0-9#]/', self::FONCTION_PREGREPLACE],
            'validation' => ['ctype_xdigit', self::FONCTION_PHP],
            'argument' => [
                'value' => 'hexadecimal'
            ]],

        /* Un contrôle qui permet de saisir une date (composé d'un jour, d'un mois et d'une année mais sans heure), cela
         ouvre un sélecteur de date ou des roues numériques pour la sélection du jour/mois/année dans les navigateurs
        qui le prennent en charge. */
        'date' => [
            'nettoyage' => ['/[^0-9-]/', self::FONCTION_PREGREPLACE],
            'validation' => ['/\d{4}-\d{2}-\d{2}/', self::FONCTION_PREGMATCH],
            'argument' => [
                'value' => 'yyyy-mm-dd',
                'max' => 'yyyy-mm-dd',
                'min' => 'yyyy-mm-dd',
                'set' => 'int'
            ]],

        /* Un contrôle qui permet de saisir une date et une heure (sans fuseau horaire), cela ouvre un sélecteur de date
         ou des roues numériques pour la sélection de la date et de l'heure dans les navigateurs qui le prennent en
        charge */
        'datetime-local' => [
            'nettoyage' => ['/[^0-9T:-]/', self::FONCTION_PREGREPLACE],
            'validation' => ['/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', self::FONCTION_PREGMATCH],
            'argument' => [
                'value' => 'yyyy-MM-ddThh:mm',
                'max' => 'yyyy-MM-ddThh:mm',
                'min' => 'yyyy-MM-ddThh:mm',
                'set' => 'int'
            ]],

        /* Un champ qui permet de saisir une adresse électronique, il ressemble à un champ de type text, mais possède
        des fonctionnalités de validation et l'adaptation du clavier pour les navigateurs et appareils qui ont des
        claviers dynamiques. */
        'email' => [
            'nettoyage' => [FILTER_SANITIZE_EMAIL, self::FONCTION_PHPFILTER],
            'validation' => [FILTER_VALIDATE_EMAIL, self::FONCTION_PHPFILTER],
            'argument' => [
                'value' => 'text',
                'maxlength' => 'int',
                'minlength' => 'int',
                'size' => 'int'
            ]],

        /* Un contrôle qui permet de sélectionner un fichier. L'attribut accept peut être utilisé pour définir les types
         de fichiers qui peuvent être sélectionnés. */
        'file' => [
            'nettoyage' => [],
            'validation' => ['validation_fichier', self::FONCTION_CLASS],
            'argument' => [
                // Un ou plusieurs identifiants de type de fichier décrivants les types de fichier autorisés.
                'accept' => 'text',
                // La source à utiliser pour capturer des images ou des vidéos.
                'capture' => ['user', 'environment'],
                // Un objet FileList qui liste les fichiers choisis
                'files' => 'objet',
                // Un attribut booléen qui, lorsqu'il est présent, indique que plusieurs fichiers peuvent être sélectionnés.
                'multiple' => 'bool'
            ]],

        /* Un contrôle qui n'est pas affiché mais dont la valeur est envoyée au serveur. Il y a un exemple dans la
        colonne à côté, mais il est caché ! */
        'hidden' => [
            'nettoyage' => [FILTER_SANITIZE_STRING, self::FONCTION_PHPFILTER],
            'validation' => [],
            'argument' => [
                'value' => 'text'
            ]],

        /* Un bouton graphique d'envoi du formulaire. L'attribut src doit être défini avec la source de l'image et
        l'attribut alt doit être défini avec le texte alternatif si l'image est absente.*/
        'image' => [
            'nettoyage' => [],
            'validation' => [],
            'argument' => [

                // L'attribut src est utilisé afin d'indiquer le chemin vers l'image à afficher sur le bouton.
                'src' => 'url',

                /* L'attribut alt permet de fournir un texte alternatif afin que les personnes qui utilisent un outil de
                 lecture d'écran puissent avoir une meilleure idée du rôle du bouton. Ce texte sera également affiché
                 si l'image ne peut être affichée pour quelque raison que ce soit (par exemple si le chemin contient
                 une coquille). Si possible, on utilisera un texte qui correspond au libellé qui aurait été choisi
                 si le bouton avait été un bouton d'envoi texte classique.*/
                'alt' => 'text',

                /* Les attributs width et height indiquent respectivement la largeur et la hauteur, exprimées en pixels,
                selon lesquelles afficher l'image. Le bouton aura la même taille que l'image. S'il faut que la zone couverte
                 par le bouton soit plus grande que l'image, on utilisera du CSS (par exemple la propriété padding). Si une
                seule dimension est indiquée, la seconde est automatiquement ajustée pour que l'image conserve ses
                proportions originales. */
                'width' => 'int',
                'height' => 'int',

            ]],

        /* Un contrôle qui permet de saisir un mois et une année (sans fuseau horaire). */
        'month' => [
            'nettoyage' => ['/[^0-9-]/', self::FONCTION_PREGREPLACE],
            'validation' => ['/\d{4}-\d{2}/', self::FONCTION_PREGMATCH],
            'argument' => [
                'value' => 'yyyy-MM',
                'max' => 'yyyy-MM',
                'min' => 'yyyy-MM',
                'set' => 'int'
            ]],

        /* Un contrôle qui permet de saisir un nombre, affichant des curseurs pour augmenter/réduire la valeur et
        disposant d'une validation par défaut lorsqu'elle est prise en charge. Un clavier numérique est affiché pour
        certains appareils avec des claviers dynamiques. */
        'number' => [
            'nettoyage' => [
                [
                    'float' => [FILTER_SANITIZE_NUMBER_FLOAT, self::FONCTION_PHPFILTER],
                    'int' => [FILTER_VALIDATE_INT, self::FONCTION_PHPFILTER]
                ],
                self::MODE_TYPE
            ],
            'validation' => [
                [
                    'float' => [FILTER_VALIDATE_FLOAT, self::FONCTION_PHPFILTER],
                    'int' => [FILTER_VALIDATE_INT, self::FONCTION_PHPFILTER]
                ],
                self::MODE_TYPE
            ],
            'argument' => [
                'value' => 'int',
                'max' => 'int',
                'min' => 'int',
                'set' => 'int'
            ]],

        /* Un champ texte sur une seule ligne dont la valeur est masquée et qui affichera une alerte si le site n'est
        pas sécurisé. */
        'password' => [
            'nettoyage' => ['/[^a-zA-Z0-9éè_.,;:!*@#?+-]/', self::FONCTION_PREGREPLACE],
            'validation' => ['passwors_hash', self::FONCTION_CLASS],
            'argument' => [
                'value' => 'text',
                'maxlength' => 'int',
                'minlength' => 'int',
                'size' => 'int'
            ]],

        /* Un bouton radio qui permet de sélectionner une seule valeur parmi un groupe de différentes valeurs portant
        le même attribut name. */
        'radio' => [
            'nettoyage' => ['/[^a-zA-Z0-9]/', self::FONCTION_PREGREPLACE],
            'validation' => [],
            'argument' => [
                'value' => 'text',
                'checked' => 'int'
            ]],

        /* Un contrôle qui permet de saisir un nombre dont la valeur exacte n'est pas importante. Le contrôle qui
        s'affiche est une jauge horizontale avec la valeur par défaut placée au milieu. On l'utilise avec les attributs
        min et max pour définir un intervalle des valeurs acceptables. */
        'range' => [
            'nettoyage' => [
                [
                    'float' => [FILTER_SANITIZE_NUMBER_FLOAT, self::FONCTION_PHPFILTER],
                    'int' => [FILTER_VALIDATE_INT, self::FONCTION_PHPFILTER]
                ],
                self::MODE_TYPE
            ],
            'validation' => [
                [
                    'float' => [FILTER_VALIDATE_FLOAT, self::FONCTION_PHPFILTER],
                    'int' => [FILTER_VALIDATE_INT, self::FONCTION_PHPFILTER]
                ],
                self::MODE_TYPE
            ],
            'argument' => [
                'value' => 'int',
                'max' => 'int',
                'min' => 'int',
                'set' => 'int'
            ]],

        /* Un bouton qui réinitialise le contenu du formulaire avec les valeurs par défaut. Ce type d'élément n'est pas
        recommandé. */
        'reset' => [
            'nettoyage' => [],
            'validation' => [],
            'argument' => []
        ],

        /* Un champ texte sur une ligne pour des termes de recherche. Les sauts de ligne sont automatiquement retirés.
        Le contrôle peut disposer d'une icône permettant de réinitialiser le champ. Une icône de recherche est affichée
        à la place de la touche Entrée/ pour certains appareils avec des claviers dynamiques. */
        'search' => [
            'nettoyage' => ['/[^a-zA-Z0-9_ ]/', self::FONCTION_PREGREPLACE],
            'validation' => [],
            'argument' => [
                'value' => 'text',
                'maxlength' => 'int',
                'minlength' => 'int',
                'size' => 'int'
            ]],

        /* Un bouton qui envoie le formulaire. */
        'submit' => [
            'nettoyage' => [],
            'validation' => [],
            'argument' => []
        ],

        /* Un contrôle pour saisir un numéro de téléphone, qui affiche un clavier téléphonique pour certains appareils
        avec des claviers dynamiques. */
        'tel' => [
            'nettoyage' => ['/[^0-9+]/', self::FONCTION_PREGREPLACE],
            'validation' => ['validation_tel', self::FONCTION_CLASS],
            'argument' => [
                'value' => 'text',
                'maxlength' => 'int',
                'minlength' => 'int',
                'size' => 'int'
            ]],

        /* La valeur par défaut de type. Un champ texte sur une seule ligne. Les sauts de ligne sont automatiquement
        retirés. */
        'text' => [
            'nettoyage' => ['/[^0-9a-zA-Zâàäéèêë_-]/', self::FONCTION_PREGREPLACE],
            'validation' => [],
            'argument' => [
                'value' => 'text'
            ]],

        /* Un contrôle pour saisir une valeur temporelle sans fuseau horaire. */
        'time' => [
            'nettoyage' => ['/[^0-9:]/', self::FONCTION_PREGREPLACE],
            'validation' => ['/\d{2}:\d{2}/', self::FONCTION_PREGMATCH],
            'argument' => [
                'value' => 'hh:mm',
                'max' => 'hh:mm',
                'min' => 'hh:mm',
                'set' => 'int'
            ]],

        /* Un champ permettant de saisir une URL. Ce contrôle ressemble à un champ de type text, mais dispose de
        paramètres de validation et d'un clavier adapté pour les navigateurs et appareils qui le prennent en charge et qui ont un clavier dynamique. */
        'url' => [
            'nettoyage' => [FILTER_SANITIZE_URL, self::FONCTION_PHPFILTER],
            'validation' => [FILTER_VALIDATE_URL, self::FONCTION_PHPFILTER],
            'argument' => [
                'value' => 'url',
                'maxlength' => 'int',
                'minlength' => 'int',
                'size' => 'int'
            ]],

        /* Un contrôle permettant de saisir une date représentée par un numéro de semaine et une année
        (sans indication de fuseau horaire). */
        'week' => [
            'nettoyage' => ['/[^0-9W-]/', self::FONCTION_PREGREPLACE],
            'validation' => ['/\d{4}-W\d{2}/', self::FONCTION_PREGMATCH],
            'argument' => [
                'value' => 'yyyy-Www',
                'max' => 'yyyy-Www',
                'min' => 'yyyy-Www',
                'set' => 'int'
            ]],
    ];
    /**
     * @var mixed
     */
    protected mixed $preparation;


    /**
     * @param array $type
     */
    public function __construct(array $type)
    {
        $this->preparation = $type;

        /*if (isset($type['name']))
            $this->name = $type['name'];*/

    }

    /**
     * @param string $type : selectionner le type
     * @param string|null $option
     * @param string $id
     * @param string $class
     * @param string $name
     * @return static
     * @throws Exception
     */
    public static function defini(string $type, string $option = null, string $id = '', string $class = '', string $name = ''): static
    {

        if (key_exists($type, self::TYPE)) {

            return new static(self::corp($type, $option, $id, $class, $name));
        }
        throw new Exception('Erreur type inexistant');
    }

    /**
     * @param string $type
     * @param string|null $mode
     * @param string $id
     * @param string $class
     * @param string $name
     * @return array
     * @throws Exception
     */
    #[ArrayShape([0 => "string", 1 => "mixed", 2 => "string", 'name' => "string"])]
    private static function corp(string $type, string|null $mode, string $id = '', string $class = '', string $name = ''): array
    {

        $gen_id_class_name = '';
        if (strlen($id) > 0) $gen_id_class_name .= " id=\"$id\"";
        if (strlen($class) > 0) $gen_id_class_name .= " class=\"$class\"";
        if (strlen($name) > 0) $gen_id_class_name .= " name=\"$name\"";


        // ici on défini coté php générer la variable récolte qui va servire de récolte d'information
        // et à partir de recolte vous pourrez juger si le formulaire passe ou pas
        $test = <<<TEST
        /* 
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--- 
         * TEST : INPUT : $type : $name
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--- 
         */
        \$recolte['$name']['type'] = '$type';
        
        TEST;

        $test .= "/* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */" . PHP_EOL;
        if ($type == 'file') {
            $test .= "// Si \$_FILE['$name'] existe " . PHP_EOL;
            $test .= "if(isset(\$_FILE['$name'])){" . PHP_EOL . PHP_EOL . PHP_EOL;
        } else {
            $test .= "// Si \$_POST['$name'] existe " . PHP_EOL;
            $test .= "if(isset(\$_POST['$name'])){" . PHP_EOL . PHP_EOL . PHP_EOL;
        }


        $test = self::nettoyage($test, $type, $mode, $name);
        $test = self::validation($test, $type, $mode, $name);

        //$test = "\r\n -> page test [test:$type]";
        return ["<input type=\"$type\"$gen_id_class_name", self::TYPE[$type], $test, 'name' => $name];
    }

    /**
     * @param $test
     * @param $type
     * @param $mode
     * @param $name
     * @return string
     * @throws Exception
     */
    private static function nettoyage($test, $type, $mode, $name): string
    {
        $test .= "/* ===[ TEST DE : $name] === */" . PHP_EOL;

        if ( // on detecte si nettoyage est vide
            count(self::TYPE[$type]['nettoyage']) > 0 &&
            isset(self::TYPE[$type]['nettoyage'][0]) &&
            isset(self::TYPE[$type]['nettoyage'][1])
        ) {
            $nettoyage_filtre = self::TYPE[$type]['nettoyage'][0];
            $nettoyage_action = self::TYPE[$type]['nettoyage'][1];

            if ($nettoyage_action == self::MODE_TYPE) { // indique que c'est un type différent int float
                $nettoyage_filtre_2 = $nettoyage_filtre;
                if (is_null($mode)) $mode = 'int';
                $nettoyage_filtre = $nettoyage_filtre_2[$mode][0];
                $nettoyage_action = $nettoyage_filtre_2[$mode][1];
            }


            $test .= "// analyse de \$_POST['$name'] " . PHP_EOL;
            $test .= "\$recolte['$name']['avant'] = strlen(\$_POST['$name']);" . PHP_EOL;
            $test .= "// nettoyage de \$_POST['$name'] " . PHP_EOL . PHP_EOL;
            $test .= match ($nettoyage_action) {
                self::FONCTION_PHPFILTER => "\$_POST['$name'] = filter_var(\$_POST['$name'], $nettoyage_filtre);" . PHP_EOL,
                self::FONCTION_PHP => "\$_POST['$name'] = $nettoyage_filtre(\$_POST['$name']);" . PHP_EOL,
                self::FONCTION_CLASS => self::$nettoyage_filtre($name) . PHP_EOL,
                self::FONCTION_PREGREPLACE => "\$_POST['$name'] = preg_replace('$nettoyage_filtre', '', \$_POST['$name']);" . PHP_EOL,
                default => throw new Exception(
                    'Erreur dans le tableau de type une valeur ' .
                    'dans la constante TYPE[nettoyage][1] est detecter : [' .
                    $nettoyage_action . '] inconnu !'),
            };
            $test .= PHP_EOL . "// fin analyse de \$_POST['$name'] " . PHP_EOL;
            $test .= "\$recolte['$name']['apres'] = strlen(\$_POST['$name']);" . PHP_EOL;
            $test .= "\$recolte['$name']['modification'] = (\$recolte['$name']['apres'] == \$recolte['$name']['avant']);" . PHP_EOL;

            // $test .= "\$recolte['$name']['valeur'] = &\$_POST['$name'];" . PHP_EOL;

        }
        return $test;
    }

    /**
     * @param $test
     * @param $type
     * @param $mode
     * @param $name
     * @return string
     * @throws Exception
     */
    private static function validation($test, $type, $mode, $name): string
    {
        $test .= PHP_EOL;
        if ( // on detecte si validation est vide
            count(self::TYPE[$type]['validation']) > 0 &&
            isset(self::TYPE[$type]['validation'][0]) &&
            isset(self::TYPE[$type]['validation'][1])
        ) {

            list($validation_filtre, $validation_action) = self::validation_spec(self::TYPE[$type], $mode);

            $test .= "// validation de \$_POST['$name'] " . PHP_EOL;

            $test .= match ($validation_action) {
                self::FONCTION_PHPFILTER => "\$recolte['$name']['resultat'] = filter_var(\$_POST['$name'], $validation_filtre);" . PHP_EOL,
                self::FONCTION_PHP => "\$recolte['$name']['resultat'] = $validation_filtre(\$_POST['$name']);" . PHP_EOL,
                self::FONCTION_CLASS => self::$validation_filtre($name, false, $mode) . PHP_EOL,
                self::FONCTION_PREGMATCH => "\$recolte['$name']['resultat'] = preg_match('$validation_filtre', '', \$_POST['$name']);" . PHP_EOL,
                default => throw new Exception(
                    'Erreur dans le tableau de type une valeur ' .
                    'dans la constante TYPE[validation][1] est detecter : [' .
                    $validation_action . '] inconnu !'),
            };

        }
        return $test;
    }

    /**
     * @param $preparation
     * @param $mode
     * @return array
     */
    private static function validation_spec($preparation, $mode): array
    {
        // $validation_filtre = self::TYPE[$type]['validation'][0];
        // $validation_action = self::TYPE[$type]['validation'][1];

        $validation_filtre = $preparation['validation'][0];
        $validation_action = $preparation['validation'][1];

        if ($validation_action == self::MODE_TYPE) { // indique que c'est un type différent int float
            $validation_filtre_2 = $validation_filtre;
            $validation_filtre = $validation_filtre_2[$mode][0];
            $validation_action = $validation_filtre_2[$mode][1];
        }

        return [$validation_filtre, $validation_action];
    }

    /**
     * @param $name
     * @param bool $test_interne
     * @param string $option
     * @return string
     */
    private static function passwors_hash($name, bool $test_interne = false, string $option = ''): string
    {
        return "\$recolte['$name']['resultat'] = hash('sha512', \$_POST['$name']);";
    }


    // ------------------------------------
    // V A L I D A T I O N - S P E C I A L
    // ------------------------------------

    /**
     * @param $name
     * @param bool $test_interne
     * @param string $option
     * @return false|int|string
     */
    private static function validation_strtotime($name, bool $test_interne = false, string $option = ''): bool|int|string
    {
        // === false
        if ($test_interne) return strtotime($name);
        return "\$recolte['$name']['resultat'] = strtotime(\$_POST['$name']);";
    }



    //--------------------------------------------------

    /**
     * @param $name
     * @param bool $test_interne
     * @param string $option
     * @return false|int|string
     */
    private static function validation_tel($name, bool $test_interne = false, string $option = ''): bool|int|string
    {
        //+33 7 17 41 83 03
        if ($test_interne) return preg_match('/\+\d{11}|\d{10}/', $name);

        return "\$recolte['$name']['resultat'] = preg_match('/\+\d{11}|\d{10}/',\$_POST['$name']);";
    }

    /**
     * @param $name
     * @param bool $test_interne
     * @param string|null $option
     * @return string
     */
    private static function validation_fichier($name, bool $test_interne = false, string|null $option = null): string
    {
        $size = '1000000';
        if (is_null($option)) $option = 'upload';
        if ($option[0] == '[' && $option[-1] == ']') {
            $exoption = explode(',', trim(substr($option, 1, -1)));
            $option = trim($exoption[0]);
            $size = trim($exoption[1]);
            unset($exoption[0], $exoption[1]);
            $exoption = implode(',', $exoption);
        } else {
            $exoption = "
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',";
        }

        return "
    // Undefined | Multiple Files | \$_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset(\$_FILES['$name']['error']) ||
        is_array(\$_FILES['$name']['error'])
    ) {
        \$recolte['$name']['retour_test'][0] = false;
    }

    // Check \$_FILES['$name']['error'] value.
    switch (\$_FILES['$name']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
           \$recolte['$name']['retour_test'][1] = false;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            \$recolte['$name']['retour_test'][2] = false;
        default:
            \$recolte['$name']['retour_test'][3] = false;
    }

    // You should also check filesize here.
    if (\$_FILES['$name']['size'] > $size) {
        \$recolte['$name']['retour_test'][4] = false;
    }

    // DO NOT TRUST \$_FILES['$name']['mime'] VALUE !!
    // Check MIME Type by yourself.
    \$finfo = new finfo(FILEINFO_MIME_TYPE);
    if ( false === 
        (\$ext = array_search(
        \$finfo->file(\$_FILES['$name']['tmp_name']),
        array($exoption),
        true
    ))) {
       \$recolte['$name']['retour_test'][5] = false;
    }

    // All check error
    if( count(\$recolte['$name']['retour_test']) > 0 ) {
        \$recolte['$name']['resultat'] = false;    
    } else {
        \$recolte['$name']['resultat'] = true; 
        
        // You should name it uniquely.
        // DO NOT USE \$_FILES['$name']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        \$recolte['$name']['function'] = function() use (\$ext) {
            if (
                    !move_uploaded_file(
                        \$_FILES['$name']['tmp_name'],
                        sprintf(
                            '$option/%s.%s',
                            sha1_file(\$_FILES['$name']['tmp_name']),
                            \$ext
                        )
                    )
                ){
                    return false;
                } 
                return true;
            };
        }
";
    }

    /**
     * @throws Exception
     */
    public function exception(string $nom, mixed $valeur, string $test = null): static
    {
        $test_argument_list = in_array($nom, self::ARGUMENT);
        $test_argument_data = str_starts_with($nom, 'data-');
        if ($test_argument_list || $test_argument_data) {
            if ($test_argument_data) {
                if (!is_null($test)) {
                    // test spécifique
                    $this->preparation[2] .= $test; // test lier à data-
                }
            }

            return static::queue(
                ["{$this->preparation[0]} $nom=\"$valeur\"", $this->preparation[1], $this->preparation[2], 'name' => $this->preparation['name']]
            );
        }
        throw new Exception(
            "Erreur argument inexistant [ $nom ] liste argument possible => [" .
            implode(',', self::ARGUMENT) . '] ou data-nomargument'
        );

    }

    /**
     * @param $fonction
     * @return static
     */
    #[Pure] private static function queue($fonction): static
    {
        return new static($fonction);
    }

    /**
     * @throws Exception
     */
    public function argument(string $nom, mixed $valeur, string $mode = 'int', string $test = null, string|null $maxmin_test = null): static
    {
        $test_argument_list = in_array($nom, self::ARGUMENT);
        $test_argument_type = key_exists($nom, $this->preparation[1]['argument']);

        if ($test_argument_list && $test_argument_type) {
            return static::queue(
                call_user_func_array(
                    [$this, 'element'],
                    [$nom, $valeur, $this->preparation, $mode, $test, $maxmin_test])
            );
        }
        throw new Exception(
            (!$test_argument_list ? "Erreur argument inexistant [ $nom ]" :
                (!$test_argument_type ? "Erreur argument interdit [ $nom ] autoriser : [" .
                    implode(',', array_keys($this->preparation[1])) . ']' :
                    "Erreur argument [ $nom ]"))
        );
    }

    /**
     * @return array
     */
    public function finaliser(): array

    {
        $this->preparation[0] .= '>';
        return [
            $this->preparation[0],
            $this->preparation[2] .
            '}' . PHP_EOL . '/* ISSET FIN -------------------- */' . PHP_EOL,
            'input',
            $this->preparation['name']
        ];
    }

    /**
     * @param $argument
     * @param $valeur
     * @param $preparation
     * @param $mode
     * @param $test_specifique
     * @param $maxmin_test
     * @return array
     * @throws Exception
     */
    #[ArrayShape([0 => "string", 1 => "mixed", 2 => "string", 'name' => "mixed"])]
    private function element($argument, $valeur, $preparation, $mode, $test_specifique, $maxmin_test): array
    {

        $test = $preparation[2] . "";
        $name = $preparation['name'];

        /* V E R I F I C A T I O N - C O D E - U T I L I S A T E U R & F I L T R E */
        if (isset($preparation[1]['argument'][$argument])) {
            $type_var = $preparation[1]['argument'][$argument];
            // 1 vérifier la valeur du codeur donc la valeur dans
            // ->argument('maxlength', 10) si le codeur n'a pas fait d'erreur
            // le tous avec un message d'erreur

            $test .= "// -> $argument : $valeur " . PHP_EOL;

            if (!is_null($test_specifique)) {
                $test .= "// -> ajout test specifique : " . PHP_EOL;
                $test .= "$test_specifique" . PHP_EOL;
            }


            switch ($type_var) {
                case 'int':
                    if (!is_int($valeur))
                        $this->exception_element($name, $argument, $type_var);
                    $test .= "\$recolte['$name']['autre']['$argument'] = $valeur;" . PHP_EOL;
                    break;
                case 'text':
                    if (!is_string($valeur))
                        $this->exception_element($name, $argument, $type_var);
                    $test .= "\$recolte['$name']['autre']['$argument'] = '$valeur';" . PHP_EOL;
                    break;
                default:

                    if ( // on detecte si validation est vide
                        count($preparation[1]['validation']) > 0 &&
                        isset($preparation[1]['validation'][0]) &&
                        isset($preparation[1]['validation'][1])
                    ) {
                        $test .= "\$recolte['$name']['autre']['$argument'] = '$valeur';" . PHP_EOL;

                        list($validation_filtre, $validation_action) = self::validation_spec($preparation[1], $mode);

                        switch ($validation_action) {
                            case self::FONCTION_PHPFILTER : // indique une fonction filtre de php à utilisé
                                if (filter_var($valeur, $validation_filtre) === false)
                                    $this->exception_element($name, $argument, $type_var);
                                break;
                            case self::FONCTION_PHP : // indique une fonction php à utilisé
                                $val = $validation_filtre($valeur);
                                if ($val === false)
                                    $this->exception_element($name, $argument, $type_var);
                                break;
                            case self::FONCTION_CLASS : // indique que c'est une fonction de cette class si
                                if ($validation_filtre != 'passwors_hash') {
                                    $val = self::$validation_filtre($valeur, true);
                                    if ($val == false)
                                        $this->exception_element($name, $argument, $type_var);
                                }
                                break;
                            case self::FONCTION_PREGMATCH : // indique que c'est un filtre preg_replace
                                if (!preg_match($validation_filtre, $valeur))
                                    $this->exception_element($name, $argument, $type_var);
                                break;
                            default:
                                throw new Exception(
                                    'Erreur dans le tableau de type une valeur ' .
                                    'dans la constante TYPE[validation][1] est detecter : [' .
                                    $validation_action . '] inconnu !');
                        }

                    }

                //throw new Exception("Erreur type inexistant $argument => $type" );
            }

            if (!is_null($maxmin_test)) {
                $test .= "// -> ajout test maxmin : " . PHP_EOL;

                if ($preparation[1]['argument'][$argument] == 'int') {
                    if ($argument == 'min' || $argument == 'max') {
                        $test .= "\$recolte['$name']['test'] = " .
                            "(\$recolte['$name']['autre']['max'] >= \$_POST['$name'] && " .
                            "\$recolte['$name']['autre']['min'] <= \$_POST['$name']);" . PHP_EOL;
                    } else {
                        $test .= "\$recolte['$name']['test'] = " .
                            "(\$recolte['$name']['autre']['maxlength'] >= strlen(\$_POST['$name']) && " .
                            "\$recolte['$name']['autre']['minlength'] <= strlen(\$_POST['$name']));" . PHP_EOL;
                    }
                } else {
                    if ($argument == 'min' || $argument == 'max') {
                        $test .= "\$recolte['$name']['test'] = (" .
                            "strtotime(\$recolte['$name']['autre']['max']) >= strtotime(\$_POST['$name']) && " .
                            "strtotime(\$recolte['$name']['autre']['min']) <= strtotime(\$_POST['$name'])"
                            . ");" . PHP_EOL;
                    } else {
                        $test .= "\$recolte['$name']['test'] = (" .
                            "strtotime(\$recolte['$name']['autre']['maxlength']) >= strlen(\$_POST['$name']) && " .
                            "strtotime(\$recolte['$name']['autre']['minlength']) <= strlen(\$_POST['$name'])"
                            . ");" . PHP_EOL;
                    }
                }


            }


        }
        /* F - I - N :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: :: ::  */

        return ["$preparation[0] $argument=\"$valeur\"", $preparation[1], $test, 'name' => $preparation['name']];
    }

    /**
     * @throws Exception
     */
    private function exception_element($name, $argument, $type_var)
    {
        throw new Exception("Erreur dans l'argument: $name [ $argument => $type_var ]");
    }

}