<?php namespace Darksynx\Eukaruon\modules\interfaces;

/** Ce module à pour objectif de forcer le development des modules
 * avec les méthodes si dessous. je rappel aussi que requette_brute_query
 * et requette_brute_exec ont été créé pour la compatibilité avec SQlite
 * vous devrez donc vous adapter de votre côté avec un alias.
 *  - selection_bdd
 *  - rechercher
 *  - insertion
 *  - mise_a_jour
 *  - suppression
 *  - requette_brute_query
 *  - requette_brute_exec
 */
interface interfaces_bdd
{
    /** Permet de Selectionner le nom de la BDD
     * @param $nom_de_la_bdd
     * @return mixed
     */
    public function selection_bdd($nom_de_la_bdd);

    /** Permet de créé une recherche simple sur une colone avec une valeur
     * @param $nom_de_la_table
     * @param $colonne
     * @param $valeur_rechercher
     * @param string $type_de_recherche
     * @param string $selecteur_specifique
     * @return mixed
     */
    public function rechercher($nom_de_la_table, $colonne, $valeur_rechercher, $type_de_recherche = 'LIKE', $selecteur_specifique = '*'): mixed;

    /** Permet d'insérer des valeurs via un tableau
     * @param $nom_de_la_table
     * @param array $tableau_valeur_ajouter
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function insertion($nom_de_la_table, $tableau_valeur_ajouter = array(), $pas_de_retour = true): mixed;

    /** Permet de mettre à jour les données par un tableau
     * @param $nom_de_la_table
     * @param $identifiant
     * @param array $mise_a_jour_multiple
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function mise_a_jour($nom_de_la_table, $identifiant, $mise_a_jour_multiple = array(), $pas_de_retour = true): mixed;

    /** Permet la supression de donnée dans une base de donnée
     * @param $nom_de_la_table
     * @param $identifiant
     * @param bool $pas_de_retour
     * @return mixed
     */
    public function suppression($nom_de_la_table, $identifiant, $pas_de_retour = true): mixed;

    /** Permet d'envoyer une Requette brute en Query pour Sqlite bien que dans d'autre
     * gestionnaire de base de donnée vous aurez besoin que de Query plus bas la fonction
     * requette_brute_exec est là spécifiquement pour Sqlite ou un gestionnaire qui à besoin
     * de faire cette différentiation
     * @param $requette_specifique_brute
     * @return mixed
     */
    public function requette_brute_query($requette_specifique_brute): mixed;

    /** Permet d'envoyer une Requette brute en Query pour Sqlite et exclusive
     * à Sqlite  si vous n'en avais pas besoin faite vos requette dans requette_brute_query
     * Sqlite sépare les requettes avec retour et sans retour de valeur en Query et Exec
     * pas d'affollement faite comme bon vous semble
     * @param $requette_specifique_brute
     * @return mixed
     */
    public function requette_brute_exec($requette_specifique_brute): mixed;
}
