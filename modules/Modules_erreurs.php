<?php namespace Eukaruon\modules;

class Modules_erreurs
{
    public function __construct()
    {
        set_error_handler(array($this, 'gestion_derreurs'));
    }

    public function gestion_derreurs(int $errno, string $errstr = '', string $errfile = '', int $errline = -1, array $errcontext = [])
    {
        var_dump(ARCHIVES);
        $error_line = "$errno | $errstr| $errfile| $errline| " . implode(";", $errcontext);
        error_log("$error_line\r\n", 3, LOGS . 'erreur_' . date("Ymd") . '.log');
    }
}