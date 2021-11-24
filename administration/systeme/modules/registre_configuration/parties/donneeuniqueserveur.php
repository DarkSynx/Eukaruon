<?php
include_once CHEMIN_SITE . 'chemins.php';
include CONFIGS . 'DonneeUniqueServeur.php';
if (!isset($contenu)) $contenu = '';

$contenu .= '<h3>Donnee Unique Serveur</h3>';
$contenu .= '<table>';
foreach (DonneeUniqueServeur::LISTING_VAR as $index) {
    $valeur = constant('DonneeUniqueServeur::' . $index);
    switch ($index) {
        default:
            $plus = '';
            $type = 'text';
    }
    $contenu .= "<tr><td><label><b>{$index}</b></label></td><td><input id='{$index}' class='input_donneeuniqueserveur' type='{$type}' name='{$index}' value='{$valeur}' placeholder='{$index}'></td><td>{$plus}</td></tr>";
}
$contenu .= <<<DEB
</table>
<button id="valider_donneeuniqueserveur">Modifier</button>
<script>
    $( document ).ready(function() {
        $('#valider_donneeuniqueserveur').click(function () {
            //var myhtml = $('#module_admin_contenu').html();
            var allinput = {};
            $('#module_admin_contenu :input.input_donneeuniqueserveur').each(function(){
                console.log($(this).attr('name'));
                allinput[$(this).attr('name')] = $(this).val();
            })

            $.ajax({
                type: "POST",
                url: './systeme/modules/registre_configuration/maj_donneeuniqueserveur.php',
                data: allinput,
                success: function(done){
                    alert(done);
                }
            });
        })
    });
</script>
<hr>
DEB;