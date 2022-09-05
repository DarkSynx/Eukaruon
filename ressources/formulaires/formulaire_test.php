<?php

/**
 * class qui a pour objectif de recolter les
 * données du formulaire, de les filtrer et les
 * valider. a vous de travailler par la suite
 * avec le tableau produit par :
 * recolte, recolte_json et analyse
 */
class recolte
{

    /**
     * variable qui va recevoir le tableau de recolte
     * @var array
     */
    public const HTML =
        '<form  action="http://localhost/Eukaruon/formulaires.php?f=formulaire_test&r=1&u=0123456789" method="post">
<textarea id="id_input_2" class="classtest i121 l3 o1 " name="nametest1" ></textarea>
<input type="email" id="idtest" class="classtest i120 l2 o18 " name="nametest" maxlength="10" minlength="2" size="10" step="10" data-tableau="1,2,3">
<input type="file" id="id_input_2" class="classtest i121 l3 o1 " name="nametest2">
<select id="id_input_2" class="classtest i121 l3 o1 " name="nametest3" autofocus multiple required>
<option value="chien">dog</option>
<option value="chat">cat</option>
</select>
<input type="submit">
</form>';
    /**
     *
     */
    private const SCRIPT_JS = '
    <script>
    if("<!-- [JS_ACTIVATION] -->" === "1"){
        const type = ["textarea","input","input","select"];
        const name = ["nametest1","nametest","nametest2","nametest3"];
        const value = ["<!-- [JS_GEN_VALUE] -->"];
        
        if(
            (type[0] !== \'<!-- [JS_GEN_TYPE] -->\' && type[0] !== "") &&
            (name[0] !== \'<!-- [JS_GEN_NAME] -->\' && name[0] !== "") &&
            (value[0] !== \'<!-- [JS_GEN_VALUE] -->\' && value[0] !== "")
        )
        {
         
            for (let i = 0; i < name.length; i++) {
              let element = document.getElementsByName(name[i]);
             // console.log(element);
                switch(type[i]){
                    case \'select\':
                    case \'input\':
                         element.value = value[i];
                         element[0].setAttribute("value",value[i]);
                   break;
                   case \'textarea\':
                        element.innerText = value[i];    
                   break;
                }
            }
        }
    }
    </script>';
    /**
     * variable qui va recevoir le tableau de recolte
     * @var array
     */
    private array $_recolte = array();

    /**
     * constructeur de la class recolte
     */
    public function __construct($auto = false)
    {
        if (!$auto) $this->mode_auto();
    }

    /**
     *
     */
    private function mode_auto()
    {
        if ($this->option_identifiant()) {
            if (isset($_GET['r']) && $_GET['r'] === '1') {
                $this->gestion_analyse();
            } else {
                echo self::HTML . $this->injection_de_valeur();
            }
        } else {
            $this->_recolte = $this->donnee();
        }
    }

    /**
     * @return bool
     */
    private function option_identifiant()
    {
        $_option_identifiant = false;
        $utiliser_un_identifiant = true;
        if ($utiliser_un_identifiant == true &&
            isset($_GET['u']) === true &&
            $_GET['u'] !== '<!-- [PHP-INJECTION-IDUSE] -->' &&
            $_GET['u'] === '0123456789'
        ) {
            $_option_identifiant = true;
        } else {
            if ($utiliser_un_identifiant === false) {
                $_option_identifiant = true;
            } else {
                sleep(1);
            }
        }
        return $_option_identifiant;
    }

    /**
     *
     */
    private function gestion_analyse()
    {
        $this->_recolte = $this->donnee();
        $analyse = $this->analyse();
        // var_dump($analyse);
        $message_erreur = '';
        foreach ($analyse['Erreurs'] as $clee => $valeur) {
            $message_erreur .= '- Erreur : ' . $valeur['type'] . ' (' . $valeur['motif'] . ') <br>';
        }
        if ($analyse['Resultat'] === false) {
            echo "<p id=\"genformerror\">$message_erreur</p>" . self::HTML . $this->injection_de_valeur();
        } else {
            $chargement_page_dexploitation = 'index.php';
            if ($chargement_page_dexploitation !== '<!-- [PHP-INJECTION-LIEN] -->') {
                include $chargement_page_dexploitation;
            }
        }
    }

    /**
     * @return array
     */
    private function donnee(): array
    {
        $recolte = array();


        /*
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         * TEST : TEXTAREA : nametest1
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         */
        $recolte['nametest1']['type'] = 'textarea';
        /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */
// Si $_POST['nametest1'] existe
        if (isset($_POST['nametest1'])) {


            $filtre_base_64 = filter_var($_POST['nametest1'], FILTER_SANITIZE_ADD_SLASHES | FILTER_SANITIZE_ENCODED);
            $recolte['nametest1']['resultat'] = (($filtre_base_64 !== false && !empty($filtre_base_64) && $filtre_base_64 !== '') ? base64_encode($filtre_base_64) : false);
        }
        /* ISSET FIN -------------------- */

        /*
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         * TEST : INPUT : email : nametest
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         */
        $recolte['nametest']['type'] = 'email';
        /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */
// Si $_POST['nametest'] existe
        if (isset($_POST['nametest'])) {


            /* ===[ TEST DE : nametest] === */
// analyse de $_POST['nametest']
            $recolte['nametest']['avant'] = strlen($_POST['nametest']);
// nettoyage de $_POST['nametest']

            $_POST['nametest'] = filter_var($_POST['nametest'], 517);

// fin analyse de $_POST['nametest']
            $recolte['nametest']['apres'] = strlen($_POST['nametest']);
            $recolte['nametest']['modification'] = ($recolte['nametest']['apres'] == $recolte['nametest']['avant']);

// validation de $_POST['nametest']
            $recolte['nametest']['resultat'] = filter_var($_POST['nametest'], 274);
// -> maxlength : 10
            $recolte['nametest']['autre']['maxlength'] = 10;
// -> minlength : 2
            $recolte['nametest']['autre']['minlength'] = 2;
// -> ajout test maxmin :
            $recolte['nametest']['test'] = ($recolte['nametest']['autre']['maxlength'] >= strlen($_POST['nametest']) && $recolte['nametest']['autre']['minlength'] <= strlen($_POST['nametest']));
// -> size : 10
            $recolte['nametest']['autre']['size'] = 10;
        }
        /* ISSET FIN -------------------- */

        /*
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         * TEST : INPUT : file : nametest2
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         */
        $recolte['nametest2']['type'] = 'file';
        /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */
// Si $_FILE['nametest2'] existe
        if (isset($_FILE['nametest2'])) {


            /* ===[ TEST DE : nametest2] === */

// validation de $_POST['nametest2']

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['nametest2']['error']) ||
                is_array($_FILES['nametest2']['error'])
            ) {
                $recolte['nametest2']['retour_test'][0] = false;
            }

            // Check $_FILES['nametest2']['error'] value.
            switch ($_FILES['nametest2']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $recolte['nametest2']['retour_test'][1] = false;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $recolte['nametest2']['retour_test'][2] = false;
                default:
                    $recolte['nametest2']['retour_test'][3] = false;
            }

            // You should also check filesize here.
            if ($_FILES['nametest2']['size'] > 2000000) {
                $recolte['nametest2']['retour_test'][4] = false;
            }

            // DO NOT TRUST $_FILES['nametest2']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false ===
                ($ext = array_search(
                    $finfo->file($_FILES['nametest2']['tmp_name']),
                    array(
                        "jpg" => "image/jpeg",
                        "png" => "image/png",
                        "gif" => "image/gif"),
                    true
                ))) {
                $recolte['nametest2']['retour_test'][5] = false;
            }

            // All check error
            if (count($recolte['nametest2']['retour_test']) > 0) {
                $recolte['nametest2']['resultat'] = false;
            } else {
                $recolte['nametest2']['resultat'] = true;

                // You should name it uniquely.
                // DO NOT USE $_FILES['nametest2']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                $recolte['nametest2']['function'] = function () use ($ext) {
                    if (
                        !move_uploaded_file(
                            $_FILES['nametest2']['tmp_name'],
                            sprintf(
                                './upload/%s.%s',
                                sha1_file($_FILES['nametest2']['tmp_name']),
                                $ext
                            )
                        )
                    ) {
                        return false;
                    }
                    return true;
                };
            }

        }
        /* ISSET FIN -------------------- */

