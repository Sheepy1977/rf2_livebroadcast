<?php
error_reporting(E_ERROR);

$sessionID = 2;
$ChineseNameFile = file("Chinesename.txt");
foreach ($ChineseNameFile as $singleLine)
{
    $re = explode (",",$singleLine);
    $playerName = strtolower(trim($re[0]));
    $cName = trim($re[1]);
    $shortID = trim($re[2]);
    $weight = trim($re[3]);
    $ChineseNameArray[$playerName] =  $cName;
    $shortIdArray[$playerName] = $shortID;
    $weightArray[$playerName] = $weight;// 2020.5.20
}

$db = mysql_connect("127.0.0.1:8888", "root","root");
mysql_select_db("broadcast", $db);
date_default_timezone_set('PRC');
$sql="select * from srfc_grid";
$ok=mysql_query($sql);
while ($row=mysql_fetch_array($ok)){
    $playerName = strtolower(trim($row[1]));
    $grid[$playerName] = $row[0];
}

//$sql="select * from vehicle_score where SessionID=$sessionID ";
//$ok=mysql_query($sql);



$content = file_get_contents("http://127.0.0.1:34297/getScoringInfo");
$content = eregi_replace("'","-", $content); 
$t = json_decode($content,true);

$env = array();
$env['mDarkCloud'] = $t['mDarkCloud'];
$env['mRaining'] = number_format($t['mRaining'],2);
$env['mAmbientTemp'] = number_format($t['mAmbientTemp'],2);
$env['mTrackTemp'] =  number_format($t['mTrackTemp'],2);
$env['mAvgPathWetness'] = number_format($t['mAvgPathWetness'],2)*100;
$env['mCurrentET'] = $t['mCurrentET'];
$env['mEndET'] = $t['mEndET'];
$env['mSession'] = $t['mSession'];
$env['mLapDist'] = $t['mLapDist'];
$env['mBestLapTimeRow'] = 9999;

