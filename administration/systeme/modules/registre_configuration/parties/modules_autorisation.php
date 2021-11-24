<?php
include_once CHEMIN_SITE . 'chemins.php';
include CONFIGS . 'Modules_autorisations.php';

if (!isset($contenu)) $contenu = '';

$specifique_id = 'modulesautorisation';
$contenu .= '<h3>Autorisation des Modules</h3>';
$contenu .= '<table>';

$index = 'MODULE_INTERDIT_AUX_SOUSMODULES';
$plus = '';
$expsouval = Modules_autorisations::MODULE_INTERDIT_AUX_SOUSMODULES;
$valeur = implode(',', $expsouval);
$contenu .= "<tr><td><label><b>{$index}</b></label></td><td><textarea style='width:300px;'  id='{$index}' class='input_{$specifique_id}'  name='{$index}' placeholder='module1, module2...'>{$valeur}</textarea></td><td>{$plus}</td></tr>";

$contenu .= '</table><h3>Autorisation par Modules</h3><table id="tableautorisation">';

foreach (Modules_autorisations::AUTORISATION_PAR_MODULE as $index => $valeur) {

    switch ($index) {
        default:
            $plus = '';
            $type = 'text';
    }
    $valeur_exception = implode(',', $valeur['exception']);
    $contenu .=
        "<tr id='remove_{$index}'><td><label><b>{$index}</b></label></td><td><br/>" .
        "<label><b>global</b></label><br/><input id='{$index}_global' class='input_{$specifique_id}' type='{$type}' name='AUTORISATION_PAR_MODULE-{$index}-global' value='{$valeur['global']}' placeholder='{$index}'>" .
        "<br/>" .
        "<label><b>exception</b></label><br/><textarea style='width:300px;'  id='{$index}_exception' class='input_{$specifique_id}'  name='AUTORISATION_PAR_MODULE-{$index}-exception' placeholder='module1, module2...'>{$valeur_exception}</textarea>" .
        "</td><td>{$plus}<button onclick=\"$('#remove_{$index}').remove();\">Supprimer</button></td></tr>";
}


$contenu .= <<<DEB
</table>
<button id="valider_{$specifique_id}">Modifier</button>
<script>
    $( document ).ready(function() {
        
        $('#valider_{$specifique_id}').click(function(){
            //var html = $('#module_admin_contenu').html();
            var allinput = {};
            $('#module_admin_contenu .input_{$specifique_id}').each(function(){
                console.log($(this).attr('name'));

                allinput[$(this).attr('name')] = $(this).val();

            })

            $.ajax({
                type: "POST",
                url: './systeme/modules/registre_configuration/maj_modules_autorisation.php',
                data: allinput,
                success: function(done){
                    alert(done);
                }
            });
        })
        
    });
</script>
<hr>
<h3>Ajouter</h3>
<label><b>Nom du module :</b></label><br/><input id='add_module' type='text' name='nouveaumodule' value='' placeholder='nom du module'><br/>
<label><b>global :</b></label><p>
        0 => pas de restriction<br/>
        1 => restriction à tout les Modules primaires<br/>
        2 => restriction à la liste d'exception<br/>
        3 => applique 1 et 2</p><input id='add_module_global' type='number' name='globalmodule' value='0' placeholder='niveau: 1 2 ou 3'><br/>
<label><b>exception :</b></label><br/><textarea style='width:300px;'  id='add_module_exception'  name='exceptionmodule' placeholder='module1, module2...'></textarea>
<br/>
<button id="ajoutermodule" >ajouter</button>
<script>
    $( document ).ready(function() {
        $('button#ajoutermodule').on('click',function(){
            let nomdumodule = $('#add_module').val();
            nomdumodule = nomdumodule.replace(' ', '');
            let valeurglobal = $('#add_module_global').val();
            let valeurexception = $('#add_module_exception').val();
             if(nomdumodule !== '') {
                   let contenu = 
                    "<tr id='remove_" + nomdumodule + "'><td><label><b>" + nomdumodule + "</b></label></td><td><br/>" +
                    "<label><b>global</b></label><br/><input id='" + nomdumodule + "_global' class='input_modulesautorisation' type='text' name='AUTORISATION_PAR_MODULE-" + nomdumodule + "-global' value='" + valeurglobal + "' placeholder='" + nomdumodule + "'>" +
                    "<br/>" +
                    "<label><b>exception</b></label><br/><textarea style='width:300px;'  id='" + nomdumodule + "_exception' class='input_modulesautorisation'  name='AUTORISATION_PAR_MODULE-" + nomdumodule + "-exception' placeholder='module1, module2...'>" + valeurexception + "</textarea>" +
                    "</td><td><span style='color:red'>pas oublier de sauvegarder</span><br/><button onclick=\"$('#remove_" + nomdumodule + "').remove();\">Supprimer</button></td></tr>";
             
            $('table#tableautorisation').append(contenu);
             }
        })
    });
</script>
DEB;