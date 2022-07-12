<?php namespace Eukaruon\modules;

use Eukaruon\configs\DonneeUniqueServeur;
use Eukaruon\modules\interfaces\interfaces_modules;
use Exception;

/** Class abstraite boite à outils contenant le nécessaire à la gestion de fonction de base comme
 *  on y regroupera tout ce qui est recurrent à des fins de création de sucre syntaxique
 *  ou de facilité des blocs de code qui doivent être réduit pour une meilleure lisibilité
 * - charger_donnee_gestionnaire
 * - load_donnee_gestionnaire
 * - DonneeUniqueServeur
 * - Module_utiliser_exploitant_BDD
 * TOUTES LES méthode  DANS PILOTE et Modules_Outils COMMENCE PAR UNE MAJUSCULE
 * visuellement c'est pour localisé l'origine des methodes
 */
class Modules_outils implements interfaces_modules
{
    /** Va accueillir l'instanciation par reference de Module_gestionnaire
     * $donnee_gestionnaire est un tableau contenant tous les modules que vous voulez utilisé
     * @var array
     */
    protected array $donnee_gestionnaire = array();

    /**
     * @var array|string[]
     */
    protected array $alpha_codec = DonneeUniqueServeur::ALPHACODE;
    // // ahoubipvcjqwdkrxelsyfmtzgn4702581369AHOUBIPVCJQWDKRXELSYFMTZGN;_+#!@|-
    // ; -> arguments
    // _ -> espaces
    // + -> encrage visuel accéder à une partie du document
    // # -> coordonné et couleur de pixel #RRGGBBAAXXYY|RRGGBBAAXXYY
    //  - -> compression d'un pixel 000000000000 = -, AAAAC3000002 = -4AC3-502, E111160AAAAA = E-4160-5A
    //  @ -> definit la taille de l'image #@HHLL|
    // ! -> lancer des scripts spécifique
    // produits = 0614021203052218
    // créé une fonction qui générer une chaine ac
    // via $alpha_codec

    /**
     * @var array
     */
    protected array $arguments_url = array();

    /**
     * @var object|null
     */
    protected ?object $Modules_bdd_utilisation_rapide = null;

    /**
     *
     */
    public function gen_alpha()
    {
        foreach ($this->alpha_codec as $key => $val) {
            echo $key . '=>' . $val . PHP_EOL;
        }
    }

    /**
     * @param null $donnee_gestionnaire
     * @param bool $pas_de_post_construct
     */
    public function __construct($donnee_gestionnaire = null, bool $pas_de_post_construct = false)
    {
        // Attention post_construct est là pour nous éviter de réinstancier l'objet inutilement
        // donc comme l'objet est déjà instancier vous pouvez relancer les fonction de __construct dans
        // post_construct contenu dans tout les objets

        if (strpos(get_class($this), '_')) {
            try {
                $nom_eclater = explode('_', get_class($this));
                if ($nom_eclater[0] == 'sousmodules') {
                    throw new Exception(
                        'Erreur => [ ' . get_class($this) . ' ] un sous module ne peut pas utiliser Modules_outils ' . PHP_EOL
                    );
                }
            } catch
            (Exception $e) {
                echo 'FATAL::' . $e->getMessage();
                exit;
            }

            if ($pas_de_post_construct || !is_null($donnee_gestionnaire)) {
                $this->post_construct($donnee_gestionnaire);
            }
        }
    }

    /**
     * @param $donnee_gestionnaire
     * @return mixed|void
     */
    public function post_construct(&$donnee_gestionnaire)
    {

        $this->Ajouter_donnee_dans_gestionnaire($donnee_gestionnaire);
    }