$currentET = $t['mCurrentET'];
$count=0;
$array = $t['mVehicles'];
$testArr = array("测试者","测试");
foreach ($array as $row){
    $players = array();
    $place = $row['mPlace']; 
    //if ($row['mDriverName'] == 'yellow_stand_ze') $row['mDriverName'] = "yellow";
    $players['mDriverName'] = trim($row['mDriverName']);
    $ttt = strtolower(trim($row['mDriverName']));
    if (! isset ($ChineseNameArray[$ttt])) $cn = ''; else $cn = $ChineseNameArray[$ttt];
    
    $weight = $weightArray[$ttt];
    if ($weight == null) $weight =  "";
    $players['weight'] = $weight;
    
    //$cn = $testArr[rand(0,1)];
    if (strlen($cn) <=6) $cn = substr($cn,0,3)."&nbsp;&nbsp;&nbsp;&nbsp;".substr($cn,3);
    $players['ChineseName'] = $cn ;
    
    if (!$shortIdArray[$ttt]){
        $players['shortID'] = strtoupper(substr($players['mDriverName'],0,3));
    }else{
        $players['shortID'] = $shortIdArray[$ttt];
    }
    $players['mTotalLaps'] = $row['mTotalLaps'];
    $players['mPlace'] = $row['mPlace'];
    $mTimeBehindLeader = number_format($row['mTimeBehindLeader'],3);
    if ($mTimeBehindLeader > 60) $mTimeBehindLeader = SecToString($mTimeBehindLeader);
    $mTimeBehindNext = number_format($row['mTimeBehindNext'],3);
    if ($mTimeBehindNext > 60) $mTimeBehindNext = SecToString($mTimeBehindNext);
    
    $temp = $row['mVehicleClass'];
    //$temp = str_replace(" ", "_", $temp);
    //if (strpos($temp,"Mclaren")) $temp = "Mclaren 650S GT3";
    $players['mVehicleClass'] 				= $temp;
    $players['mVehicleName']                = $row['mVehicleName'];
    $players['mTimeBehindLeader'] 			= $mTimeBehindLeader;
    $players['mTimeBehindNext'] 			= $mTimeBehindNext;
    $players['mLapsBehindLeader'] 			= $row['mLapsBehindLeader'];
    $players['mBestLapTime'] 				= $row['mBestLapTime'] > 0 ? SecToString($row['mBestLapTime']):"";
    $players['mBestLapTimeRow'] 			= $row['mBestLapTime'];
    $players['mLastLapTime'] 				= $row['mLastLapTime'] > 0 ? SecToString($row['mLastLapTime']):"";
    $players['mLastLapTimeRow'] 			= $row['mLastLapTime'];
    $players['recordID'] 					= $row['recordID'];
    $players['mInPits'] 					= $row['mInPits'];
    $players['mFinishStatus'] 				= $mFinishStatus = $row['mFinishStatus']; // 0=none, 1=finished, 2=dnf, 3=dq
    $players['mNumPitstops']				= $row['mNumPitstops'];
    $players['mNumPenalties'] 				= $row['mNumPenalties'];
    $players['mSector'] 					= $row['mSector'];
    $players['mCurSector1Row'] 				= $row['mCurSector1'];
    $players['mCurSector2Row'] 				= $row['mCurSector2'];
    $players['mCurSector1'] 				= $row['mCurSector1'] > 0 ? SecToString($row['mCurSector1']):"";
    $players['mCurSector2'] 				= $row['mCurSector2'] > 0 ? SecToString($row['mCurSector2']):"";
    $players['mLapStartET'] 				= $row['mLapStartET'];

    if ($env['mBestLapTimeRow'] > $row['mBestLapTime'] && $row['mBestLapTime'] != -1  && $row['mTotalLaps'] > 2){
        $env['mBestLapTimeRow'] = $row['mBestLapTime'];
        $env['mBestLapTime'] = $players['mBestLapTime'];
        $env['mBestLapSector1'] = $row['mBestLapSector1'] > 0 ? SecToString($row['mBestLapSector1']):"";
        $env['mBestLapSector2'] = $row['mBestLapSector2'] > 0 ? SecToString($row['mBestLapSector2']):"";
        $env['mBestLapSector1Row'] = $row['mBestLapSector1'];
        $env['mBestLapSector2Row'] = $row['mBestLapSector2'];
    }

    $players['mLapDist'] = $mLapDist = $row['mLapDist'];
    $mid = $row['mID'];
    if ($players['mDriverName'] != ""){
        $old_name = getsql("select name from en_player_in_row where mid=$mid and name = '".$players['mDriverName']."'");
        if (!$old_name){
            $sql = "insert into en_player_in_row (mid,name) values ($mid,'".$players['mDriverName']."')";
            mysql_query($sql);
        }
    }

    $sql = "delete from timeBehindNext_table where mid = $mid";
    mysql_query($sql);
    $timenow = time();
    $sql = "insert into timeBehindNext_table (player,mid,mTimeBehindNext,timestamp,mPlace,mInPits,mLapsBehindLeader,mLapDist,mFinishStatus) values ('".$players['mDriverName']."',$mid,$mTimeBehindNext,$timenow,$place,".$players['mInPits'].",".$players['mLapsBehindLeader'].",$mLapDist,$mFinishStatus)";//
    mysql_query($sql);


    $sql = "insert into pitStatus(mid,mInPits,mFinishStatus) values($mid ,0,$mFinishStatus)";
    mysql_query($sql);//第一次插入0的停站状态。

    $ttt = $players['mDriverName'];
    $sql="select ps from srfc_grid where plyname='$mid'";
    $grid=getsql($sql);
    $players['mGrid'] = $grid;
    $players['mID'] = $mid;

    $sql = "select * from en_player_in_row where mid=$mid order by id";
    $ok = mysql_query($sql);
    $name_in_row = "";
    $p = 0;
    if (mysql_num_rows($ok) > 1){
        while ($row = mysql_fetch_array($ok)){
            $name = $row['name'];
            if ($name != $players['mDriverName']) $name_in_row = $name_in_row." ".$name;
            $p ++;
        }
        $players['name_in_row'] = substr($name_in_row,1);
    }else{
        $players['name_in_row'] = "";
    }

    $player_map[$place] = $players;
    $player_mid_map[$mid] = $players;
    $count++;
}
//echo "<div id='living'>";

//获得当前直播数据模式
$content = file_get_contents("http://127.0.0.1:34297/getGraphicsInfo");
//$content = file_get_contents("http://106.75.49.105:34297/getGraphicsInfo");
$t = json_decode($content,true);
$focusID = $t['mID'];
//$focusID = 32;

$sql="select * from SRFC_LIVE where sessionID=$sessionID ";
$ok = mysql_query($sql);
$row = mysql_fetch_array($ok);


$mode = $row['mode'];
$battlePs = $row['battlePs'];