        /*
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         * TEST : SELECT : nametest3
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         */
        $recolte['nametest3']['type'] = 'select';
        /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */
// Si $_POST['nametest3'] existe
        if (isset($_POST['nametest3'])) {


// analyse de $_POST['nametest3']
            $recolte['nametest3']['avant'] = strlen($_POST['nametest3']);
// nettoyage de $_POST['nametest3']
            $_POST['nametest3'] = preg_replace('/[^a-zA-Z0-1+-_.]/', '', $_POST['nametest3']);
            $recolte['nametest3']['resultat'] = preg_match('/[^a-zA-Z0-1+-_.]/', $_POST['nametest3']);
// debut analyse 1/2 de $_POST['nametest3']
            $recolte['nametest3']['apres'] = strlen($_POST['nametest3']);
            $recolte['nametest3']['modification'] = ($recolte['nametest3']['apres'] == $recolte['nametest3']['avant']);
            $recolte['nametest3']['autre'][] = 'chien';
            $recolte['nametest3']['autre'][] = 'chat';
// fin analyse 2/2

            $recolte['nametest3']['test'] = in_array($_POST['nametest3'], $recolte['nametest3']['autre']);
        }
        /* ISSET FIN -------------------- */

        /*
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         * TEST : INPUT : submit :
         * ---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=---
         */
        $recolte['']['type'] = 'submit';
        /* /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ */
// Si $_POST[''] existe
        if (isset($_POST[''])) {


            /* ===[ TEST DE : ] === */

        }
        /* ISSET FIN -------------------- */