    /**
     * @param $chaine
     * @param false $decoder
     * @return string|null
     */
    protected function codec_ac($chaine, $decoder = false)
    {

        $cellules = strlen($chaine) - 1;
        $endec = '';

        if ($decoder) {
            $table_codec = $this->alpha_codec;
            $saut = 2;
        } else {
            $table_codec = array_flip($this->alpha_codec);
            $saut = 1;
        }

        for ($curseur = 0; $curseur < $cellules; $curseur += $saut) {
            $portion = substr($chaine, $curseur, $saut);
            if (array_key_exists($portion, $table_codec)) {
                $endec .= $table_codec[$portion];
            } else {
                return null;
            }
        }
        return $endec;
    }


    /**
     * @param $encoder
     * @return string
     */
    public function pixel_decodage($encoder)
    {
        // prototype à finir
        // il est préférable d'utilisé un fichier
        // de limité la chain URL à 128px donc 1674 caractére
        // l'objectif est de pouvoir produire des QRcode de couleur

        //$decoder = '000000000000|AAAAC3000002|E111160AAAAA';
        //$encoder = '@05550444|-|-4AC3-502|E-4160-5A|-|-4AC3-502|E-4160-5A|';

        //$taille = strlen($encoder) - 1;
        $position1 = 0;
        $nmb_portions = substr_count($encoder, '|');
        $num_portion = 1;
        //echo 'nbp:', $nmb_portions, '<br/><hr>';
        $fabrique = false;
        $image = null;

        while ($num_portion <= $nmb_portions) {
            $position2 = strpos($encoder, '|', $position1);
            $extraction = substr($encoder, $position1, ($position2 - $position1));

            if ($extraction[0] == '@') {
                //echo 'pt:', $num_portion, '/', $nmb_portions, ' -> ', $extraction, '<br/>';
                $hexa_tableau = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
                $hauteur = hexdec(substr($extraction, 1, 4));
                $largeur = hexdec(substr($extraction, 5, 4));
                // var_dump($hauteur,$largeur);
                if ($hauteur <= 4096 && $largeur <= 4096 && $hauteur >= 1 && $largeur >= 1) {
                    $image = imagecreatetruecolor($largeur, $hauteur);
                }
                $fabrique = true;
            } else if ($fabrique) {
                $decp = $this->decompress_pxc($extraction);

                //echo 'pt:', $num_portion, '/', $nmb_portions, ' -> ', $extraction, ' :: ', $decp, '<br/>';

                $R = hexdec(substr($decp, 0, 2));
                $G = hexdec(substr($decp, 2, 2));
                $B = hexdec(substr($decp, 4, 2));
                $A = hexdec(substr($decp, 6, 2));
                $X = hexdec(substr($decp, 8, 2));
                $Y = hexdec(substr($decp, 10, 2));

                // var_dump($R, $G, $B, $A, $X, $Y);

                imagesetpixel($image, $X, $Y, imagecolorallocatealpha($image, $R, $G, $B, $A));

            }

            $num_portion++;
            $position1 = $position2 + 1;
        }
        ob_start();
        imagepng($image);
        $imagedata = ob_get_clean();
        imagedestroy($image);
        return 'data:image/png;base64,' . base64_encode($imagedata);
    }

    /**
     * @param $chaine
     * @return array|mixed|string|string[]|null
     */
    protected function decompress_pxc($chaine)
    {
        if (strlen($chaine) > 12) return null;

        if ($chaine === '-') return '000000000000';
        else {

            $nmb_moins = substr_count($chaine, '-');
            $num_moins = 0;
            $position1 = 0;
            //verif si hexadecimal
            // verif ne depasse pas 12car
            // //$repeter = hexdec($extraction[1]);
            // //$car_repeter = str_repeat($extraction[1],$repeter);
            // //str_replace($chaine,)
            $tableau_parties = array();
            while ($num_moins < $nmb_moins) {
                $position2 = strpos($chaine, '-', $position1);
                $extraction = substr($chaine, $position2, 3);
                $tableau_parties[] = $extraction;
                $num_moins++;
                $position1 = $position2 + 3;
            }
            //var_dump($tableau_parties);
            $hexa_tableau = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($tableau_parties as $valeur) {
                if (in_array($valeur[1], $hexa_tableau)) {
                    $repeter = hexdec($valeur[1]);
                    $car_repeter = str_repeat($valeur[2], $repeter);
                    $chaine = str_replace($valeur, $car_repeter, $chaine);
                } else {
                    return null;
                }
            }
            return $chaine;
        }
    }