$env['sessionID'] = $sessionID;
$env['db'] = $db;
$env['playerCount'] = $count;
$env['focusID'] = $focusID;
$env['live_mode'] = $mode;
$env['raceName'] = $row['raceName'];
$env['raceRound'] = $row['raceRound'];
$env['battlePs'] = $battlePs;
$env['battleEndPs'] = $row['battleEndPs'];
$env['lastWatchID'] = $row['lastWatchID'];
$env['isTimeOn'] = $row['isTimeOn'];
$env['isBadgeOn'] = $row['badgeOn'];
$env['isWeatherOn'] = $row['weatherOn'];
$env['isAutoOn'] = $row['autoOn'];
$env['isWeightOn'] = $row['weightOn'];
$env['isInfoOn'] = $row['infoOn'];


if ($row['autoOn'] == 1){
	$tt = getsql("select * from auto_watch_record");
	$last_mid = $tt['mID'];
	$last_timestamp = $tt['timestamp'];
	$last_steadyTime = $tt['steadyTime'];
	$last_key = $tt['watchkey'];
	$last_weight = $tt['watchweight'];
	$timePass = time() - $last_timestamp;

	$seed = mt_rand(0,3);
	if ($seed > 1) {
		$camID = 4;
	}else{
		$camID = $seed;
		$last_steadyTime = 30;//如果正好对车内视角，则放大时间
	} 

	$re = watch_by_pitstop($player_map,$env);//从pit状态查找观看对象
	$mid = $re['mid'];$steadyTime = $re['time'];$weight = $re['weight'];$key = 'pit';

	$re = watch_by_gap($player_map,0);//从gao查找观看对象
	if (!$re){
		$re = watch_by_dist($player_map,$env);//如果没有gap（所有人都不同圈了）,则用到dist来查找观看对象
	}
	if ($re['weight'] > $weight){//如果gap或者dist查找到的观看对象权重比pit更高，则mid替换。
		$mid = $re['mid'];$steadyTime = $re['time'];$weight = $re['weight']; $key = 'gap';
	}

	$re = watch_by_fastestlap($player_map,$env);
	if ($re['weight'] > $weight){
		$mid = $re['mid'];$steadyTime = $re['time'];$weight = $re['weight'];$key = 'fastlap';
	}

    $re = watch_by_random($player_map,$env);
    if ($re['weight'] > $weight){
        $mid = $re['mid'];$steadyTime = $re['time'];$weight = $re['weight'];$key = 'random';
    }
	//上述查询结束后得到本次查询权重最大的查询key和mid。
	$weightDiff = $weight - $last_weight;
	if ($weightDiff > 3) $last_steadyTime  = 0;//如果当前权重比上次权重大过3，则上次延续时间降低为0秒（为的是如果有突发情况，比如突然的追近，突然的进出站可以立刻切换镜头
	if ($timePass > $last_steadyTime && $mid > 0){//如果时间已经超过了上一次延续时间
		if ($last_mid == $mid){//如果上一次观看和本次观看的mid一致
            if ($timePass > 20){//超过20秒换第二快的人观看
            	$seed = mt_rand(1,2);
            	if ($seed == 1){
            		$tt = watch_by_gap($player_map,$player_mid_map[$mid]['mTimeBehindNext']);//50%的几率观看第二快的人
	    			if ($tt){
	    				$mid = $tt['mid'];
	    				$key = 'gap2';
	    			}else{
	    				$tt = watch_by_random($player_map,$env);//50%的几率随机找个人看
	    				$mid = $tt['mid'];
	    				$key = 'random';
	    			}
            	}else{
            		$tt = watch_by_random($player_map,$env);
    				$mid = $tt['mid'];
    				$key = 'random';
            	}
            	if ($key == 'pit'){
					$pitStatusNow =  $player_mid_map[$mid]['mInPits'];
					$sql = "update pitStatus set mInPits = $pitStatusNow where mid=$mid";//更新“上次”停站状态为当前值
					//$env['test'] = $sql;
					mysql_query($sql);
				}
    			switchCam($mid,$steadyTime,$key,$weight,$camID);
            }
		}else{
			if ($key == 'pit'){
				$pitStatusNow =  $player_mid_map[$mid]['mInPits'];
				$sql = "update pitStatus set mInPits = $pitStatusNow,mFinishStatus = ".$player_mid_map[$mid]['mFinishStatus']." where mid=$mid";//更新“上次”停站状态为当前值
				//$env['test'] = $sql;
				mysql_query($sql);
			}
	  		switchCam($mid,$steadyTime,$key,$weight,$camID);
        } 
	}
	//$env['test'] = $tt['testVar'];
	$env['test'] = "key:$last_key weight:".number_format($last_weight,2)." timePass:$timePass steadyTime:".number_format($last_steadyTime,2);
}


