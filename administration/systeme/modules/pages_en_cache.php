<?php namespace Eukaruon\administration\systeme\modules;

use Eukaruon\configs\Page_en_cache;
use FilesystemIterator;

class pages_en_cache
{
    public function contenu()
    {

        include_once CHEMIN_SITE . 'chemins.php';
        include_once CONFIGS . 'Page_en_cache.php';
        $page_en_cache = new Page_en_cache();

        $list_en_cache = $page_en_cache->get_page_en_cache();
        //var_dump($list_en_cache);
        $generer = '';
        $incrementer = -1;
        $recherche = $this->rechercher();
        foreach ($list_en_cache as $valeur) {
            //$valeur = basename($valeur,'.php');
            $valeur2 = str_replace('_', chr(32), $valeur);
            if (in_array($valeur, $recherche)) {
                $gestion_bouttons = '';

                $liste_fichier = $this->rechercher2();
                //var_dump($liste_fichier);

                $element_generer = !in_array($valeur, $liste_fichier['GENERER']) ? "<span title='pasgenerer'>&#10071;</span>" : "<span title='generer'>&#9889;</span>";
                $element_encache = !in_array($valeur, $liste_fichier['CACHE']) ? '' : "<span title='en cache'>&#128293;</span>";

                //<button id='generer_{$valeur}' class='generer_button' title='generer'>&#9889;</button><button id='cache_{$valeur}' class='cache_button' title='mise en cache'>&#128293;</button><button id='supprimer_{$valeur}' class='supprimer_button' title='supprimer du cache'>&#128721;</button>

                $gestion_bouttons .= in_array($valeur, $liste_fichier['GENERER']) ? '' : "<button id='generer_{$valeur}' class='generer_button specifique' title='generer'>&#9889;</button>";
                $gestion_bouttons .= (in_array($valeur, $liste_fichier['CACHE']) ?
                    "<button id='supprimer_{$valeur}' class='supprimer_button specifique' title='supprimer du cache'>&#128721;</button>" :
                    (!in_array($valeur, $liste_fichier['GENERER']) ? '' :
                        "<button id='cache_{$valeur}' class='cache_button specifique' title='mise en cache'>&#128293;</button>"));

                //$gestion_bouttons =

                $generer .= "<li><label>{$incrementer}</label>&nbsp;&nbsp;<name data-valeur='$valeur'>$valeur2</name>&nbsp;{$element_generer}&nbsp;{$element_encache}&nbsp;<button id='retirer_{$valeur}' class='retirer_button' title='retirer de la liste'>&#10060;</button>{$gestion_bouttons}</li>";
            } else {
                $generer .= "<li><label>{$incrementer}</label>&nbsp;&nbsp;<name data-valeur='$valeur'>$valeur2 : <span style='font-size: 12px' title='inexistant'>&#128679;</span></name><button id='retirer_{$valeur}' class='retirer_button' title='retirer de la liste'>&#10060;</button></li>";
            }
            $incrementer++;
        }


        $contenu = <<<CONTENU
<h3>List numerique des pages en cache</h3>
<button onclick="window.location.href = '?tabselect=2';"> Rafraichir (actualisé valeurs)</button>
<ul id="sortable_page_en_cache">{$generer}</ul>
<button id="sauvegardersortable_page_en_cache">Sauvegarder</button><hr/>
<p> pour qu'un fichier puissent être disponible au utilisateurs il devra :<br/>
1 - être existant comme profils dans pages/profils ou un fichier PHP dans pages/ <br/>
2 - que celui-ci soit generer il est donc assembler en un seul fichier html dans ressources/generer <span title='generer'>&#9889;</span><br/>
3 - il est envoyer dans ressources/cache (et seulement ici il est exploitable via son identifiant[-1,0,1,2...]) <span title='en cache'>&#128293;</span><br/>
les pages en cache sont organisé par un numero de position exploitable elle ne sont donc pas forcément en cache
</p>
<p>
- <span title='inexistant'>&#128679;</span>le fichier est bien indiqué dans le fichier de configuration mais qu'il est inexistant<br/>
- <span title='pasgenerer'>&#10071;</span> le fichier n'est pas generer<br/>
- <span title='generer'>&#9889;</span>le fichier est generer avant la mise en cache<br/>
- <span title='en cache'>&#128293;</span> le fichier est bien en cache et exploitable coté utiliusateur<br/>
- <span title='supprime du cache'>&#128721;</span> vous pouvez le supprimer du cache et de generer<br/>
- <span title='retirer de la liste'>&#10060;</span> vous pouvez le retirer de la liste exploitable et son son identifiant[-1,0,1,2...]
</p>

<hr/>
<h3>Page à mettre en cache</h3>
<label><b>liste:</b></label><br/>
<button id="recherche">Rechercher</button>
<ul id="actualise_list"></ul>
 <script>
 
 $( document ).ready(function() {
     actiliser_list();
  
     $('ul#sortable_page_en_cache').on('click', 'li button.specifique',function(){
         
         let parent = $('#' + this.id ).parent();
         let option = this.id.split('_');
         let allinput = {};
            allinput['option'] = option[0];
            allinput['valeur'] = $('name', parent).attr('data-valeur');
            console.log('#' + this.id);
            
            $.ajax({
                type: "POST",
                url: './systeme/modules/pages_en_cache/options_page.php',
                data: allinput,
                success: function(done){
                   alert(done);
                  window.location.href = '?tabselect=2';
                }
            });
     });
     
     
     
    $('button#sauvegardersortable_page_en_cache').on('click',function(){      
        // ConfirmDialog('Attention!!!','Voulez-vous sauvegarder ces données?',function() {     
        let allinput = {};        
            $('ul#sortable_page_en_cache li').each(function(){
                let name = $('name', this).attr('data-valeur');
                let erreur = $('name', this).attr('data-info');
                let label = $('label', this).text();
                if(erreur !== 'erreur') {
                    allinput[name] = label;
                }
            })
        
            $.ajax({
                type: "POST",
                url: './systeme/modules/pages_en_cache/sauvgarder_page.php',
                data: allinput,
                success: function(done){
                    alert(done);
                }
            }); 
       // });
    });
     
    $('ul#sortable_page_en_cache').on('click', 'li button.retirer_button',function(){ 
        const qui = $(this).parent();
        ConfirmDialog('attention!','vous voulez le retirer ?',function() {  
            qui.remove();
            renum_detect();
           // window.location.href = '?tabselect=2';
        });
    });
      
      
    $( "ul#sortable_page_en_cache" ).sortable({
      stop: function() { 
            renum_detect();
        }
    });
    
    function renum_detect(){
          let renum = -1;
          let list = [];
            $('li','ul#sortable_page_en_cache').each(function(){               
                       $('label', this).html(renum++); 
                       let name = $('name', this).text();
                       //console.log(name);
                       
                       if(list.includes(name)) {
                            $('name', this).html(name + ' : <span style="color:red"> Erreur doublon</span>');
                            $('name', this).attr('data-info','erreur');
                       }
                       else {
                        list.push( $('name', this).text() );
                       }
            });
    }
    
    $('button#recherche').on('click',function(){       
        actiliser_list();        
    });
    
    $('ul#actualise_list').on('click', 'li button',function(){       
       let name = $(this).attr('data-name');
       let paterne = '<li><label>?</label>&nbsp;&nbsp;<name data-valeur="' + name + '">' + name + 
       '</name><button id="retirer_' + name + 
       '" class="retirer_button" title="retirer de la liste">&#10060;</button><button id="generer_' + name + 
       '" class="generer_button specifique" title="generer">&#9889;</button><button id="cache_' + name + 
       '" class="cache_button specifique" title="mise en cache">&#128293;</button><button id="supprimer_' + name + 
       '" class="supprimer_button specifique" title="supprimer du cache">&#128721;</button></li>';
       $('ul#sortable_page_en_cache').append(paterne);    
        renum_detect();
    });
      
    function actiliser_list(){
            $.ajax({
                url: './systeme/modules/pages_en_cache/recherche_page.php',
                success: function(data){
                    $('ul#actualise_list').html('');
                    $.each(data, function(i, item) {
                          

                                let paterne = '<li>&nbsp;&nbsp;<name>' + item + '</name><button data-name="' + item + '">ajouter</button></li>';
                                $('ul#actualise_list').append(paterne);
                        


                    });
                }
            });
    }
 });
 



 </script>
CONTENU;
        return $contenu;
    }