    // seul post_construct est en minuscule

    /** Permet de charger les données $donnee_gestionnaire en référence dans donnee_gestionnaire
     *  pour tout les modules qui ont besoin d'utilisé donnee_gestionnaire
     * @param $donnee_gestionnaire
     */
    protected function Ajouter_donnee_dans_gestionnaire(&$donnee_gestionnaire)
    {
        if (!is_null($donnee_gestionnaire)) {
            $this->donnee_gestionnaire = &$donnee_gestionnaire;
        }
    }


    /**
     * @return string
     */
    public function Name_module(): string
    {
        return get_class($this);
    }

    /** Même methode que load_donnee_gestionnaire mais ici spécifique pour une meilleure lisibilité
     * invoque spécifiquement DonneeUniqueServeur en référence
     * @return mixed
     */
    public function &DonneeUniqueServeur(): mixed
    {
        return $this->donnee_gestionnaire['DonneeUniqueServeur'];
    }

    /**
     * @return mixed
     */
    public function DonneeUniqueServeur_IDSERVEUR(): mixed
    {
        return $this->donnee_gestionnaire['DonneeUniqueServeur']::IDSERVEUR;
    }

    /**
     * @param string $base_de_donnee
     * @param string $table_a_charger
     * @param string $colonne
     * @param string|int $valeur
     * @return mixed
     */
    public function Modules_bdd_recherche(string $colonne, string|int $valeur, null|string $base_de_donnee = null, null|string $table_a_charger = null): mixed
    {
        $this->Modules_bdd_preparation($base_de_donnee, $table_a_charger);
        return $this->Modules_bdd_utilisation_rapide->rechercher($colonne, $valeur);
    }

    /**
     * @param string|null $base_de_donnee
     * @param string|null $table_a_charger
     */
    public function Modules_bdd_preparation(null|string $base_de_donnee = null, null|string $table_a_charger = null)
    {
        $this->Modules_bdd_utilisation_rapide();
        if (!is_null($base_de_donnee)) {
            $this->Modules_bdd_utilisation_rapide->selectionner_BDD($this->Module_utiliser_exploitant_BDD(), $base_de_donnee);
        }
        if (!is_null($table_a_charger)) {
            $this->Modules_bdd_utilisation_rapide->charger_table($table_a_charger);
        }
    }

    /**
     *
     */
    public function Modules_bdd_utilisation_rapide()
    {

        if (is_null($this->Modules_bdd_utilisation_rapide)) {
            $this->Modules_bdd_utilisation_rapide = &$this->Donnee_selectionner_du_gestionnaire('Modules_bdd');
        }
    }
    /* -------------------------------------------------------------- */
    /*
     * cette section est déstiné à l'utilisation simple et efficace
     * de la base de donnée certain pouront y voir à enchainement de
     * methode qui s'entre croise alors que ce n'est pas le cas nous somme
     * ici plus dans le cas de figure de couche de plus en plus haute
     * et donc de plus en plus simple d'utilisation pour une meilleur lisibilité
     * par exemple je ne vais pas constement définir le module ainsi que la base de donnée exploité
     * et la table. dans une majorité de cas j'ai besoin de définir une fois le module et la base de donnée
     * et même la table et dans ce besoin j'ai la lecture et l'écriture dans cette même table sans pour autant
     * redéfinir ces informations c'est pour cela que l'on va priorisé les valeur d'exploitation en premier
     * et de maniére secondaire définir le module, la BDD et la table.
     * comme dans cette exemple :
     *
     *  $ip = $this->obtenir_ip_utilisateur();
     *
     *  // ici Modules_bdd_recherche définit la BDD 'utilisateurs.db' et la table 'ip'
     *   $results = $this->Modules_bdd_recherche(
     *      'ip',
     *      $ip,
     *       'utilisateurs.db',
     *       'ip'
     *   );
     *
     *   while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
     *       if ($row['ip'] == $ip) {
     *           return !($row['blocage'] == 0);
     *       }
     *   }
     *
     *   // ici on ne redéfinit plus la BDD 'utilisateurs.db' et la table 'ip' mais c'est possible en second
     *   // argument
     *   $this->Modules_bdd_ajouter([
     *   'ip' => $ip,
     *   'tentative' => 0,
     *   'date_tentative' => time(),
     *   'blocage' => 0
     *  ]);
     */