if ($mode == 'START'){
    show_mode_start($player_map,$env);//出发详细排位
}else{
    show_mode_noraml($player_map,$env);
}

function watch_by_gap($player_map,$gap){//根据时间差距找车
	$sql = "select * from timeBehindNext_table where mTimeBehindNext > $gap  and mFinishStatus = 0 and mid!= 0 and mInPits = 0 order by mTimeBehindNext limit 0,1";
	$re = getsql($sql);
	if ($re){
		$mid = $re['mid'];
		$weight = 5 - $re['mTimeBehindNext'];
		$time = $weight * 2;
		if ($time < 5) $time = 10;
		if ($gap > 0) $time = 10;
	}else{
		return array("weight" => 0);
	}
	return array("weight" => $weight,"time" => $time,"mid" => $mid);
}

function watch_by_dist($player_map,$env){//根据赛道位置差距找车
	$maxDist = $env['mLapDist'];
	foreach ($player_map as $player){
    	$in_mid = $player['mID'];
    	$own_dist = getsql("select mLapDist from timeBehindNext_table where mid=$mid");
    	$close_dist = getsql("select abs($own_dist - mLapDist) as t from timeBehindNext_table where mid != $mid and mid!= 0 and mFinishStatus = 0 and t > 0 and mInPits = 0 order by t limit 0,1");
    	if ($close_dist < $maxDist && $close_dist > 0){
    		$maxDist = $close_dist;
    		$watchID = $in_mid;
    	}
    }
    if ($maxDist < 40){
    	$mid = $watchID;
		$weight = 5;
		$time = 15;//
	}else{
		return array("weight" => 0);
	}
	return array("weight" => $weight,"time" => $time,"mid" => $mid);
}

function watch_by_pitstop($player_map,$env){//根据进出站找车
	foreach ($player_map as $player){
		$mid = $player['mID'];
		$pitStatusNow = $player['mInPits'];
		$pitStatusLast = getsql("select mInPits from pitStatus where mid=$mid  and mid!= 0 and mFinishStatus = 0");
		if ($pitStatusNow != $pitStatusLast && isset($pitStatusLast)){
			$mid = $player['mID'];
			$weight = 9;//进出站权重
			$time = 5;
			//$sql = "update pitStatus set mInPits = $pitStatusNow where mid=$mid";//更新“上次”停站状态为当前值
			//mysql_query($sql);
			if ($mid != 0) return array("weight" => $weight,"time" => $time,"mid" => $mid); else return array("weight" => 0);
		}
	}
	return array("weight" => 0);
}

function watch_by_fastestlap($player_map,$env){//根据最快圈找车
	$bestlap = getsql("select bestlap from auto_watch_record");
	foreach ($player_map as $player){
		if ($player['mBestLapTimeRow'] < $bestlap && $player['mBestLapTimeRow'] > 0){
			$sql = "update auto_watch_record set bestlap = ".$player['mBestLapTimeRow'];
			mysql_query($sql);
			$mid = $player['mID'];
			$weight = 4;//最快圈的权重
			$time = 10;
			return array("weight" => $weight,"time" => $time,"mid" => $mid);
		}
	}
	return array("weight" => 0);
}


function watch_by_random($player_map,$env){//随机找车
	while ($ps = mt_rand(1,$env['playerCount'])){
		if ($player_map[$ps]['mFinishStatus'] == 0 && $player_map[$ps]['mInPits'] == 0) break;//不看退赛的 不看停站的
	}
	$watchID = $player_map[$ps]['mID'];
	if($ps == 1) {
		$weight = 3;//正好随机到第1名则加大权重延长观看时间
		$time = 20;
	}else{
		$weight = 2;
		$time = 20;
	}
	return array("weight" => $weight,"time" => $time,"mid" => $watchID);
}

function switchCam($watchID,$steadyTime,$key,$weight,$camID){//相机切换
	$timenow = time();
	$callvar = "http://127.0.0.1:34297/cameraControl?mID=$watchID&mCameraType=$camID";
	$sql = "update auto_watch_record set mID = $watchID ,timestamp = $timenow,steadyTime = $steadyTime,watchkey='$key',watchweight=$weight,testVar='$callvar'";
	$ok = mysql_query($sql);

	//    0  = TV cockpit
    //    1  = cockpit
    //    2  = nosecam
    //    3  = swingman
    //    4  = trackside (nearest)
    //    5  = onboard000
	$re = file_get_contents($callvar);
}



