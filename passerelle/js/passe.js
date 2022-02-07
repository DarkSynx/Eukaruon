function formulaire_action(nom_identifiant, list_nom_objet, appeller_une_page = '', desactiver_retour = false) {


    item = {};
    $.each(list_nom_objet, function (index, nom_objet) {
        valeur = $("form#" + nom_identifiant + " input[name='" + nom_objet + "']").val();
        item [nom_objet] = valeur;
    });
    console.log(item);

    $.ajax({
        method: "POST",
        url: "passerelle/actions.php",
        data: item
    })
        .done(function (msg) {
            if (!desactiver_retour) {
                alert(msg);
            }
            if (appeller_une_page !== '') {
                window.open('?page=page_' + appeller_une_page);
            }
        })
        .fail(function () {
            if (!desactiver_retour) {
                alert("Erreur survenu dans cette action");
            }
        });

}