<?php namespace Eukaruon\modules\interfaces;

/** Force l'ajoute de partie comme post_construct
 *
 */
interface interfaces_modules
{

    /** post_construct est là pour que l'orsque de l'objet à déjà été instancier
     * que l'on puisse relancer les fonctions aprés l'instanciation
     * @param $donnee_gestionnaire
     * @return mixed
     */
    public function post_construct(&$donnee_gestionnaire);
}