<?php namespace Eukaruon\configs;
class Page_en_cache
{
    /**
     * CE FICHIER EST GERER ET RECREE PAR
     * L'ADMINISTRATION
     */
    protected array $page_en_cache = [
        -1 => 'accueil',
        0 => 'produits',
        1 => 'inscription',
        2 => 'authentification',
        3 => 'tableau_de_bord',
    ];

    public function get_page_en_cache()
    {
        return $this->page_en_cache;
    }

}