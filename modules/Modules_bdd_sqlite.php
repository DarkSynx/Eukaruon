<?php namespace Eukaruon\modules;


use Eukaruon\configs\DonneeUniqueServeur;
use Eukaruon\modules\interfaces\interfaces_bdd;
use SQLite3;

/** Module SQLite 3
 * - selection_bdd
 * - rechercher
 * - insertion
 * - mise_a_jour
 * - suppression
 * - requette_brute_query
 * - requette_brute_exec
 */
class Modules_bdd_sqlite implements interfaces_bdd
{

    /** Permet de stocker l'instanciation SQLite3 du module BDD
     * @var object|null
     */
    protected ?object $SQLite3 = null;
    protected ?object $gen = null;


    /** Instancie le gestionnaire SQlite 3 dans la variable $SQLite3
     * @param $nom_de_la_bdd
     */
    public function selection_bdd($nom_de_la_bdd)
    {
        // mode spline ou normal
        // definit une utilisation exceptionel pour Eukarion
        // d'une Bdd dans un dossier spécifique
        /*if (str_contains($nom_de_la_bdd, ':')) {
            $exp_nom_de_la_bdd = explode(':', $nom_de_la_bdd);
            $this->gen = new Modules_gen();
            $nom_dossier = $this->gen->scanner_index($exp_nom_de_la_bdd[1]);
            if ($nom_dossier) {
                $this->SQLite3 = new SQLite3(UTILISATEURS . $nom_dossier . '/' . $exp_nom_de_la_bdd[0]);
            }
        } */

        $this->SQLite3 = new SQLite3(
            BDD . $nom_de_la_bdd,
            SQLITE3_OPEN_READWRITE,
            DonneeUniqueServeur::SQLITE_ENC
        );


        $this->SQLite3->busyTimeout(5000);
        // WAL mode has better control over concurrency.
        // Source: https://www.sqlite.org/wal.html
        $this->SQLite3->exec('PRAGMA journal_mode = wal;');
        $this->SQLite3->exec('PRAGMA synchronous = NORMAL;');
        $this->SQLite3->exec('PRAGMA schema.taille_cache = 16000;');

    }

    /** Permet de réalisé une recherche dans une base de donnée
     * @param $nom_de_la_table
     * @param $colonne
     * @param $valeur_rechercher
     * @param string $type_de_recherche
     * @param string $selecteur_specifique
     * @param string $limite_recherche
     * @return mixed
     */
    public function rechercher(
        $nom_de_la_table,
        $colonne,
        $valeur_rechercher,
        $type_de_recherche = 'LIKE',
        $selecteur_specifique = '*',
        $limite_recherche = '0, 49999'
    ): mixed
    {
        $requette = "SELECT $selecteur_specifique FROM $nom_de_la_table WHERE $colonne $type_de_recherche '$valeur_rechercher' LIMIT $limite_recherche";
        return $this->SQLite3->query($requette);
    }

    /** Permet l'insertion d'une ou plusieurs valeur via un tableau ou la clé
     * - ne pas oublier de mettre les valeur qui s'auto-incrémente comme souvant
     * avec "id" à null dans votre tableau
     * -- exemple [ 'id' =>  'null',...]
     * @param $nom_de_la_table
     * @param array $tableau_valeur_ajouter
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function insertion($nom_de_la_table, $tableau_valeur_ajouter = array(), $pas_de_retour = true): mixed
    {
        $extraire_colonne_tableau = array_keys($tableau_valeur_ajouter);
        $extraire_valeur_tableau = array_values($tableau_valeur_ajouter);
        $clee_tableau = '"' . implode('","', $extraire_colonne_tableau) . '"';
        $valeur_tableau = '"' . implode('","', $extraire_valeur_tableau) . '"';
        $requette = "INSERT INTO $nom_de_la_table($clee_tableau) VALUES($valeur_tableau)";
        return $this->SQLite3->exec($requette);
    }

    /** Permet de mettre à jour des données dans une table attention tout comme la methode insertion
     * le tableau utilisera les clé pour les colonnes et les valeur.
     * la requette utilisé et celle-ci :
     * - UPDATE $nom_de_la_table SET $mise_a_jour WHERE id='$identifiant
     * - $mise_a_jour contiendra alors les valeurs construite sous cette forme ($colonne=$valeur)
     * @param $nom_de_la_table
     * @param $identifiant
     * @param array $mise_a_jour_multiple
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function mise_a_jour($nom_de_la_table, $identifiant, $mise_a_jour_multiple = array(), $pas_de_retour = true): mixed
    {
        $callback = fn(string $colonne, string $valeur): string => "\"$colonne\"='$valeur'";
        $tableau_preparer = array_map($callback, array_keys($mise_a_jour_multiple), array_values($mise_a_jour_multiple));
        $mise_a_jour = '"' . implode('","', $tableau_preparer) . '"';
        $requette = "UPDATE $nom_de_la_table SET $mise_a_jour WHERE id='$identifiant'";
        return $this->SQLite3->exec($requette);
    }

    /** Permet la suppresion d'une donné via son identifiant
     * notez que $where_id definit par default 'id' mais que dans certain
     * cas comme dans la table utilisateur j'ai utiliser 'idutilisateur' je devrais donc le spécifier dans
     * le $where_id
     * @param $nom_de_la_table
     * @param $identifiant
     * @param string $where_id
     * @param bool $pas_de_retour
     * @return mixed
     */
    public
    function suppression($nom_de_la_table, $identifiant, $where_id = 'id', $pas_de_retour = true): mixed
    {
        $requette = "DELETE FROM $nom_de_la_table WHERE $where_id IN ('$identifiant');";
        return $this->SQLite3->exec($requette);
    }

    /** Cette partie donne la possibililitée d'envoyer une requette spécifique
     * - !!! en Sqlite il y a une différence entre une requette query qui produit un retour
     * et une requette Exec qui n'attend pas de valeur de retour
     * @param $requette_specifique_brute
     * @return mixed
     */
    public
    function requette_brute_query($requette_specifique_brute): mixed
    {
        return $this->SQLite3->query($requette_specifique_brute);
    }

    /** Cette partie donne la possibililitée d'envoyer une requette spécifique
     * - !!! en Sqlite il y a une différence entre une requette query qui produit un retour
     * et une requette Exec qui n'attend pas de valeur de retour
     * @param $requette_specifique_brute
     * @return mixed
     */
    public
    function requette_brute_exec($requette_specifique_brute): mixed
    {
        return $this->SQLite3->exec($requette_specifique_brute);
    }
}