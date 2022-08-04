<?php

namespace Eukaruon\modules;

use finfo;
use RuntimeException;

/**
 * module de test vide
 */
class Modules_test
{


    /**
     *
     */
    public function test()
    {


        header('Content-Type: text/plain; charset=utf-8');

        try {


            // Non défini | Fichiers multiples | $_FILES Attaque de corruption
            // Si cette requête relève de l'une d'entre elles, traitez-la comme invalide.
            if (
                !isset($_FILES['upfile']['error']) ||
                is_array($_FILES['upfile']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Vérifie la valeur de $_FILES['upfile']['error'].
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // Vous devriez également vérifier la taille du fichier ici.
            if ($_FILES['upfile']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // NE PAS FAIRE CONFIANCE À LA VALEUR $_FILES['upfile']['mime'] !!
            // Vérifiez le type MIME par vous-même.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === ($ext = array_search(
                    $finfo->file($_FILES['upfile']['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                ))
            ) {
                throw new RuntimeException('Invalid file format.');
            }

            // Vous devez lui donner un nom unique.
            // NE PAS UTILISER $_FILES['upfile']['name'] SANS AUCUNE VALIDATION !!
            // Dans cet exemple, obtenir un nom unique sûr à partir de ses données binaires.
            if (!move_uploaded_file(
                $_FILES['upfile']['tmp_name'],
                sprintf('./uploads/%s.%s',
                    sha1_file($_FILES['upfile']['tmp_name']),
                    $ext
                )
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            echo 'File is uploaded successfully.';

        } catch (RuntimeException $e) {

            echo $e->getMessage();

        }


    }
}