        $_SESSION['filter'] = 'ok';
        return $recolte;
    }

    /**
     * cette fonction est destiné à
     * déclencher une analyse complete du
     * tableau recolte et de produire un
     * tableau d'erreur ou de donnée potentiellement
     * validé; c'est à vous de revérifier que les données
     * sont bien conforme; faire confiance à un logiciel tiers
     * est déconseiller
     * @param bool $retour_json
     * @return array|string
     */
    public function analyse(bool $retour_json = false): array|string
    {
        $tableau_final = ['Resultat' => true, 'Erreurs' => array()];
        foreach ($this->_recolte as $clee => $valeur) {
            // vérifier [résultat]
            if (isset($valeur['resultat']) && $valeur['resultat'] === false) {
                if ($tableau_final['Resultat']) $tableau_final['Resultat'] = false;
                $tableau_final['Erreurs'][$clee] = $this->_recolte[$clee];
                $tableau_final['Erreurs'][$clee]['motif'] = 'valeur';
            }

            // vérifier [modification]
            if (isset($valeur['modification']) && $valeur['modification'] === false) {
                if ($tableau_final['Resultat']) $tableau_final['Resultat'] = false;
                $tableau_final['Erreurs'][$clee] = $this->_recolte[$clee];
                $tableau_final['Erreurs'][$clee]['motif'] = 'modification inopiné';
            }

            // vérifier [test]
            if (isset($valeur['test']) && $valeur['test'] === false) {
                if ($tableau_final['Resultat']) $tableau_final['Resultat'] = false;
                $tableau_final['Erreurs'][$clee] = $this->_recolte[$clee];
                $tableau_final['Erreurs'][$clee]['motif'] = 'hors-champ des possibilités';
            }

        }
        if ($retour_json) {
            header('Content-Type: application/json; charset=utf-8');
            return json_encode($tableau_final);
        }
        // var_dump($this->_recolte);
        return $tableau_final;
    }

    /**
     * @return array|string|string[]
     */
    private function injection_de_valeur()
    {
        $gen_script = '';

        if (isset($_SESSION['filter']) && $_SESSION['filter'] == 'ok') {

            $tableau_post_name = ["nametest1", "nametest", "nametest2", "nametest3"];
            $tableau_valeur_generer = array();

            if ($tableau_post_name !== ['<!-- [POST_NAME_EXIST] -->']) {
                //var_dump($_POST);
                foreach ($tableau_post_name as $clee) {
                    if (isset($_POST[$clee]))
                        $tableau_valeur_generer[$clee] = $_POST[$clee];
                }
                //var_dump($tableau_valeur_generer);

                $gen_script = str_replace(
                    [
                        '"<!-- [JS_GEN_VALUE] -->"',
                        '"<!-- [JS_ACTIVATION] -->"'
                    ],
                    [
                        '"' . implode('","', array_values($tableau_valeur_generer)) . '"',
                        '"1"'
                    ],
                    self::SCRIPT_JS);
            }
        }
        return $gen_script;
    }

    /**
     * cette fonctionpermet de renvoyer
     * le tableau récolte sans analyse
     * pour une exploitation personalisé
     * @return array
     */
    public function recolte()
    {
        return $this->_recolte;
    }

    /**
     * cette fonction permet de renvoyer
     * le tableau récolte sans analyse
     * pour une exploitation personalisé
     * au format json
     * @return false|string
     */
    public function recolte_json()
    {
        return json_encode($this->_recolte);
    }
}

/* =-=-=-=-=-=-=-=-=  ! F   I   N : C L A S S : R E C O L T E ! =-=-=-=-=-=-=-= */

new recolte();
                