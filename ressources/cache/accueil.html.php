<?php error_reporting(0); header("Content-type: text/html; charset=utf-8"); ?>
    [TEST::TEST]
    <class id="2">
        <div>test de div</div>
        <div>test de div2</div>
        <label>test de div3
            <input type="texte" value="test"></input>
        </label>
    </class>
    <if id="3" user="2">
        <done>
            <div>test 1</div>
            <div>test 2</div>
            <div>test 3</div>
        </done>
        <elseif user="3">
            <div>test 4</div>
            <div>test 5</div>
            <div>test 6</div>
        </elseif>
        <else>
            <div>test 7</div>
            <div>test 8</div>
            <div>test 9</div>
        </else>
    </if>
