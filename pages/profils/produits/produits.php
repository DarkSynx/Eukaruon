<?php


class produits
{
    public function article()
    {


        return <<<'EOD'
            test de produit
            <?php
            echo 'xxxxxxx';
            ?>";
        EOD;
    }
}