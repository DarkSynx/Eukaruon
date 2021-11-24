<?php

class administration_outils
{


    public function tester_la_validiter_de_la_demande(): bool
    {
        return ((
                array_key_exists('key', $_COOKIE) &&
                array_key_exists('key', $_SESSION) &&
                array_key_exists('time', $_SESSION) &&
                array_key_exists('error', $_SESSION)
            ) && (
                strlen($_COOKIE['key']) == 128 &&
                strlen($_SESSION['key']) == 128
            )
            &&
            ($_SESSION['error'] < 10)
            &&
            (time() <= $_SESSION['time'])
            &&
            ($_SESSION['key'] == $_COOKIE['key']));
    }
}