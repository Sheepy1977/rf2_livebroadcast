<?php
error_reporting(E_ERROR);
include ("include/config.php");//导入css位置，颜色设置
$db = mysql_connect("127.0.0.1:8888", "root","root");
mysql_select_db("broadcast", $db);
date_default_timezone_set('PRC');
$sql = "TRUNCATE timeBehindNext_table";//清理时间差表，避免出现服务器上不存在的mid
mysql_query($sql);
$sql = "TRUNCATE pitStatus"; //清理停站记录表
mysql_query($sql);
//$sql = "TRUNCATE en_player_in_row";//清理换人记录表
//mysql_query($sql);
//$sql = "update auto_watch_record set bestlap = 9999";//设置最快圈记录为9999
//mysql_query($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>LIVE-<?=$mode?>-MODE</title>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/mode_2.js?t=<?=time()?>"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .sessionName{
        background:rgba(255,255,255,0.8);
        color:#000;
        padding:5px;
        position: absolute;
        top:48px;
        left:27px;
        width: 55px;
        font-size:12px;
        font-weight: bold;
    }
    .sessionLeftTime{
        position: absolute;
        top:48px;
        left:67px;
        background:#51ddee;
        color:#000;
        padding:5px;
        width: 178px;
        text-align: center;
    }
    .best{
        position: absolute;
        top: 560px;
        left:<?=($driverInfoLeft + 60)?>px;
        background:rgba(255,255,255,0.8);
        color:#800080;
        font-size:18px;
        width:80px;
        padding:5px;
    }



    #weatherDiv{
        position: absolute;
        text-align: center;
        top:5px;
        left:27px;
        width: 228px;
    }

    .weather{
        background:rgba(0,0,0,0.5);
        color:#fff;
        padding:5px;
    }
    .weatherContent{
        background:rgba(0,0,0,0.5);
        color:#fff;
        padding:5px;
    }
    #test{
        position: absolute;
        bottom: 0px;
        right:30px;
        color:#fff;
        padding:5px; 
        font-size: 14px;
        z-index: 9;
    }
    .finishStatus{
        top: 0px;
        left: 245px; 
        width: 25px;
        height: 25px;
        line-height: 25px;
        font-size: 12px;
        font-weight:bold;
        color: #cdcdcd;
        position: absolute;
        background: rgba(0,0,0,0.5);
        border-bottom-right-radius:3px;
        text-align:center;
    }

    #pitNumOnDriver{
        position: absolute;
        bottom: 62px;
        right:35px;
        color:#fff;
        padding:5px; 
        font-size: 12px;
        color:#fff;
        border-radius:12px;
        background: rgba(0,0,80,0.6);
        z-index: 19;
        display: none;
    }

    #playerInfo_name_in_row{
        display: none;
        position: absolute;
        top: <?=($driverInfoTop + 36)?>px;
        left: <?=($driverInfoLeft + 142)?>px; 
        height: 20px;
        line-height: 20px;
        font-size: 13px;
        color:<?=$color2?>;
        padding-left: 5px;
        padding-right: 5px;
        background: #333;
        border-radius: 6px;
    }


    body{
        padding: 0;
        margin: 0;
        font-size:14px;
        background-color:rgba(0,0,0,0);  
        font-family: "Helvetica Neue", Helvetica, Arial, "PingFang SC", "Hiragino Sans GB", "WenQuanYi Micro Hei", "Microsoft Yahei", sans-serif;
    }
    /* Start grid **********************************************************************************************************************/
    #control{
            position: absolute;
            bottom: 0px;
            right:0px;
        }
    #EventName{
        background: #f3b25a;
        height: 40px;
        line-height: 40px;
        font-size: 24px;
        color: #000;
        text-align: center;
        display:none;
        font-weight: bold;
        z-index: 1;
    }
    #RaceName{
        background: rgba(0,0,0,0.6);
        height: 40px;
        line-height: 40px;
        color: #FFF;
        font-weight: bold;
        font-size: 24px;
        padding: 0 5px;
        text-align: center;
        display:none;
    }
    .gridPs{
        background: #f3b25a;
        height: 40px;
        line-height: 40px;
        color: #000;
        font-weight: bold;
        font-size: 20px;
        width:50px;
        text-align: center;
        float:left;

    }
    .gridPlayer{
        background: rgba(0,0,0,0.5);
        left:50px;
        height: 40px;
        line-height: 40px;
        color: #FFF;
        font-weight: bold;
        font-size: 20px;
        width:350px;
        padding-left: 55px;
    }

    .pts{
        background: rgba(0,0,0,0.8);
        float: right;
        height: 40px;
        line-height: 40px;
        color: #FFF;
        font-weight: bold;
        font-size: 24px;
        width:50px;
        text-align: center;
    }

    /* tower **********************************************************************************************************************/

    .badge{
        position: absolute;
        left: 5px;
        top: 0px;
        height: 12px;
        width: 12px;
        font-size:12px;
        text-align:center;
        margin-top:3px;
        padding:2px;
        color:#fff;
        border-radius:12px;
        background: rgba(0,0,80,0.6);
    }
    .pos{
        position: absolute;
        left: 27px;
        width: 25px;
        top: 0px;
        height: <?=$height?>;
        line-height: <?=$lineHeight?>;
        text-align: center;
        background: <?=$background1?>;
        color:<?=$color1?>;
        font-size: 16px;
        font-weight: bold;
    }



    .cnName{
        left: 52px;
        width: 60px;
        top: 0px;
        position: absolute;
        color: <?=$color1?>;
        background:<?=$background2?>;
        height: <?=$height?>;
        padding-left: 5px;
        font-size: 16px;
        line-height: <?=$lineHeight?>;
        font-weight: bold;
    }
    .name{
        left: 117px;
        width: 40px;
        top: 0px;
        position: absolute;
        color: <?=$color1?>;
        background: <?=$background2?>;
        height: <?=$height?>;
        padding-left: 5px;
        font-size: 14px;
        line-height: <?=$lineHeight?>;
    }

    .xie{
        left: 162px;
        width: 5px;
        top: 0px;
        position: absolute;
        color: <?=$color2?>;
        background: <?=$background3?>;
        line-height:25px;
    }


    .offset{
        top: 0px;
        left: 161px;
        width: 94px;
        height: <?=$height?>;
        line-height: <?=$lineHeight?>;
        font-size: 16px;
        color: <?=$color1?>;
        position: absolute;
        background: <?=$background3?>;
        text-align:center;
    }

    .pit{
        top: 0px;
        left: 245px; 
        width: 10px;
        height: <?=$height?>;
        line-height: <?=$lineHeight?>;
        font-size: 12px;
        font-weight:bold;
        color: #111;
        background: #ffffff;
        position: absolute;
        text-align:center;
    }

    .penalty{
        top: 0px;
        left: 247px; 
        width: 30px;
        height: <?=$height?>;
        line-height: <?=$lineHeight?>;
        font-size: 12px;
        font-weight:bold;
        color: #f3b25a;
        position: absolute;
        text-align:center;
    }

    .bestlapOnTower{
        top: 0px;
        left: 260px; 
        width: 60px;
        padding-left: 2px;
        padding-right: 2px;
        line-height: 25px;
        font-size: 12px;
        font-weight:bold;
        color: #fff;
        position: absolute;
        background: #800080;
        text-align:center;
    }


    /* current player ****************************************************************************************************************/

    #playerInfo_pos {
        position: absolute;
        left: <?=$driverInfoLeft-5?>px;
        top: <?=$driverInfoTop?>px;
        width: 40px;
        text-align: center;
        background: rgba(0,0,0,0);
        color:#fff;
        font-weight: bold;
        z-index: 10;
        width: 60px;
        height: 60px;
        font-size: 55px;
        line-height: 60px;
        text-shadow: #000 1px 0 0, #000 0 1px 0, #000 -1px 0 0, #000 0 -1px 0; 
    }

    #playerInfo_cnName{
        position: absolute;
        left: <?=($driverInfoLeft + 60)?>px;
        top: <?=$driverInfoTop?>px;
        background: <?=$background3?>;
        color:<?=$color1?>;
        height: 30px;
        width: 200px;
        font-size: 20px;
        line-height: 30px;
        padding-left: 5px;
        font-weight:bold;
    }

    #playerInfo_offset{
        position: absolute;
        top: <?=$driverInfoTop?>px;
        left: <?=($driverInfoLeft +250)?>px;
        z-index: 9;
        width: 90px;
        height: 30px;
        line-height: 30px;
        font-size: 18px;
        background: <?=$dec_background2?>;
        color:<?=$color2?>;
        padding-left: 5px;
        text-align:center;
    }

    #playerInfo_name{
        position: absolute;
        top: <?=($driverInfoTop + 30)?>px;
        left: <?=($driverInfoLeft + 60)?>px; 
        width: 200px;
        height: 30px;
        line-height: 30px;
        font-size: 13px;
        background: <?=$background2?>;
        color:<?=$color1?>;
        padding-left: 5px;

    }

    #playerInfo_lastLap{
        display:none;
        position: absolute;
        top: <?=($driverInfoTop + 65)?>px;
        left: <?=($driverInfoLeft + 130)?>px; 
        font-size:18px;
        background: <?=$dec_background2?>;
        color:<?=$color2?>;
        padding-right: 5px;
        height:30px;
        width :215px;
        line-height: 30px;
        text-align:right;
    }

    #playerInfo_weight {
        position: absolute;
        left: <?=$driverInfoLeft + 300?>px;
        top: <?=$driverInfoTop - 30?>px;
        text-align: center;
        background: rgba(0,0,0,0);
        color:#fff;
        font-weight: bold;
        z-index: 10;
        width: 20px;
        height: 20px;
        font-size: 20px;
        line-height: 20px;
        text-shadow: #000 1px 0 0, #000 0 1px 0, #000 -1px 0 0, #000 0 -1px 0; 
    }
    /* qualify-info **************************************************************************************************************************/

    #q_pos {
        position: absolute;
        left: <?=$driverInfoLeft-5?>px;
        top: <?=$driverInfoTop?>px;
        width: 40px;
        text-align: center;
        background: rgba(0,0,0,0);
        color:#fff;
        font-weight: bold;
        z-index: 10;
        width: 60px;
        height: 60px;
        font-size: 55px;
        line-height: 60px;
        text-shadow: #000 1px 0 0, #000 0 1px 0, #000 -1px 0 0, #000 0 -1px 0; 
    }

    #q_cnName{
        left: <?=($driverInfoLeft + 65)?>px;
        top: <?=$driverInfoTop?>px;
        position: absolute;
        background: <?=$background3?>;
        color:<?=$color1?>;
        height: 30px;
        width: 250px;
        font-size: 20px;
        line-height: 30px;
        padding-left: 5px;
        font-weight:bold;
    }

    #q_splitName{
        top:<?=$driverInfoTop + 5?>px;
        left:<?=($driverInfoLeft + 190)?>px;
        width:20px;
        height: 20px;
        line-height: 20px;
        font-size: 12px;
        position: absolute;
        background: <?=$dec_background2?>;
        color:<?=$color2?>;
        text-align:center;
        z-index: 9;
    }


    #q_splitTime{
        top: <?=$driverInfoTop?>px;
        left: <?=($driverInfoLeft + 220)?>px;
        z-index: 9;
        width: 100px;
        height: 30px;
        line-height: 30px;
        font-size: 18px;
        position: absolute;
        color:<?=$color1?>;
        padding-left: 5px;
        text-align:center;
    }

    #q_name{
        top: <?=($driverInfoTop + 30)?>px;
        left: <?=($driverInfoLeft + 65)?>px;
        z-index: 9;
        width: 160px;
        height: 30px;
        line-height: 30px;
        font-size: 13px;
        position: absolute;
        background: <?=$background2 ?>;
        color:<?=$color1?>;
        padding-left: 5px;
    }

    #q_time{
        top:<?=($driverInfoTop + 30)?>px;
        left:<?=($driverInfoLeft +225)?>px;
        width: 80px;
        height: 30px;
        line-height: 30px;
        background: <?=$background1?>;
        color:<?=$color1?>;
        font-size: 14px;
        position: absolute;
        padding-left: 15px;
    }

    #q_splitDiff{
        top:<?=($driverInfoTop +65)?>px;
        left:<?=($driverInfoLeft + 185)?>px;
        width:135px;
        height: 30px;
        line-height: 30px;
        font-size: 16px;
        position: absolute;
        background: <?=$background1?>;
        color:<?=$color1?>;
        text-align:center;
        z-index: 7;
    }


    /* admin-control **************************************************************************************************************************/

    .mode-switch{
        bottom:0px;
        left 600px;
        position: absolute;
        background :rgba(0,0,0,0);
        color:#333;
    }

    .bat{
        position: relative;
        width:110px;
        font-size: 18px;
        font-weight: bold;
        left:52px;
        bottom:20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        color:#fff;
        background:<?=$dec_background2?>
    }

    a:link{
        color:#666;
    }

    a:visited{
        color:#666;
    }

    a:hover{
        color:#eee;
    }


    </style>
