<?php

namespace Eukaruon\modules;

class Modules_phpml
{
    /*
     * votre module  pour phpml devra nommer les fonctions par le nom de la classe
     * par exemple j'utilise <phpml:etoile.forme
     * dans ma class "etoile" je vais devoir avec une fonction forme nommé
     * ainsi : public function etoile_forme(){} c'est pour évité de créé des fonctions
     * qui existe déjà en php
     * votre module doit ressemblé à celui-ci pour qu'il puissent être exploité
     * soit contenu un constructeur avec $element1 = null,$element2 = null
     * et la fonction utilise_methode($methode, $exploit = null, $inserts = null, $contenu = null)
     * vos méthodes seront en private et devrons travailler avec le tableau $donnee
     * la function utilise_methode est là comme pré-function qui permettra la sécurité
     * et la bonne fiabilité des données avant exploitation
     */
    const CONTENU_IF = '/(*UCP)<(?|(done)|(elseif)\(((?:(?(R)\w++|[^()]*+)|(?R))*)\)|(else))>((?:(?(R)\w++|[^><]*+)|(?R))*)<\/(?:done|elseif|else)>|<\w*\s*[[:word:][:cntrl:][:blank:]+*-="\'\/\\\\.,;:[\](){}\x20?#&%|@]*\s*>/';
    const ANALYSE_IF = '/(\w*)([=><%!+*^&|\/~]*)(\w*)/';

    private string|null $_element1;
    private string|null $_element2;
    private string|null $_ac_type;

    private array $_gen_var_phpml;

    public function __construct($ac_type = null, $element1 = null, $element2 = null, $gen_var_phpml = array())
    {
        var_dump($ac_type);
        var_dump($element1);
        var_dump($element2);
        $this->_ac_type = $ac_type;
        $this->_element1 = $element1;
        $this->_element2 = $element2;
        $this->_gen_var_phpml = $gen_var_phpml;

        /*
         * ici element1 et element2
         * sont là pour vous permettre une grande flexibilité en phpml
         * et un maxium de possibilité comme des drapeaux
         */
    }


    public function utilise_methode($methode, $exploit = null, $inserts = null, $contenu = null)
    {
        // ici chaque méthode travaillera avec un tableau
        // ce qui permet par la suite selon votre code
        // et les interpretations de travailler en amont sur
        // le contenu de ce tableau et mettre en relation
        // $element1 et $element2
        $donnee = [
            'exploit' => $exploit,
            'inserts' => $inserts,
            'contenu' => $contenu
            // vous pouvez ajouter du contenu exploité dans les functions
        ];
        return $this->$methode($donnee);
    }

    private function phpml_head($donnee)
    {
        echo '---->';
        // convertion json avec '' en ""
        $donnee['exploit'] = str_ireplace(['"', '\''], ['\"', '"'], $donnee['exploit']);
        var_dump($donnee['exploit']);
        $json_to_array = json_decode($donnee['exploit'], true);
        var_dump($json_to_array);
        echo '<----';

        $doctype = $json_to_array['doctype'] ?? '';
        $lang = $json_to_array['lang'] ?? '';
        $title = $json_to_array['title'] ?? '';
        $base = '<base ' . ($json_to_array['base'] ?? '') . '>';

        $head = '';
        var_dump($json_to_array);
        if (is_array($json_to_array['head'])) {
            foreach ($json_to_array['head'] as $cle => $valeur) {
                if (is_array($valeur)) {
                    foreach ($valeur as $valeur2) {
                        if (is_array($valeur2)) {
                            $valeur2x = '';
                            foreach ($valeur2 as $cle3 => $valeur3) {
                                $valeur2x .= "$cle3=\"$valeur3\" ";
                            }
                            $head .= "<$cle $valeur2x></$cle>";
                        } else {
                            $head .= "<$cle $valeur2></$cle>";
                        }
                    }
                } else {
                    $head .= "<$cle $valeur></$cle>";
                }
            }
        }

        return "<!DOCTYPE $doctype><html lang='$lang'><head><title>$title</title>$head\r\n$base\r\n{$donnee['contenu']}</head><body>";
    }

