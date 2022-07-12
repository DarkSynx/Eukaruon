<?php namespace Eukaruon\modules;

/** Module interface qui à pour objectif de proposé un controle facile de l'accés à la base de donnée quelques
 * soit le module de BDD charger. le projet utilisé principalement du Sqlite pour des raison minimaliste mais
 * il n'est pas exclus de créé son propre module en respactant l'interface  ./modules/interfaces/Modules_interfaces_bdd
 * notez qu'il faudra adapter la methode requette_specifique_brute
 * dans tout les cas vous pouvez aussi modifier ici l'ensemble du code mais gardez à l'esprit que ça risque de ne pas
 * rester longtemps compatible avec les mise à jours donc vous devriez créé un dossier contenant les fichiers
 * que vous avez modifier
 * - selectionner_BDD
 * - set_selection_module_bdd
 * - charger_bdd
 * - charger_table
 * - rechercher
 * - insertion
 * - mise_a_jour
 * - suppression
 * - requette_specifique_brute
 *
 */
class Modules_bdd
{
    /** Va contenir l’objet du module bdd utilisé par exemple Modules_bdd_sqlite
     * @var object|null
     */
    protected ?object $module_dbb = null;
    /** Va contenir la table sélectionnée à exploité
     * @var string|null
     */
    protected ?string $table = null;


    /**
     *
     */
    public function __construct()
    {


    }

    /** Combinaison de plusieurs methode souvent utilisée pour éviter des blocs de code en plusieurs lignes :
     * - set_selection_module_bdd : pour sélectionner le Module exploité par exemple Module_bdd_sqlite
     * - charger_bdd : pour charger directement la base de donnée
     * - charger_table (optionnel) : qui charge la table
     * - rechercher (optionnel) à condition que charger_table existe : réalise une recherche via une colonne et une valeur
     * et déclenche un retour non null, un $résults à exploite comme si dessous avec un While : $results->fetchArray(SQLITE3_ASSOC)
     * @param $type_de_BDD
     * @param $selection_BDD
     * @param null $table
     * @param null $recherche_clone
     * @param null $recherche_valeur
     * @return mixed|null
     */
    public function selectionner_BDD($type_de_BDD, $selection_BDD, $table = null, $recherche_clone = null, $recherche_valeur = null): mixed
    {
        $this->set_selection_module_bdd($type_de_BDD);
        $this->charger_bdd($selection_BDD);

        if (!is_null($table)) {
            $this->charger_table('inscription');

            if (!is_null($recherche_clone) && !is_null($recherche_valeur)) {
                return $this->rechercher($recherche_clone, $recherche_valeur);
            }
        }
        return null;
    }

    /** Permet d’instancier le module utilisé dans la variable protected module_dbb
     * @param $nom_module_exploitant_bdd
     */
    public function set_selection_module_bdd($nom_module_exploitant_bdd)
    {
        $nom_module_exploitant_bdd = 'Eukaruon\\modules\\' . $nom_module_exploitant_bdd;
        $this->module_dbb = new $nom_module_exploitant_bdd();
    }

    /** Charge le nom de la base de donnée via l'objet dans module_bdd
     * au préalable vous devez utiliser set_selection_module_bdd avant charger_bdd
     * @param $nom_de_la_bdd
     */
    public function charger_bdd($nom_de_la_bdd)
    {
        $this->module_dbb->selection_bdd($nom_de_la_bdd);
    }

    /** Indique le nom de la table sauvegarder dans la variable table
     * @param $nom_de_la_table
     */
    public function charger_table($nom_de_la_table)
    {
        $this->table = $nom_de_la_table;
    }

    /** Retourne la recherche brute de la valeur, retourn donc un $results
     * dans le cadre d'une recherche plus spécifique il est
     * préférable d'utiliser la fonction requette_specifique_brute
     * @param $colonne
     * @param $valeur_rechercher
     * @param string $type_de_recherche
     * @param string $selecteur_specifique
     * @return mixed
     */
    public function rechercher($colonne, $valeur_rechercher, string $type_de_recherche = 'LIKE', string $selecteur_specifique = '*'): mixed
    {
        return $this->module_dbb->rechercher($this->table, $colonne, $valeur_rechercher, $type_de_recherche, $selecteur_specifique);
    }

    /**
     * @return bool
     */
    public function table_charger()
    {
        return is_null($this->table);
    }

    /** Permet d'ajouter des valeur dans la base de donnée via un tableau clé valeur
     * la clé pour la colone et la valeur associer à la clé
     * @param array $tableau_valeur_ajouter
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function insertion(array $tableau_valeur_ajouter = array(), bool $pas_de_retour = true): mixed
    {
        return $this->module_dbb->insertion($this->table, $tableau_valeur_ajouter, $pas_de_retour);

    }


    /** Retourne tout le tableau avec les données mis à jours
     * si $mise_a_jour_multiple est un tableau non vide alors
     * $colonne et $valeur sont ignoré et passé à null
     * les clés et valeurs dans $mise_a_jour_multiple seront la
     * représentation des $colonne et $valeur à mettre à jours
     * il est donc conseiller de mettre précedement $colonne et
     * $valeur à null comme dans l'exemple si dessous :
     * - mise_a_jour('22335', null, null, [
     * 'utilisateur' => 'data1256',
     * 'statistique' => '123456789'
     * ]);
     * @param $identifiant
     * @param $colonne
     * @param $valeur
     * @param array $mise_a_jour_multiple
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function mise_a_jour($identifiant, $colonne, $valeur, array $mise_a_jour_multiple = array(), bool $pas_de_retour = true): mixed
    {
        return $this->module_dbb->mise_a_jour($this->table, $identifiant, $mise_a_jour_multiple, $pas_de_retour);

    }

    /** Retourne tout le tableau avec les données supprimer sauf si l'option pas de retour est à true
     * alors la suppression est définitive
     * @param $identifiant
     * @param string $where_id
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function suppression($identifiant, string $where_id = 'id', bool $pas_de_retour = true): mixed
    {
        return $this->module_dbb->suppression($this->table, $identifiant, $where_id, $pas_de_retour);

    }


    /** Permet d'envoyer une requette spécifique dite requette brute avec une demande non courante
     * @param string $requette_specifique_brute
     * @param string $type
     * @return mixed
     */
    public function requette_specifique_brute(string $requette_specifique_brute = '', string $type = 'query'): mixed
    {
        return ($type == 'query' ?
            $this->module_dbb->requette_brute_query($requette_specifique_brute) :
            $this->module_dbb->requette_brute_exec($requette_specifique_brute));

    }

}