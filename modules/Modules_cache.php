<?php namespace Eukaruon\modules;

use Exception;


class Modules_cache extends Modules_outils
{

    /**
     * @var mixed|null
     */
    private mixed $_rappel_fonction = null;
    /**
     * @var int
     */
    private int $_temp_du_cache = 60;
    /**
     * @var string|bool|object|null
     */
    private string|bool|null|object $_data_exploite = null;

    /* utilisation :
     * $Modules_cache->cache( 'test', 10, 120, 'userID001124/')
     * -> va créé un fichier 'test' dans le dossier 'temp/userID001124/'
     *
     * $Modules_cache->cache( 'test:userID001124')
     * -> va recherche le fichier test dans 'temp/userID001124/'
     *
     * $Modules_cache->cache( 'test', 10, 120)
     * -> va créé un fichier 'test' dans 'temp/'
     *
     * $Modules_cache->cache( 'test')
     * -> recherche le fichier 'test' dans 'temp/'
     *
     * $Modules_cache->cache('test:user1023456', <<<CODE
     *  <?php class test {
     *  function run(){
     *  return 'teste_de_class';
     *  }}
     *  CODE);
     */

    /**
     * @param string $nom_donnee
     * @param mixed|null $donnee
     * @param int|null $temp_cache_seconds
     * @param string $dossier
     * @return bool|object|string|null
     */
    public function cache(string $nom_donnee, mixed $donnee = null, int|null $temp_cache_seconds = null, string $dossier = '')
    {
        /* 1 on vérifie si le fichier existe et que le temp de cache est inférieur au temps qu'on lui a
         * imposé ou par défault soit 1 minutes
         */


        if (strlen($dossier) == 0 && str_contains($nom_donnee, ':')) {
            list($nom_donnee, $dossier) = explode(':', $nom_donnee);
            $dossier .= '/';
        }


        $fichier = TEMP . $dossier . $nom_donnee . '.data';
        $existe = file_exists($fichier);

        if ($existe) {

            $temp_nondepasser = ((time() - filemtime($fichier)) < $this->_temp_du_cache);

            if ($temp_nondepasser) {

                return $donnee === null ?
                    // on va lire la donnée
                    $this->lecture_cache($fichier) :
                    // sinon on met à jours la donnée
                    $this->ecrire_cache($fichier, $donnee, $temp_cache_seconds);
            }

        }

        return $this->ecrire_cache($fichier, $donnee, $temp_cache_seconds);

    }

    /**
     * @param string $fichier
     * @param bool $force
     * @return bool|object|string|null
     */
    public function lecture_cache(string $fichier, bool $force = false)
    {
        if ($this->_data_exploite === null || $force) {
            $this->_data_exploite = file_get_contents($fichier);
        }
        return $this->_data_exploite;
    }

    /**
     * @param string $fichier
     * @param mixed|null $donnee
     * @param int|null $temp_cache_seconds
     * @return mixed
     */
    private function ecrire_cache(string $fichier, mixed $donnee = null, int|null $temp_cache_seconds = null)
    {
        if (!file_exists(dirname($fichier, 1))) {
            @mkdir(dirname($fichier, 1), 0777, true);
        }


        if (str_starts_with($donnee, '<?php')) {
            file_put_contents($fichier . '.class.php', $donnee);
            $donnee = $this->maclassexploiter($fichier);
        } elseif (file_exists($fichier . '.class.php')) {
            $donnee = $this->maclassexploiter($fichier);
        }


        /*
        elseif ($donnee !== null) {
            echo '$donnee !== null' , PHP_EOL;
            if (is_object($donnee) || is_callable($donnee)) {
                echo '$donnee !== null => CLOSURE' , PHP_EOL;
                $this->_rappel_fonction = $donnee;
                @mkdir( dirname($fichier,1), 0777, true);
                file_put_contents($fichier . '.callback', serialize([$this->_temp_du_cache, $this->closure_to_str($donnee)]));
                $donnee = $donnee();
            }
        } else {
            if (file_exists($fichier . '.callback')) {
                echo 'Fichier CLOSURE existe' , PHP_EOL;
                list($this->_temp_du_cache, $this->_rappel_fonction) = unserialize(file_get_contents($fichier . '.callback'));
                eval('$zeval =' . $this->_rappel_fonction . ';');
                $donnee = $zeval();
            }
        }*/

        if ($temp_cache_seconds !== null) $this->_temp_du_cache = $temp_cache_seconds;

        return $this->ecriture_cache($fichier, $donnee);

    }

    /*
     * deux méthodes d'utilisation supprimer_cache(nom:dossier) ou supprimer_cache(nom,dossier)
     * cela offre une compatibilité avec la fonction principal
     */

    /**
     * @param $fichier
     * @return mixed|void
     */
    private function maclassexploiter($fichier)
    {
        try {

            include_once $fichier . '.class.php';
            $classname = basename($fichier, '.data');
            $classinst = new $classname();
            $methode_list = get_class_methods($classinst);

            if (!in_array('run', $methode_list)) {
                throw new Exception(
                    'Erreur => [ ' . $fichier . ' ]  ne contient pas de fonction run() !' . PHP_EOL
                );

            } else {
                return $classinst->run();
            }

        } catch
        (Exception $e) {
            echo 'FATAL::' . $e->getMessage();
            exit;
        }

    }

    /**
     * @param $fichier
     * @param $donnee
     * @return mixed
     */
    public function ecriture_cache($fichier, $donnee)
    {
        $this->_data_exploite = $donnee;
        //echo "<<ecriture>>" . $fichier . '|';
        file_put_contents($fichier, $this->_data_exploite);
        return $donnee;
    }

    /**
     * @param string $nom_donnee
     * @param string $dossier
     */
    public function supprimer_cache(string $nom_donnee, string $dossier = '')
    {
        if (strlen($dossier) == 0 && str_contains($nom_donnee, ':')) {
            list($nom_donnee, $dossier) = explode(':', $nom_donnee);
            $dossier .= '/';
        }
        @unlink(TEMP . $dossier . $nom_donnee . '.data');
        @unlink(TEMP . $dossier . $nom_donnee . '.data.class.php');
        if (TEMP . $dossier !== TEMP) {
            @rmdir(TEMP . $dossier);
        }
    }


}

