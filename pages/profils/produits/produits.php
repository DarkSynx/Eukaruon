<?php

use Eukaruon\modules\Modules_formulaire as FORMULAIRE;
use Eukaruon\modules\Modules_formulaire_input as INPUT;
use Eukaruon\modules\Modules_formulaire_select as SELECT;
use Eukaruon\modules\Modules_formulaire_textarea as TEXTAREA;

class produits
{
    /**
     * @throws Exception
     */
    public function article()
    {
        $_GET['u'] = '0123456789';

        FORMULAIRE::defini(SITE_WEB . 'formulaires.php?f=formulaire_test&r=1&u=0123456789')
            ->elements(

                TEXTAREA::defini(
                    id: 'id_input_2',
                    class: 'classtest i121 l3 o1 ',
                    name: 'nametest1',
                    filtre: 'FILTER_SANITIZE_ADD_SLASHES|FILTER_SANITIZE_ENCODED',
                    encaps_b64: true
                )
                    ->finaliser()
                ,

                INPUT::defini(
                    type: 'email',
                    id: 'idtest',
                    class: 'classtest i120 l2 o18 ',
                    name: 'nametest'
                )
                    ->argument('maxlength', 10)
                    ->argument('minlength', 2, maxmin_test: true)
                    ->argument('size', 10)
                    ->exception('step', 10)
                    ->exception('data-tableau', '1,2,3')
                    ->finaliser()
                ,
                INPUT::defini(
                    type: 'file',
                    option: '[
                             ./upload,
                             2000000,
                             "jpg" => "image/jpeg",
                             "png" => "image/png",
                             "gif" => "image/gif"
                             ]',
                    id: 'id_input_2',
                    class: 'classtest i121 l3 o1 ',
                    name: 'nametest2'
                )
                    ->finaliser()

                ,
                SELECT::defini(
                    id: 'id_input_2',
                    class: 'classtest i121 l3 o1 ',
                    name: 'nametest3',
                    autofocus: true,
                    multiple: true,
                    required: true,
                )
                    ->option('chien', 'dog')
                    ->option('chat', 'cat')
                    ->finaliser()

                ,
                INPUT::defini(type: 'submit')->finaliser()
            )
            ->generer(
                chemin_fichier_php: FORMULAIRES . 'formulaire_test.php',
                fichier_post_traitement: 'index.php',
                identifiant_utilitsation: '0123456789',
                instancier: true,
                debug: false,
                exploiter: true,
                un_fichier_unique: true
            );

        //
        // include_once FORMULAIRES . 'formulaire_test.php';

        // $_GET['u'] = '0123456789';
        // $_GET['f'] = 'formulaire_test';
        // include RACINE . 'formulaires.php';

        /*        var_dump(
                   INPUT::defini(
                       type: 'file',
                       option: '[
                             upload,
                             "jpg" => "image/jpeg",
                             "png" => "image/png",
                             "gif" => "image/gif"
                             ]',
                       id: 'id_input_2',
                       class: 'classtest i121 l3 o1 ',
                    name: 'nametest2'
                   )
                       ->finaliser());

               /*
                      echo '<code>',
                          str_replace("\r\n",'<br>',
                          FORMULAIRE::defini('formulaire_test')
                              ->elements(

                              )
                              ->generer(])
                          , '</code>'
                      ;


                      /*


                                  INPUT::defini(
                                      type: 'file',
                                      option: '[
                                          upload,
                                          "jpg" => "image/jpeg",
                                          "png" => "image/png",
                                          "gif" => "image/gif"
                                      ]',
                                      id: 'id_input_2',
                                      class: 'classtest i121 l3 o1 ',
                                      name: 'nametest2'
                                  )
                                      ->finaliser()
                                  ,
                                  INPUT::defini(
                                      type:'email',
                                      id: 'idtest',
                                      class: 'classtest i120 l2 o18 ',
                                      name: 'nametest'
                                  )
                                      ->argument('maxlength', 10)
                                      ->argument('minlength', 2)
                                      ->argument('size', 10)
                                      ->exception('step', 10)
                                      ->exception('data-tableau', '1,2,3')
                                      ->finaliser()


                      var_dump(
                                  INPUT::type('email')
                                      ->identifiant(
                                          id: 'idtest',
                                          class: 'classtest i120 l2 o18 ',
                                          name: 'nametest')
                                      ->argument('maxlength', 10)
                                      ->argument('minlength', 2)
                                      ->argument('size', 10)
                                      ->exception('step', 10)
                                      ->exception('data-tableau', '1,2,3')
                                      ->finaliser()
                              );
                      */

        return <<<'EOD'
            test de produit
            <?php
            echo 'xxxxxxx';
            ?>";
        EOD;
    }
}