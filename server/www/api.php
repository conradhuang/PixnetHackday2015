<?php

    require('../system.inc.php');

    $ts = time();

    $response = array(
                'st' => 200,
                'msg' => '',
                'data' => false,
                'meta' => array(
                        'ts' => $ts,
                        'tspent' => microtime(true),
                        'host' => gethostname(),
                    ),
            );
        
    $action = empty($_GET['a']) ? false : $_GET['a']; 

    function getAkg($query){
        global $mongo;
        $total = $mongo->kg->count($query);
        return $mongo->kg->find($query)->limit(-1)->skip(rand(0,$total - 1))->next();
    }

    function logAkg($id){
        global $mongo;
        global $_SERVER;
        global $ts;
        $item = $mongo->kg->findOne(array('id' => $id));
        if(!$item){
            return false;
        }
        $log = array(
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'ts' => $ts,
                );
        $mongo->log->update(
                    array(
                        'id' => $id,
                        ),
                    array(
                        '$set' => array('cat' => $item['cat'], 'ts_last' => $ts),
                        '$push' => array('view' => $log),
                        '$inc' => array('count' => 1),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        return true;
    }

    if($action === 'getkg'){
        $response['data'] = array();
        $dict = array();
        $n = empty($_GET['n']) ? '' : $_GET['n'];
        if(preg_match('/^\d+$/', $n)){
            $query = array();
            if(!empty($_GET['cat'])){
                if(isset($categories[$_GET['cat']])){
                    $query = array('cat' => $_GET['cat']);     
                }
                else{
                    $response['warning'] = 'invalid category';
                }
            }
            $totalkgn = $mongo->kg->count($query);
            if($totalkgn){
                $n = min($n, $totalkgn, 100);
                while($n > 0){
                    $kg = getAkg($query);
                    if(!isset($dict[$kg['id']])){
                        $n--;
                        $response['data'][] = $kg;
                    }
                }
            }
            else{
                $response['st'] = 404;
            }
        }
        else {
            $response['st'] = 400;
            $response['msg'] = 'missing parameter n';
        }
    }
    else if($action === 'logkg'){
        if(empty($_GET['id'])){
            $response['st'] = 400;
        }
        else if(!logAkg($_GET['id'])){
            $response['st'] = 404;
        }
    }
    else if($action === 'stat'){
        $mongo->log->ensureIndex('ts_last');
        $mongo->log->ensureIndex('cat');
        $query = array();
        if(!empty($_GET['cat'])){
            if(isset($categories[$_GET['cat']])){
                $query = array('cat' => $_GET['cat']);     
            }
            else{
                $response['warning'] = 'invalid category';
            }
        }
        $query = array_merge($query, array('ts_last' => array('$gt' => $ts - 86400 * 7)));
        $response['debug'] = $query;
        $cursor = $mongo->log->find($query)->sort( array( 'count' => -1 ) )->limit(10);
        $response['data'] = array();
        foreach ($cursor as $doc) {
            $item = array(
                        'id' => $doc['id'],
                        'count' => $doc['count'],
                        'ts_last' => $doc['ts_last'],
                        'qa' => $mongo->kg->findOne(array('id' => $doc['id'])),
                    );
            $response['data'][] = $item;
        }
    }
    else if($action === 'getCats'){
        $response['data'] = $categories;
    }
    else {
        $response['st'] = 400;
    }

    $response['meta']['tspent'] = microtime(true) - $response['meta']['tspent'];

    if(empty($response['msg'])){
        $code2msg = array(
                    '200' => 'ok',
                    '400' => 'bad request',
                    '404' => 'not found',
                );
        if(isset($code2msg[$response['st']])){
            $response['msg'] = $code2msg[$response['st']];
        }
    }

    if(!empty($_GET['phpdebug'])) print_r($response);
    else echo json_encode($response);

