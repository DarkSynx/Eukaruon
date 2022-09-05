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
        '<!-- [HTML_GEN] -->';
    /**
     *
     */
    private const SCRIPT_JS = '
    <script>
    if("<!-- [JS_ACTIVATION] -->" === "1"){
        const type = ["<!-- [JS_GEN_TYPE] -->"];
        const name = ["<!-- [JS_GEN_NAME] -->"];
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
        $utiliser_un_identifiant = "<!-- [PHP-INJECTION-IDUSE-OK] -->";
        if ($utiliser_un_identifiant == true &&
            isset($_GET['u']) === true &&
            $_GET['u'] !== '<!-- [PHP-INJECTION-IDUSE] -->' &&
            $_GET['u'] === '"<!-- [PHP-INJECTION-IDUSE] -->"'
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
            $chargement_page_dexploitation = "<!-- [PHP-INJECTION-LIEN] -->";
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

        /* <!-- [DONNEE] --> */

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

            $tableau_post_name = ["<!-- [POST_NAME_EXIST] -->"];
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