    private function phpml_var($donnee)
    {
        if ('exploit' != '' || 'exploit' != null) {
            $this->_gen_var_phpml[$donnee['exploit']] =
                ($donnee['inserts'][0] == '@' ? ${$donnee['inserts']} : $donnee['inserts']);
        }
        return ['_gen_var_phpml', $donnee['exploit'], $this->_gen_var_phpml[$donnee['exploit']]];
    }

    private function phpml_if($donnee)
    {
        preg_match_all(self::CONTENU_IF, $donnee['contenu'], $matches_contenu, PREG_SET_ORDER, 0);
        var_dump($matches_contenu);
        $_ok_return = null;

        if ($this->_element1 != '' || $this->_element1 != null) {
            echo "------->";
            // evidement possibilité de faire bien mieu
            foreach ($matches_contenu as $clee => $valeur) {
                $mnemo = $valeur[1];
                $analyse = $valeur[2];
                $contenu = $valeur[3];

                switch (true) {
                    case ($mnemo == 'done'):
                        if ($this->exploite_analyse($this->_element1)) return $contenu;
                        break;
                    case ($mnemo == 'elseif'):
                        if ($this->exploite_analyse($analyse)) return $contenu;
                        break;
                    case ($mnemo == 'else'):
                        return $contenu;
                }

            }
            echo "<-------";
        } else {
            $this->error_afficher(
                true,
                $this->_element1,
                'this->_element1',
                "erreur dans la condition if il manques des eléments phpml:if(element1)");
        }

    }

    private function exploite_analyse($element1)
    {
        preg_match(self::ANALYSE_IF, $element1, $matches_analyse1);

        var_dump($matches_analyse1);
        $recombine = array();

        $recombine[0] = is_numeric($matches_analyse1[1]) || is_bool($matches_analyse1[1]) ||
        is_null($matches_analyse1[1]) || is_int($matches_analyse1[1]) ||
        is_float($matches_analyse1[1]) ? $matches_analyse1[1] : $this->_gen_var_phpml[$matches_analyse1[1]];

        $recombine[1] = $matches_analyse1[2];

        $recombine[2] = is_numeric($matches_analyse1[3]) || is_bool($matches_analyse1[3]) ||
        is_null($matches_analyse1[3]) || is_int($matches_analyse1[3]) ||
        is_float($matches_analyse1[3]) ? $matches_analyse1[3] : $this->_gen_var_phpml[$matches_analyse1[3]];

        return $this->operator_switch($recombine);
    }

    private function operator_switch($recombine)
    {
        switch ($recombine[1]) {
            case '===':
                $retour_test = ($recombine[0] === $recombine[2]);
                break;
            case '==':
                $retour_test = ($recombine[0] == $recombine[2]);
                break;
            case '!==':
                $retour_test = ($recombine[0] !== $recombine[2]);
                break;
            case '!=':
                $retour_test = ($recombine[0] != $recombine[2]);
                break;
            case '<=':
                $retour_test = ($recombine[0] <= $recombine[2]);
                break;
            case '>=':
                $retour_test = ($recombine[0] >= $recombine[2]);
                break;
            case '>':
                $retour_test = ($recombine[0] > $recombine[2]);
                break;
            case '<':
                $retour_test = ($recombine[0] < $recombine[2]);
                break;
            case '<=>':
                $retour_test = ($recombine[0] <=> $recombine[2]);
                break;
            case '<>':
                $retour_test = ($recombine[0] <> $recombine[2]);
                break;
            case '&&':
                $retour_test = ($recombine[0] && $recombine[2]);
                break;
            case '||':
                $retour_test = ($recombine[0] || $recombine[2]);
                break;
            case '|':
                $retour_test = ($recombine[0] | $recombine[2]);
                break;
            case '%':
                $retour_test = ($recombine[0] % $recombine[2]);
                break;
        }
        return $retour_test;
    }

    private function error_afficher(bool $test, &$var, $varnom, string $message)
    {
        try {
            if ($test) {
                throw new Exception(
                    'Erreur => [ ' . $varnom . ':' . var_export($var, true) . ' ] ' . PHP_EOL .
                    $message . PHP_EOL
                );
            }
        } catch
        (Exception $e) {
            echo 'FATAL::' . $e->getMessage();
            exit;
        }
    }

    private function phpml_end($donnee)
    {
        return $donnee['contenu'] . '</body></html>';
    }
}