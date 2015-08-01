<?php

    require('../system.inc.php');

    $cursor = $mongo->kg->find()->sort( array( 'manual' => -1 ) )->limit(max($_GET['n'],1000));
    $data = array();
    foreach ($cursor as $doc) {
        $data[] = $doc['q'];
    }
    $data = array_reverse($data);

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>歐噴豆。知識</title>
    <style>
    body {
        background: url(images/background.png)
    }
.qq {
    position: absolute;
    font-size: 30px;
    background-color: #fff;
    border: thin solid;
    font-family: arial, sans-serif;        
}
    </style>
</head>
<body>
<?php
    foreach($data as $q){
        $top = rand(0,600)-10;
        $left = rand(0,600)-10;
        echo '<span class="qq" style="top:'.$top.'px;left:'.$left.'px">'.$q.'</span>';
    }
?>
</body>
</html>
