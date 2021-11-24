<?php
if (!isset($contenu)) $contenu = '';

$contenu .= '<h3>Donnée d\'administration</h3>';
$contenu .= '<table>';
$donnee_sencible = $this->recuperer_data();
//var_dump($donnee_sencible);
foreach ($donnee_sencible as $index => $valeur) {
    switch ($index) {
        case 'password_administration':
            $type = 'password';
            $plus = "<button onclick=\"let index = $('#{$index}');index.attr('type' , (index.attr('type') === 'text' ? 'password':'text'));\">👁️</button><label>Identifiant et mot_de_passe coller: test@test.com1234567</label>";
            break;
        default:
            $plus = '';
            $type = 'text';
    }
    $contenu .= "<tr><td><label><b>{$index}</b></label></td><td><input id='{$index}' class='input_admin_data' type='{$type}' name='{$index}' value='{$valeur}' placeholder='{$index}'></td><td>{$plus}</td></tr>";
}
$contenu .= <<<DEB
</table>
<button id="valider_admin_data">Modifier</button>
<script>
    $( document ).ready(function() {
        $('#valider_admin_data').click(function () {
            //var html = $('#module_admin_contenu').html();
            var allinput = {};
            $('#module_admin_contenu :input.input_admin_data').each(function(){
                console.log($(this).attr('name'));
                allinput[$(this).attr('name')] = $(this).val();
            })

            $.ajax({
                type: "POST",
                url: './systeme/modules/registre_configuration/maj_admin_data.php',
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