function show_mode_start($player_map,$env){//出发详细排位
    $playerCount = $env['playerCount'];
    $playerJson='';
    for ($p = 0 ;$p < $playerCount ;$p++){
        $player = $player_map[$p+1];
        $playerJson = $playerJson.','.json_encode($player);
        $player['mGrid'] = $player['mPlace'];
        $mid = $player['mID'];
        $place  = $player['mPlace'];
        $sql="delete from srfc_grid where plyname = '$mid'";
        mysql_query($sql);

        $sql="insert into srfc_grid (ps,plyname) values ('$place','$mid')";
        mysql_query($sql);
    }
    $playerJson = substr($playerJson,1);
    $envJson = json_encode($env);
    echo '{"player":['.$playerJson.'],"env":['.$envJson.']}';
}


function show_mode_noraml($player_map , $env){//所有名次以及排行差距显示
    $db = $env -> db;
    $sessionID = $env['sessionID'];
    $playerCount = $env['playerCount'];
    $focusID = $env['focusID'];
    for ($p = 0; $p < $playerCount; $p++){
        $player = $player_map[$p+1];
        $mTimeBehindLeader = $player['mTimeBehindLeader'];
        $mTimeBehindNext = $player['mTimeBehindNext'];
        $mTotalLaps = $player['mTotalLaps'];
        if ($mTimeBehindNext <= 0) $mTimeBehindNext = ''; else $mTimeBehindNext = '+'.$mTimeBehindNext;
        if ($p == 0) {
            if ($mTotalLaps < 1){
                $mTimeBehindNext = '领跑';
            }else{
                $mTimeBehindNext = $mTotalLaps.'圈';
            }
        }
        $mLapsBehindLeader = $player['mLapsBehindLeader'];
        $mLapsBehindLeader = floor( $mLapsBehindLeader);
        if ( $mLapsBehindLeader > 0 && $mTimeBehindNext == "") $mTimeBehindNext = "+".$mLapsBehindLeader."圈";
        $player['mTimeBehindNext'] = $mTimeBehindNext;
        $playerJson = $playerJson.','.json_encode($player);
    }
    $playerJson = substr($playerJson,1);
    $envJson = json_encode($env);
    //echo '{"player":['.$playerJson.'],"env":[{"focusID":'.$focusID.'}]}';
    echo '{"player":['.$playerJson.'],"env":['.$envJson.']}';
}



function getsql($sql){
    $result=mysql_query($sql);
    $count = mysql_num_rows($result);
    if ($count == 0){
    	return ;
    }else{
	    $row = mysql_fetch_array($result,MYSQL_ASSOC);
	    if (count($row) > 1){
	    	$re = $row;
	    }else{
	    	$re = reset($row);
	    }
	    return $re;
	}
}

function SecToString ($sec)
{
    $ms = floor($sec * 1000);
    return (MsToString ($ms));
}

//转换时间显示
function MsToString ($ms)
{
    $hou = (string)floor ($ms/3600000);
    if ($hou == 0) $hou = "";
    $min = floor (($ms-$hou*3600000)/60000);
    if ($min < 10) $min = '0'.$min;
    $sec = floor (($ms-$min*60000)/1000);
    if ($sec <10) $sec = '0'.$sec;
    $thou = fmod($ms, 1000);
    if ($thou < 100 ) $thou = '0'.$thou;
    if (strlen($thou) < 3) $thou = $thou.'0';
    if ($hou != 0)
    {
        $tString = $hou.':'.$min.":".$sec.".".$thou;
    }
    else
    {
        $tString = $min.":".$sec.".".$thou;
    }
    return ($tString);
}

function Sec2Time($time)
{  
    if(is_numeric($time))
    {  
        $value = array(  
          "hours" => 0,  "minutes" => 0, "seconds" => 0,  
        );  
        if($time >= 3600){  
          $value["hours"] = floor($time/3600);  
          $time = ($time%3600);  
        }  
        if($time >= 60){  
          $value["minutes"] = floor($time/60);  
          $time = ($time%60);  
        }
        if ($value["minutes"]<10) $value["minutes"]="0".$value["minutes"];        
        $value["seconds"] = floor($time);  
        if ($value["seconds"]<10) $value["seconds"]="0".$value["seconds"];
        //return (array) $value;  
        $t='';
        $t = $value["seconds"];
        //if ($value["seconds"] > 0) $t = ":".$value["seconds"];
        if ($value["minutes"] > 0) $t = $value["minutes"].":" .$t;
        if ($value["hours"] > 0) $t = $value["hours"].":" .$t;
        Return $t;  
    }else{  
        return (bool) FALSE;  
    }  
 }  