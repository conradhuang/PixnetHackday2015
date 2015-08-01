<?php

    require('system.inc.php');

    $mongo->kg->ensureIndex('id', array('unique' => true));
    $mongo->kg->ensureIndex('cat');

    foreach(file('dataset/taipei/animal.tsv') as $n => $line){
        if($n == 0) continue;
        $toks = explode("\t", rtrim($line));
        $name = $toks[0];
        $area = $toks[5];
        $diet = $toks[18];
        //echo $name."\t".$diet."\n";

        if(!empty($area)){
            $mid = 'tpe-animal-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'natural',
                        'q' => $name . '位於臺北市立動物園的哪一區？',
                        'a' => $area,
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($diet)){
            $mid = 'tpe-animal-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'natural',
                        'q' => $name . '喜歡吃什麼？',
                        'a' => $diet,
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }
    
    foreach(file('dataset/taipei/1040706臺北市各級學校分布圖%28含國小_國中_高中職_特教學校_市立大學%291030730 - 工作表1.tsv') as $n => $line){
        if($n == 0) continue;
        $toks = explode("\t", rtrim($line));
        $mid = 'tpe-school-q00-'.$n;
        if(preg_match('/^臺北市 (.*)/', $toks[1], $regs)){
            $school = $regs[1];
        }
        else $school = $toks[1];
        $mongo->kg->update(
                array(
                    'id' => $mid,
                    ),
                array(
                    'id' => $mid,
                    'cat' => 'other',
                    'q' => '臺北市 ' . $school . " 位於何處？",
                    'a' => $toks[3],
                    'ts_c' => time(),
                    'ts_v' => 0,
                ),
                array(
                    'upsert' => true
                )
            );
    }

    /////////////////////////////

    $json = json_decode(file_get_contents('dataset/taipei/apiIn (1).json'), true);

    foreach($json as $n => $park){
        if(!empty($park['Introduction'])){
            $mid = 'tpe-park-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '臺北市 ' . $park['ParkName'] . " 有何特色？",
                        'a' => $park['Introduction'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($park['Image'])) ? (null) : ($park['Image'])),
                        'location' => ((empty($park['Latitude'])) ? (null) : (array(
                                        'lat' => $park['Latitude'],
                                        'lon' => $park['Longitude'],
                                    ))),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($park['Location'])){
            $mid = 'tpe-park-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '臺北市 ' . $park['ParkName'] . " 位於何處？",
                        'a' => $park['Location'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($park['Image'])) ? (null) : ($park['Image'])),
                        'location' => ((empty($park['Latitude'])) ? (null) : (array(
                                        'lat' => $park['Latitude'],
                                        'lon' => $park['Longitude'],
                                    ))),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($park['YearBuilt'])){
            $mid = 'tpe-park-q02-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '臺北市 ' . $park['ParkName'] . " 建於何時？",
                        'a' => $park['YearBuilt'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($park['Image'])) ? (null) : ($park['Image'])),
                        'location' => ((empty($park['Latitude'])) ? (null) : (array(
                                        'lat' => $park['Latitude'],
                                        'lon' => $park['Longitude'],
                                    ))),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }

    /////////////////////////////

    $json = json_decode(file_get_contents('dataset/taipei/apiIn (2).json'), true);

    foreach($json as $n => $park){
        if(!empty($park['Introduction'])){
            $mid = 'tpe-parkSp-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '臺北市 ' . $park['ParkName'] . " 內的 " . $park['Name'] . " 有何特色？",
                        'a' => $park['Introduction'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($park['Image'])) ? (null) : ($park['Image'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($park['OpenTime'])){
            $mid = 'tpe-parkSp-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '臺北市 ' . $park['ParkName'] . " 內的 " . $park['Name'] . " 開放時間為何？",
                        'a' => $park['OpenTime'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($park['Image'])) ? (null) : ($park['Image'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }
    
    /////////////////////////////

    $arr = XML2Array::createArray(file_get_contents('dataset/taipei/freeWifi.xml'));

    foreach($arr['NewDataSet']['hotspot'] as $n => $wifi){
        if($wifi['AREA'] == $wifi['HOTSPOT_NAME']) {
            $name = $wifi['AREA'];
        }
        else {
            $name = $wifi['AREA'] . ' 的 ' . $wifi['HOTSPOT_NAME'];
        }
            $mid = 'tpe-wifi-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北市 ' . $name . " 附近有什麼 wifi 熱點？",
                        'a' => $wifi['ADDRESS'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'location' => ((empty($wifi['LNG'])) ? (null) : (array(
                                        'lat' => $wifi['LAT'],
                                        'lon' => $wifi['LNG'],
                                    ))),
                    ),
                    array(
                        'upsert' => true
                    )
                );
    }
    
    /////////////////////////////

    $arr = XML2Array::createArray(file_get_contents('dataset/taipei/parking1.xml'));

    foreach($arr['DATA']['PARK'] as $n => $parking){
        $name = $parking['NAME'];
        if(!preg_match('/停車場$/', $name)){
            $name .= '停車場';
        }
        if(!empty($parking['PAYEX'])) {
            $mid = 'tpe-parking-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北市 ' . $parking['AREA'] . ' ' . $name . " 收費方式為何？",
                        'a' => $parking['PAYEX'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($parking['TOTALCAR'])) {
            $mid = 'tpe-parking-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北市 ' . $parking['AREA'] . ' ' . $name . " 可停幾輛小客車？",
                        'a' => $parking['TOTALCAR'] . ' 輛',
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($parking['TOTALMOTOR'])) {
            $mid = 'tpe-parking-q02-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北市 ' . $parking['AREA'] . ' ' . $name . " 可停幾輛機車？",
                        'a' => $parking['TOTALMOTOR'] . ' 輛',
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($parking['TOTALBIKE'])) {
            $mid = 'tpe-parking-q03-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北市 ' . $parking['AREA'] . ' ' . $name . " 可停幾輛腳踏車？",
                        'a' => $parking['TOTALBIKE'] . ' 輛',
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }
    
    /////////////////////////////

    $arr = XML2Array::createArray(file_get_contents('dataset/taipei/travellocationCHINESE.xml'));

    foreach($arr['CommonFormat']['Section'] as $n => $spot){
        if(!empty($spot['xbody'])) {
            $mid = 'tpe-spot-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '位於' .$spot['address']. ' 的 ' . $spot['stitle'] . " 有何特色？",
                        'a' => $spot['xbody'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($spot['file']['img'][0]['@value'])) ? (null) : ($spot['file']['img'][0]['@value'])),
                        'location' => ((empty($spot['latitude'])) ? (null) : (array(
                                        'lat' => $spot['latitude'],
                                        'lon' => $spot['longitude'],
                                    ))),
                        'url' => ((empty($spot['xurl'])) ? (null) : ($spot['xurl'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($spot['info'])) {
            $mid = 'tpe-spot-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '位於' .$spot['address']. ' 的 ' . $spot['stitle'] . " 該如何前往？",
                        'a' => $spot['info'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($spot['file']['img'][0]['@value'])) ? (null) : ($spot['file']['img'][0]['@value'])),
                        'location' => ((empty($spot['latitude'])) ? (null) : (array(
                                        'lat' => $spot['latitude'],
                                        'lon' => $spot['longitude'],
                                    ))),
                        'url' => ((empty($spot['xurl'])) ? (null) : ($spot['xurl'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($spot['MEMO_TIME'])) {
            $mid = 'tpe-spot-q02-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '位於' .$spot['address']. ' 的 ' . $spot['stitle'] . " 開放時間為何？",
                        'a' => $spot['MEMO_TIME'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($spot['file']['img'][0]['@value'])) ? (null) : ($spot['file']['img'][0]['@value'])),
                        'location' => ((empty($spot['latitude'])) ? (null) : (array(
                                        'lat' => $spot['latitude'],
                                        'lon' => $spot['longitude'],
                                    ))),
                        'url' => ((empty($spot['xurl'])) ? (null) : ($spot['xurl'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }

    /////////////////////////////

    $arr = XML2Array::createArray(file_get_contents('dataset/taipei/travelStayCHINESE.xml'));

    foreach($arr['CommonFormat']['Section'] as $n => $spot){
        if((!empty($spot['xbody'])) && (!empty($spot['address']))) {
            $mid = 'tpe-hotel-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '位於' .$spot['address']. ' 的 ' . $spot['stitle'] . " 有何特色？",
                        'a' => $spot['xbody'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($spot['file']['img'][0]['@value'])) ? (null) : ($spot['file']['img'][0]['@value'])),
                        'location' => ((empty($spot['latitude'])) ? (null) : (array(
                                        'lat' => $spot['latitude'],
                                        'lon' => $spot['longitude'],
                                    ))),
                        'url' => ((empty($spot['xurl'])) ? (null) : ($spot['xurl'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
        if(!empty($spot['MEMO_COST'])) {
            $mid = 'tpe-hotel-q01-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'travel',
                        'q' => '位於' .$spot['address']. ' 的 ' . $spot['stitle'] . " 價位為何？",
                        'a' => $spot['MEMO_COST'],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'img' => ((empty($spot['file']['img'][0]['@value'])) ? (null) : ($spot['file']['img'][0]['@value'])),
                        'location' => ((empty($spot['latitude'])) ? (null) : (array(
                                        'lat' => $spot['latitude'],
                                        'lon' => $spot['longitude'],
                                    ))),
                        'url' => ((empty($spot['xurl'])) ? (null) : ($spot['xurl'])),
                    ),
                    array(
                        'upsert' => true
                    )
                );
        }
    }
    
    /////////////////////////////

    $arr = file('dataset/taipei/臺北市104年度颱風期間開放停車學校概況一覽表.txt');

    $schools = array();
    $n = 0;

    foreach($arr as $line){
        $tline = trim($line);
        if(preg_match('/區$/', $tline)){
            $area = $tline;
        }
        else if(preg_match('/^\d/', $tline)){
            $schools[] = $tline;
        }
        else if(empty($tline)){
            $mid = 'tpe-typhoon-q00-'.($n++);
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'other',
                        'q' => '臺北市 ' . $area . ' 104年度颱風期間開放停車學校 有哪些？',
                        'a' => implode(', ', $schools),
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
            $schools = array();
        }
    }
        
    /////////////////////////////

    foreach(file('dataset/taipei/臺北市公私立醫療院所_10406 - 工作表1.tsv') as $n => $line){
        if($n == 0) continue;
        $toks = explode("\t", rtrim($line));
            $mid = 'tpe-hospital-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'other',
                        'q' => '臺北市 ' . $toks[0] . ' 位於何處？',
                        'a' => $toks[1],
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
    }

    /////////////////////////////

    foreach(file('dataset/taipei/臺北市垃圾資源回收、廚餘回收限時收受點 - 工作表1.tsv') as $n => $line){
        if($n == 0) continue;
        $toks = explode("\t", rtrim($line));
            $mid = 'tpe-garbage-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'lifestyle',
                        'q' => '臺北市清潔隊 ' . $toks[0]. $toks[1] . ' 垃圾資源回收、廚餘回收限時收受點在何處？',
                        'a' => $toks[3],
                        'ts_c' => time(),
                        'ts_v' => 0,
                        'location' => ((empty($toks[4])) ? (null) : (array(
                                        'lat' => $toks[5],
                                        'lon' => $toks[4],
                                    ))),
                    ),
                    array(
                        'upsert' => true
                    )
                );
    }

    /////////////////////////////

    foreach(file('dataset/taipei/臺北捷運系統票價資料%281040703%29 - 公開資料.tsv') as $n => $line){
        if($n == 0) continue;
        $toks = explode("\t", rtrim($line));
            $mid = 'tpe-mrt-q00-'.$n;
            $mongo->kg->update(
                    array(
                        'id' => $mid,
                        ),
                    array(
                        'id' => $mid,
                        'cat' => 'trans',
                        'q' => '臺北捷運 '.$toks[0].'站 搭乘到 '.$toks[1].'站 票價多少錢？',
                        'a' => '單程票：'.$toks[2].'元，悠遊卡全票：'.$toks[3].'元，悠遊卡敬老票：'.$toks[4].'元',
                        'ts_c' => time(),
                        'ts_v' => 0,
                    ),
                    array(
                        'upsert' => true
                    )
                );
    }
