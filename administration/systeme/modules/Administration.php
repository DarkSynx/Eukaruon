<?php namespace Eukaruon\administration\systeme\modules;

class Administration
{
    public function contenu()
    {
        $contenu = <<<CONTENU
        <p>BIENVENU DANS LE MENU ADMINISTRATION</p>
CONTENU;
        return $contenu;
    }
}