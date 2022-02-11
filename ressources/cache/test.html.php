<!doctype html PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head></head>
<body>test de html
<form><label>login</label><input id='login'><br></br><label>password</label><input
            id='password'><?php

    use Eukaruon\configs\CMD;
    use Eukaruon\pilote;

    echo "--> test"; ?><?php echo "--> test"; ?><?php echo "--> test"; ?><?php echo 'test2'; ?><?php echo 'test3'; ?><?php echo 'test2'; ?><?php echo 'test3'; ?><?php echo 'test2'; ?><?php echo 'test3'; ?><?php echo 'test2'; ?><?php echo 'test3'; ?><?php $foobar = array(0 => 'int:1', 1 => 'bool:true', 2 => 'str:3',);
    $barfoo = 'testdephp4';
    echo 'test'; ?>text 1test de html

    <?php

    $pilote = new pilote();

    $Modules_pages = $pilote->Charger_le_module(
        module_a_charger: 'Modules_pages',
        modules_primaire: [CMD::MODULES_BDD]
    );

    var_dump($Modules_pages->recuperation_en_stockage());

    ?>


    <div>test de div1</div>
    <div>test de div2</div>
    <div>test de div3</div>
    <div>test de div4</div>
    <div>test de div1</div>
    <div>test de div2</div>
    <div>test de div3</div>
    <div>test de div4</div>
</form>
</body>
</html>