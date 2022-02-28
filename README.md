#
![logoeukaruon-128_2](https://user-images.githubusercontent.com/9467611/152698385-2b5f557c-7ec0-4e2b-8325-5b7197a694cb.png)

Eukaruon est un Noyaux de départ pour créé son propre CMS.
à l'heure actuel c'est un prototype il est coder 100% en PHP8 et se détache totalement 
des version en dessous de PHP8 ce qui lui donne un avantage majeur en terme de rapidité
et de fluidité.


Le noyaux dispose d'un gestionnaire d'administration minimal détacher et non intrusif qui peut être
retirer du projet sans probléme


Gestion Administrative >> http://localhost/Eukaruon/administration/index.php

Page Utilisateur Web >> http://localhost/Eukaruon/index.php

// fini de commenté chaque fonction 

// créé un wiki

// Gestion du format Level 7

Exemple :

![image](https://user-images.githubusercontent.com/9467611/136052482-6a6b2ac4-190b-4c44-92f1-2302d95eadf7.png)

Deux chargement différent d'un module
![image](https://user-images.githubusercontent.com/9467611/136200909-0873663f-9936-47d9-ad3c-9f38e2531c28.png)

$Modules_gestionnaire = $pilote->Utiliser_le_gestionnaire();

$submodules_test = $Modules_gestionnaire->charger_le_modules('sousmodules_test');


