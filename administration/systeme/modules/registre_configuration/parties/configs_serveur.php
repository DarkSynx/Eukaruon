<?php
include_once CHEMIN_SITE . 'chemins.php';
include CONFIGS . 'admin_pilote.php';

if (!isset($contenu)) $contenu = '';
$donnees_de_configuration = '';
foreach (admin_pilote::REGISTRE as $index => $valeur) {
    $valeutype = gettype($valeur);
    $donnees_de_configuration .= "<li id='donnees_de_configuration_{$index}' class='donnees_de_configuration'><name data-valeur='{$valeur}' data-index='{$index}'>{$index}</name><input type='text' value='{$valeur}' placeholder='valeur en type : {$valeutype}'><button onclick=\"$('#donnees_de_configuration_{$index}').remove();\">Retirer</button><button onclick=\"$('#donnees_de_configuration_{$index} input').val($('#donnees_de_configuration_{$index} name').attr('data-valeur'));\">Restor</button></li>";
}


$contenu .= <<<CONTENU
<h3>Configuration serveur</h3>
<ul id="serveurconfig">
{$donnees_de_configuration}
</ul>
<button id="sauvgarde_donnees_de_configuration">Sauvgarder</button>
<hr/>
<label><b>variable de configuration:</b></label><br/>
<input id='donnees_de_configuration_ajouter_nom' type='text' name='donneesdeconfigurationajouternom' value='' placeholder='nom de la donnee'><br/>
<input id='donnees_de_configuration_ajouter_valeur' type='text' name='donneesdeconfigurationajoutervaleur' value='' placeholder='valeur de la donnee'><br/>
<button id="donnees_de_configuration_ajouter" >ajouter</button>
<hr/>
<script>
        $('button#donnees_de_configuration_ajouter').on('click',function(){
            let nomdeladonneeexploiter = $('#donnees_de_configuration_ajouter_nom').val();
            let valeurdeladonneeexploiter = $('#donnees_de_configuration_ajouter_valeur').val();
            nomdeladonneeexploiter = nomdeladonneeexploiter.replace(' ', '');
             if(nomdeladonneeexploiter !== '') {
                let contenu = "<li id='donnees_de_configuration_" + nomdeladonneeexploiter + "' class='donnees_de_configuration'><name data-valeur='" + valeurdeladonneeexploiter + "' data-index='" + nomdeladonneeexploiter + "'>" + nomdeladonneeexploiter + "</name><input type='text' value='" + valeurdeladonneeexploiter + "' placeholder=''><button onclick=\"$('#donnees_de_configuration_" + nomdeladonneeexploiter + "').remove();\">Retirer</button><button onclick=\"$('#donnees_de_configuration_" + nomdeladonneeexploiter + " input').val($('#donnees_de_configuration_" + nomdeladonneeexploiter + " name').attr('data-valeur'));\">Restor</button></li>";
                $('ul#serveurconfig').append(contenu);
             }
        });
        
        $('button#sauvgarde_donnees_de_configuration').on('click', function(){
           var allinput = {};
            $('ul#serveurconfig li.donnees_de_configuration').each(function(){
                let inputval = $('#' + this.id + ' input').val();
                let nameval = $('#' + this.id + ' name').attr('data-index');
                console.log(nameval);
              allinput[nameval] = inputval;
            });
            console.log(allinput);
             $.ajax({
                type: "POST",
                url: './systeme/modules/registre_configuration/maj_configs_serveur.php',
                data: allinput,
                success: function(done){
                    alert(done);
                }
            });
            
        });
        
</script>
CONTENU;