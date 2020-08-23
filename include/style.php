<?php

?>
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
    height: <?=$lineHeight?>;
    line-height: <?=$lineHeight?>;
    text-align: center;
    background: <?=$background1?>;
    color:<?=$color1?>;
    font-size: 16px;
    font-weight: bold;
    border-top-left-radius:9px;
}
.cnName{
    left: 52px;
    width: 60px;
    top: 0px;
    position: absolute;
    color: <?=$color2?>;
    background:<?=$background2?>;
    height: <?=$lineHeight?>;
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
    color: <?=$color2?>;
    background: <?=$background2?>;
    height: <?=$lineHeight?>;
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
    left: 162px;
    width: 85px;
    height: <?=$lineHeight?>;
    line-height: <?=$lineHeight?>;
    font-size: 16px;
    color: <?=$color1?>;
    position: absolute;
    background: <?=$background3?>;
    text-align:center;
    border-bottom-right-radius:9px;
}

.pit{
    top: 0px;
    left: 250px; 
    width: 30px;
    height: <?=$lineHeight?>;
    line-height: <?=$lineHeight?>;
    font-size: 12px;
    font-weight:bold;
    color: #f3b25a;
    position: absolute;
    background: rgba(0,0,0,0.5);
    border-radius:24px;
    text-align:center;
}

.penalty{
    top: 0px;
    left: 280px; 
    width: 25px;
    height: <?=$lineHeight?>;
    line-height: <?=$lineHeight?>;
    font-size: 12px;
    font-weight:bold;
    color: #000;
    position: absolute;
    padding-left: 5px;
    border-bottom-right-radius:9px;
    text-align:center;
    background: rgba(100,100,100,0.8);
}

.best{
    position: absolute;
    display:none;
    left: 262px;
    top: 0px;
    width:0px;
    z-index:10;
    line-height: <?=$lineHeight?>;
    height: <?=$lineHeight?>;
    font-size: 14px;
    padding-left: 5px;
    background: <?=$dec_background2?>;
    color:#fff;
}



/* current player ****************************************************************************************************************/

#playerInfo_pos {
    position: absolute;
    left: <?=$driverInfoLeft?>px;
    top: <?=$driverInfoTop?>px;
    width: 40px;
    text-align: center;
    background: <?=$dec_background?>;
    color:<?=$color3?>;
    font-weight: bold;
    z-index: 10;
    width: 60px;
    height: 60px;
    font-size: 30px;
    line-height: 60px;
    border-top-left-radius:15px;
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
    width: 285px;
    height: 30px;
    line-height: 30px;
    font-size: 13px;
    background: <?=$background2?>;
    color:<?=$color2?>;
    padding-left: 5px;
    border-bottom-right-radius:9px;
}

#playerInfo_lastLap{
    display:none;
    position: absolute;
    top: <?=($driverInfoTop + 65)?>px;
    left: <?=($driverInfoLeft + 92)?>px; 
    font-size:18px;
    background: <?=$dec_background2?>;
    color:<?=$color4?>;
    padding-right: 5px;
    height:30px;
    width :255px;
    line-height: 30px;
    text-align:right;
    border-top-left-radius:9px;
    border-bottom-right-radius:9px;
}

/* qualify-info **************************************************************************************************************************/

#q_pos {
    position: absolute;
    left: <?=$driverInfoLeft?>px;
    top: <?=$driverInfoTop?>px;
    text-align: center;
    background: <?=$dec_background?>;
    color:<?=$color3?>;
    font-weight: bold;
    z-index: 10;
    width: 60px;
    height: 60px;
    font-size: 30px;
    line-height: 60px;
    border-bottom-right-radius:9px;
}

#q_cnName{
    left: <?=($driverInfoLeft + 62)?>px;
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
    left:<?=($driverInfoLeft + 192)?>px;
    width:20px;
    height: 20px;
    line-height: 20px;
    font-size: 12px;
    position: absolute;
    background: <?=$dec_background2?>;
    color:<?=$color1?>;
    text-align:center;
    z-index: 9;
}


#q_splitTime{
    top: <?=$driverInfoTop?>px;
    left: <?=($driverInfoLeft + 222)?>px;
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
    left: <?=($driverInfoLeft + 62)?>px;
    z-index: 9;
    width: 160px;
    height: 30px;
    line-height: 30px;
    font-size: 13px;
    position: absolute;
    background: <?=$background2 ?>;
    color:<?=$color2?>;
    padding-left: 5px;
}

#q_time{
    top:<?=($driverInfoTop + 30)?>px;
    left:<?=($driverInfoLeft +222)?>px;
    width: 80px;
    height: 30px;
    line-height: 30px;
    background: <?=$background1?>;
    color:<?=$color1?>;
    font-size: 14px;
    position: absolute;
    padding-left: 15px;
    border-bottom-right-radius:9px;
}

#q_splitDiff{
    top:<?=($driverInfoTop +65)?>px;
    left:<?=($driverInfoLeft + 182)?>px;
    width:135px;
    height: 30px;
    line-height: 30px;
    font-size: 16px;
    position: absolute;
    background: <?=$background1?>;
    color:<?=$color1?>;
    text-align:center;
    z-index: 7;
    border-bottom-right-radius:9px;
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