    private function rechercher(): array
    {
        /* c'est un doublon du code dans recherche_page par securité
        je veu évité de tout lien entre les pages de préhension en lien
        avec les scripts, elle doivent rester indépendante */

        $liste_fichier = array();
        $callback = function ($class_fichier) use (&$liste_fichier) {
            $nom = $class_fichier->getBasename();
            if (is_dir(PROFILS . $nom)) {
                $liste_fichier[] = $nom;
            }
        };
        array_map($callback, iterator_to_array(
            new FilesystemIterator(PROFILS, FilesystemIterator::SKIP_DOTS)));

        $callback2 = function ($class_fichier) use (&$liste_fichier) {
            $nom = $class_fichier->getFilename();
            if (!is_dir(PAGES . $nom)) {
                $liste_fichier[] = $nom;
            }
        };
        array_map($callback2, iterator_to_array(
            new FilesystemIterator(PAGES, FilesystemIterator::SKIP_DOTS)));
        return $liste_fichier;
    }


    private function rechercher2(): array
    {
        /* c'est un doublon du code dans recherche_page par securité
        je veu évité de tout lien entre les pages de préhension en lien
        avec les scripts, elle doivent rester indépendante */
        // on recherche les page dans generer
        $liste_fichier = ['GENERER' => [], 'CACHE' => []];
        $callback1 = function ($class_fichier) use (&$liste_fichier) {
            $nom = $class_fichier->getBasename('.html');
            if (!is_dir(GENERER . $nom)) {
                $liste_fichier['GENERER'][] = $nom;
            }
        };
        array_map($callback1, iterator_to_array(
            new FilesystemIterator(GENERER, FilesystemIterator::SKIP_DOTS)));

        $callback2 = function ($class_fichier) use (&$liste_fichier) {
            $nom = $class_fichier->getBasename('.html.php');
            if (!is_dir(CACHE . $nom)) {
                $liste_fichier['CACHE'][] = $nom;
            }
        };
        array_map($callback2, iterator_to_array(
            new FilesystemIterator(CACHE, FilesystemIterator::SKIP_DOTS)));


        return $liste_fichier;
    }

}