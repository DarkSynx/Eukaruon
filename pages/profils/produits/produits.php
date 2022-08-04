<?php

use Eukaruon\modules\Modules_formulaire_textarea as TEXTAREA;

class produits
{
    /**
     * @throws Exception
     */
    public function article()
    {

        var_dump(

            TEXTAREA::defini(
                id: 'id_input_2',
                class: 'classtest i121 l3 o1 ',
                name: 'nametest2',
                filtre: 'FILTER_SANITIZE_ADD_SLASHES|FILTER_SANITIZE_ENCODED',
                encaps_b64: true
            )
                ->contenu('texte1 bla bla bla bla1')
                ->contenu('texte2 bla bla bla bla2')
                ->finaliser()

        );


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