</head>

<body onload = 'refresh()'>

<div id='tower'></div>
<div id ='driver' style="display: none">
    <div id ="pitNumOnDriver"></div>
    <div id="playerInfo_pos" > </div>
    <div id="playerInfo_name"></div>
    <div id="playerInfo_name_in_row"></div>
    <div id="playerInfo_offset" ></div>
    <div id="playerInfo_cnName" ></div>
    <div id="playerInfo_weight" ></div>
    <div id="test" style="display: none;"></div>
</div>
<div id = 'playerInfo_lastLap'></div>
<div id =  "bestTimeShow" class='best' style="display: none"></div>
<div id='qdriver' style="display: none">
    <div id="q_pos"> </div>
    <div id="q_name"></div>
    <div id="q_splitName" style="display: none;"></div>
    <div id="q_splitTime" style="display: none;"></div>
    <div id="q_cnName" ></div>
    <div id="q_time" ></div>
    <div id="q_splitDiff" style="display: none"></div>
    
</div>
<div id='EventName'></div>
<div id="RaceName"></div>
<div id="grid"></div>
<div class="sessionName"></div><div class="sessionLeftTime"></div>
<div id='weatherDiv'  style="display:none" >
    <p class='weatherContent'>
        &#x5929;&#x6C14;&#xFF1A;
    	<i class="fa fa-thermometer-empty" aria-hidden="true"></i>&nbsp;
    	<span  id = "mAmbientTemp"></span>&nbsp;
     	<i class="fa fa-tint" aria-hidden="true"></i>&nbsp;
	    <span  id = "mRaining"></span>&nbsp;
	    <i class="fa fa-percent" aria-hidden="true"></i>&nbsp;
	    <span  id = "mAvgPathWetness"></span>
    </p>
</div>