<?php namespace Eukaruon\administration\systeme\modules;

use exploiter_data;

class registre_configuration extends exploiter_data
{
    public function contenu()
    {
        $page_squelette = $this->recupere_html('admin');

        $contenu = $this->ajouter_contenu();


        $retour_valeur =
            $this->injecter_donnee([
                'contenu' => $contenu,
            ],
                $page_squelette);

        return $retour_valeur;
    }

    private function recupere_html($name): string
    {
        return file_get_contents(MODULES_ADMIN . basename(get_class($this)) . '/' . $name . '.html');
    }

    private function ajouter_contenu(string $contenu = ''): string
    {
        include 'registre_configuration/parties/configs_serveur.php';
        include 'registre_configuration/parties/admin_data.php';
        include 'registre_configuration/parties/donneeuniqueserveur.php';
        include 'registre_configuration/parties/modules_autorisation.php';
        return $contenu;
    }

    private function injecter_donnee($tableau_contenu, $donee)
    {
        $callback = fn(string $clee): string => '{{' . $clee . '}}';
        $clee_contenu = array_map($callback, array_keys($tableau_contenu));
        //var_dump($clee_contenu);
        return str_replace(
            $clee_contenu,
            array_values($tableau_contenu),
            $donee);
    }
}