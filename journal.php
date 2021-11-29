<?php namespace Eukaruon;

/** A pour objectif de loguer les actions dans des fichier texte
 * de log
 */
class journal
{

    /**
     *
     */
    public function __construct()
    {

    }

    /** Log les actions spécifique dans le journal
     * @param $information
     */
    public function journal($information)
    {
        $date = date("Y-m-d H:i:s");
        $fp = fopen(JOURNAUX . 'journal.txt', 'a+');
        fwrite($fp, $date . ' : ' . $information . PHP_EOL);
        fclose($fp);
    }

    /** loge les erreurs dans le journal d'erreur
     * @param $erreur
     */
    public function journal_erreurs($erreur)
    {
        $date = date("Y-m-d H:i:s");
        $fp = fopen(JOURNAUX . 'erreur.txt', 'a+');
        fwrite($fp, $date . ' : ' . $erreur . PHP_EOL);
        fclose($fp);
    }
}