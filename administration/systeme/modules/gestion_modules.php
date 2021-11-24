<?php

class gestion_modules
{
    public function contenu()
    {
        include_once CHEMIN_SITE . 'chemins.php';
        include_once CONFIGS . "CMD.php";

        $module_list = $this->rechercher();
        $listmodulesdetecter = '';
        foreach ($module_list['modules'] as $val1) {
            $listmodulesdetecter .= "<li id='detect_modules_{$val1}'>&nbsp;&nbsp;<name data-valeur='$val1' >$val1</name></li>";
        }

        $listsousmodulesdetecter = '';
        foreach ($module_list['sousmodules'] as $val2) {
            $listsousmodulesdetecter .= "<li id='detect_sousmodules_{$val2}'>&nbsp;&nbsp;<name data-valeur='$val2' >$val2</name></li>";
        }

        $contenu = '<h3>List modules</h3><ul id="listedonneemodule">';
        foreach (CMD::CMD_LISTE as $valeur) {
            $valeur2 = constant('CMD::' . $valeur);
            $contenu .= "<li id='donnee_{$valeur}' class='donnee_modules_over'><name data-valeur='$valeur' data-nom='$valeur2'>$valeur2</name><button onclick=\"$('#donnee_{$valeur}').remove();\">Retirer</button></li>";
        }
        $contenu .= '</ul>
                    <p style="font-size: 13px;">
                     - Le modules listé ici est utilisable dans votre code<br/>
                     - Si vous voulez le faire manuellement c\'est dans : Configs/CMD.php<br/>
                     - Dans "Registre > Configuration serveur > recherche_automatique_modules"<br/>
                     Vous pouvez à la valeur 1 rendre la détection de module automatique, mais<br/>
                     il y a un risque de perte de performance. 
                     </p>
                     <button id="Sauvegarder_donneeetmodules">Sauvegarder</button><hr/>';
        $contenu .= <<<CONTENU
<style>li.donnee_modules_over:hover{ text-transform: uppercase;}</style>
<h3>Ajouter</h3>
<label><b>Nom du module ou du fichier donnee exploitable:</b></label>
<br/><input id='add_donnee_exploitable' type='text' name='donneeexploitable' value='' placeholder='nom de la donnee'><br/>
<br/>
<button id="ajouterdonee" >ajouter</button>
<hr/>
<p>! par securité vous devez ajouter manuellement vos modules </p>
<h3>Module détecter</h3>
<ul id="listmodulesdetecter">
{$listmodulesdetecter}
</ul>
<h3>Sous-Module détecter</h3>
<ul id="listsousmodulesdetecter">
{$listsousmodulesdetecter}
</ul>
<script>
    $( document ).ready(function() {
        $('button#Sauvegarder_donneeetmodules').on('click',function(){
            let allinput = {};
            
            $('ul#listedonneemodule li').each(function(){
                let name = $('name', this).attr('data-valeur');
                let nom = $('name', this).attr('data-nom');
                let erreur = $('name', this).attr('data-info');
                if(erreur !== 'erreur') {
                    allinput[name] = nom;
                }
            })
            
            $.ajax({
                type: "POST",
                url: './systeme/modules/gestion_modules/save_gestion_modules.php',
                data: allinput,
                success: function(done){
                    alert(done);
                }
            }); 
        });
        
        $('button#ajouterdonee').on('click',function(){
            let nomdeladonnee = $('#add_donnee_exploitable').val();
            nomdeladonnee = nomdeladonnee.replace(' ', '');
             if(nomdeladonnee !== '') {
                 nomreel = nomdeladonnee;
                 nomdeladonnee = nomdeladonnee.toUpperCase();
                   let contenu = 
                    "<li id='donnee_" + nomdeladonnee + "' class='donnee_modules_over'>&nbsp;&nbsp;<name data-valeur='" + nomdeladonnee + "' data-nom='" + nomreel + "'>" + nomreel + "</name>" +
                     "<button onclick=\"$('#donnee_" + nomdeladonnee + "').remove();\">Retirer</button></li>";
             
            $('ul#listedonneemodule').append(contenu);
             }
        });
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
            $nom = $class_fichier->getBasename('.php');
            if (!is_dir(MODULES . $nom)) {
                $liste_fichier['modules'][] = $nom;
            }
        };
        array_map($callback, iterator_to_array(
            new FilesystemIterator(MODULES, FilesystemIterator::SKIP_DOTS)));

        $callback2 = function ($class_fichier) use (&$liste_fichier) {
            $nom = $class_fichier->getBasename('.php');
            if (!is_dir(SOUSMODULES . $nom)) {
                $liste_fichier['sousmodules'][] = $nom;
            }
        };
        array_map($callback2, iterator_to_array(
            new FilesystemIterator(SOUSMODULES, FilesystemIterator::SKIP_DOTS)));
        return $liste_fichier;
    }
}