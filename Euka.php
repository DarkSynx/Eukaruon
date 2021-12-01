<?php namespace Eukaruon;

use Eukaruon\configs\CMD;

/**
 * @name  Euka/Eukaruon [faham]
 * @author Manzïny/DarkSynx 2021
 * @version alpha 0.20.02.000001
 */
class Euka
{
    public function index($pilote)
    {


        //var_dump($pilote->gestion_url());


        /* module utilisateur */
        $Modules_utilisateurs = $pilote->Charger_le_module(
            module_a_charger: 'Modules_utilisateurs',
            modules_primaire: [CMD::DONNEEUNIQUESERVEUR, CMD::MODULES_BDD]
        );


        /* module pages  'Page_en_cache | Modules_bdd' */
        $Modules_pages = $pilote->Charger_le_module(
            module_a_charger: 'Modules_pages',
            modules_primaire: [CMD::PAGEENCACHE, CMD::MODULES_BDD]
        );

        $sousmodules_test = $pilote->Charger_le_module(
            module_a_charger: 'sousmodules_test'
        );

        $Modules_cache = $pilote->Charger_le_module(
            module_a_charger: 'Modules_cache'
        );


        $sousmodules_test->test();


        /* gestion de l'utilisateur et de la page */
        echo
        $Modules_pages->afficher_la_page( // on affiche la page
            $Modules_pages->preparer_page( // on préparer la page à afficher
                $Modules_utilisateurs->get_utilisateur_bdd_ok(), // on a vérifier l'ip et si l'utilisateur est en BDD
                $Modules_utilisateurs->get_utilisateur_page_direction() // la page à afficher
            ));


        $Modules_cache->cache('test:user1023456', <<<CODE
<?php class test {
function run(){
return 'teste_de_class'; 
}}
CODE
        );

//test
        // echo "Lire le fichier cache : ", $Modules_cache->cache('test:user1023456'), PHP_EOL;

//$Modules_cache->supprimer_cache('test:user1023456');

// la preparation de la page permet de savoir quel page charger
//if($Modules_pages->get_page_specifique());


        /*
         * Partie création et mise en cache
         * ne pas surpprimer
         *
         */
//$Modules_pages->preparation_mise_encache('accueil');
//$page_construite = $Modules_pages->get_profile('accueil');
//$Modules_pages->generer('accueil.html', $page_construite);
//$Modules_pages->mise_en_cache('accueil.html');

//echo '#######################', PHP_EOL;
//var_dump($Modules_pages->recuperer_generer('accueil.html'));
//echo '-----------------------', PHP_EOL;
//var_dump($Modules_pages->recuperer_cache('accueil.html'));


//echo "-------------------------\ntest DUMP\n------------------\n";
//var_dump($Modules_utilisateurs->get_IDuser());
//var_dump(session_id());
//var_dump($Modules_pages->get_page_specifique());


    }
}