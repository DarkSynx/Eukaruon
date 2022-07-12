<?php namespace Eukaruon\modules;

use Eukaruon\modules\Level7\l7;
use FilesystemIterator;

/**
 *
 */
class Modules_Level7 extends Modules_outils
{
    /**
     * @var l7
     */
    protected l7 $Level7;
    // Attention post_construct est là pour nous éviter de réinstancier l'objet inutilement
    // donc comme l'objet est déjà instancier vous pouvez relancer les fonction de __construct dans
    // post_construct
    /**
     * @param $donnee_gestionnaire
     * @return mixed|void
     */
    public function post_construct(&$donnee_gestionnaire)
    {
        $this->Ajouter_donnee_dans_gestionnaire($donnee_gestionnaire);
        // pour charger une donnée voir avec load_donnee_gestionnaire();

        include_once MODULES . 'Level7/l7.php';
        include_once MODULES . 'Level7/syntaxe.php';
        include_once MODULES . 'Level7/syntaxing.interface.php';
        include_once MODULES . 'Level7/gestionglobal.php';

        $recuperation_liste_obtjet_syntaxique = iterator_to_array(
            new FilesystemIterator(MODULES . 'Level7/syntaxe/', FilesystemIterator::SKIP_DOTS)
        );


        $liste_chemin = array_keys($recuperation_liste_obtjet_syntaxique);

        foreach ($liste_chemin as $chemin) include_once $chemin;

        $this->Level7 = new l7(liste_syntaxe: $recuperation_liste_obtjet_syntaxique, tabulation: false);
    }

    /**
     * @param $donnees
     * @return string
     */
    public function generer_l7($donnees)
    {
        $this->Level7->start($donnees);
        return $this->Level7->getdata();
    }
}