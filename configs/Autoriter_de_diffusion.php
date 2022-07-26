<?php

namespace Eukaruon\configs;

/**
 * CE FICHIER EST GERER ET RECREE PAR
 * L'ADMINISTRATION
 * l'Autoriter de diffusion est là pour definir ce qui est diffusable ou pas
 * à l'utilisateur c'est une autorité de controle définitive
 * toutes les pages dans ce tableau seront autorisé à la visualisation
 */
class Autoriter_de_diffusion
{
    protected array $page_autoriser = [
        0 => 'accueil',
        1 => 'produits',
        2 => 'inscription',
        3 => 'authentification',
        4 => 'tableau_de_bord',
        5 => 'test',
        6 => 'exemple',
    ];

    public function get_page_autoriser()
    {
        return $this->page_autoriser;
    }
}