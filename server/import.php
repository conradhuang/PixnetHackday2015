<?php

    require('system.inc.php');

    $mongo->kg->ensureIndex('id', array('unique' => true));
    $mongo->kg->ensureIndex('cat');
    $mongo->kg->ensureIndex('manual');

    foreach(file($argv[1]) as $n => $line){
        $toks = explode("\t", rtrim($line));
        if(count($toks) < 3) continue;
        $cat = trim($toks[0]);
        $q = trim($toks[1]);
        $a = trim($toks[2]);
        if(!isset($categories[$cat])) {
            echo 'Invalid category "'.$cat.'", skip.'."\n";
            continue;
        }

        if( (!empty($q)) && (!empty($a)) ){
            $mid = 'manual-01-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => $cat,
                        'q' => $q,
                        'a' => $a,
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'manual' => true,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }
