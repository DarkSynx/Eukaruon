<?php namespace Eukaruon\administration\Data;

class gestionnaire_administratif extends exploiter_data
{

    public function verifier_motdepasse(): bool
    {
        // si dans administration_data vous avez mis production à false
        // donc vous êtes en developpeur et cela accepte qu'il n'y ai rien comme mot de passe
        // c'est trés dangeureux donc ne jamais laissé à false production
        if ($this->mdp_test()) return true;

        $identifiantmdp = $this->recuperer_valeurs();
        if ($identifiantmdp === false) return false;

        return $this->mot_de_passe_verification($identifiantmdp);

    }

    public function recuperer_valeurs(): bool|string
    {
        if (array_key_exists('identifiant', $_POST)) {
            $identifiant = filter_var($_POST['identifiant'], FILTER_VALIDATE_EMAIL);
            if ($identifiant === false) return false;
            return $identifiant . $_POST['motdepasse'];
        }
        return false;
    }
}