    /** Permet d'accéder à l'instanciation voulu par reference
     * @param $donnee_selectionner
     * @return mixed
     */
    public function &Donnee_selectionner_du_gestionnaire($donnee_selectionner): mixed
    {
        // créé une erreur si l'objet n'est pas charger
        $this->Module_a_charger(verifier_existance_du_module: $donnee_selectionner);
        return $this->donnee_gestionnaire[$donnee_selectionner];
    }

    /**
     * @param $verifier_existance_du_module
     */
    public function Module_a_charger($verifier_existance_du_module)
    {


        // créé une erreur si l'objet n'est pas charger
        try {
            if (!array_key_exists($verifier_existance_du_module, $this->donnee_gestionnaire) ||
                is_null($this->donnee_gestionnaire[$verifier_existance_du_module])) {

                throw new Exception(
                    'Erreur => [ ' .
                    $verifier_existance_du_module .
                    ' ] == null or not exists in donnee_gestionnaire' . PHP_EOL .
                    'exemple use : ' . PHP_EOL .
                    '$Modules_utilisateurs = $pilote->Modules_gestionnaire(' . PHP_EOL .
                    'module_a_charger: \'Modules_utilisateurs\',' . PHP_EOL .
                    'modules_primaire: ' . strtoupper($verifier_existance_du_module) . ' | ...(flag)  or ' .
                    'array[\'' . $verifier_existance_du_module . '\',\'...\'] or ' .
                    'string\'' . $verifier_existance_du_module . '\' | \'...\' ' . PHP_EOL .
                    ');'
                );


            }
            /*echo PHP_EOL;
            var_dump(array_key_exists($verifier_existance_du_module, $this->donnee_gestionnaire));
            var_dump(is_null($this->donnee_gestionnaire[$verifier_existance_du_module]));
            var_dump($this->donnee_gestionnaire);*/
        } catch
        (Exception $e) {
            echo 'FATAL::' . $e->getMessage();
            exit;
        }
    }

    /** La constante MODULES_UTILISATEUR_INSCRIT de DonneeUniqueServeur est souvant trés utilisé
     * c'est donc pour cela que j'ai spécifiquement créé cette méthode pour l'invoquer et facile
     * son exploitation, je ne pense pas que l'on puisse faire plus simple.
     * @return mixed
     */
    public function Module_utiliser_exploitant_BDD(): mixed
    {
        return $this->donnee_gestionnaire['DonneeUniqueServeur']::MODULES_UTILISATEUR_INSCRIT;
    }

    /**
     * @param array $tableau_valeur_ajouter
     * @param string|null $base_de_donnee
     * @param string|null $table_a_charger
     * @return mixed
     */
    public function Modules_bdd_ajouter(array $tableau_valeur_ajouter = array(), null|string $base_de_donnee = null, null|string $table_a_charger = null): mixed
    {
        $this->Modules_bdd_preparation($base_de_donnee, $table_a_charger);
        return $this->Modules_bdd_utilisation_rapide->insertion($tableau_valeur_ajouter);
    }


}