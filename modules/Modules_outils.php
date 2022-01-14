<?php namespace Eukaruon\modules;

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

    protected array $alpha_codec = [
        '00' => 'a', '01' => 'h', '02' => 'o', '03' => 'u',
        '04' => 'b', '05' => 'i', '06' => 'p', '07' => 'v',
        '08' => 'c', '09' => 'j', '10' => 'q', '11' => 'w',
        '12' => 'd', '13' => 'k', '14' => 'r', '15' => 'x',
        '16' => 'e', '17' => 'l', '18' => 's', '19' => 'y',
        '20' => 'f', '21' => 'm', '22' => 't', '23' => 'z',
        '24' => 'g', '25' => 'n', '26' => '4', '27' => '7',
        '28' => '0', '29' => '2', '30' => '5', '31' => '8',
        '32' => '1', '33' => '3', '34' => '6', '36' => '9',
        '37' => 'A', '38' => 'H', '39' => 'O', '40' => 'U',
        '41' => 'B', '42' => 'I', '43' => 'P', '44' => 'V',
        '45' => 'C', '46' => 'J', '47' => 'Q', '48' => 'W',
        '49' => 'D', '50' => 'K', '51' => 'R', '52' => 'X',
        '53' => 'E', '54' => 'L', '55' => 'S', '56' => 'Y',
        '57' => 'F', '58' => 'M', '59' => 'T', '60' => 'Z',
        '61' => 'G', '62' => 'N', '63' => ';', '64' => '_',
        '65' => '+', '66' => '#', '67' => '!', '68' => '?'
    ];
    // produits = 0614021203052218

    protected array $arguments_url = array();

    protected ?object $Modules_bdd_utilisation_rapide = null;

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

    public function post_construct(&$donnee_gestionnaire)
    {

        $this->Ajouter_donnee_dans_gestionnaire($donnee_gestionnaire);
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

    public function Modules_bdd_ajouter(array $tableau_valeur_ajouter = array(), null|string $base_de_donnee = null, null|string $table_a_charger = null): mixed
    {
        $this->Modules_bdd_preparation($base_de_donnee, $table_a_charger);
        return $this->Modules_bdd_utilisation_rapide->insertion($tableau_valeur_ajouter);
    }


}