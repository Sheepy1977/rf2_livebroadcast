var lastTime = 0;
var lastWatchPlace = 2;
var lastLapShowTime = 0;
var BestLapShowTime =  0;
var BestLap = 999999;
var lastBestID = -1;
var batPs = 0; // 争夺名次
var battleEndPs = 1;//争夺名次结束
var live_mode = "RACE";
var auto = 0;//自动转播模式
var oldFocusID = 0;


function refresh(){
    $.getJSON("function/data.php",function(json){
        $.each(json.env,function(i,item){
            var env = item;
            var new_live_mode = item.live_mode;
            var battlePs = item.battlePs;
            var battleEndPs = item.battleEndPs;
            var auto = item.auto;
            mode_timeLeft(env,item.isTimeOn);//显示时间

            if (new_live_mode != live_mode){//如果新的观看模式不同，清空tower
                $("#tower").empty();
                $("#grid").hide();
                live_mode = new_live_mode;
            }
            if (live_mode == 'START'){
                $("#driver").hide();
                $("#playerInfo_name").html("");
                $("#playerInfo_lastLap").empty();
                $("#playerInfo_lastLap").hide();
                $("#qdriver").hide();
                $("#q_name").html("");
                $(".sessionLeftTime").hide();
                $(".sessionName").hide();
                mode_start();
                $("#grid").slideDown();
            }else{
                $("#EventName").hide();
                $("#RaceName").hide();
                showMode(new_live_mode,battlePs,battleEndPs);
            }
        });
    });
    setTimeout("refresh()",500);
}

function showMode(mode,batPs,battleEndPs){//通用模式显示
    var env = '';
    var isBadgeOn = 0;
    var midArr = new Array();
    $.getJSON("http://localhost:9999/LIVEBROADCAST/function/data.php",function(json){
        $.each(json.env,function(i,item){
            focusID = item.focusID;
            env = item;
            env.focusID = focusID;
            isBadgeOn = env.isBadgeOn;
            isInfoOn = env.isInfoOn;
            var isWeatherOn = env.isWeatherOn;
            if (isWeatherOn == 1){
                $("#mAmbientTemp").html(env.mAmbientTemp + "&#8451;");
                $("#mTrackTemp").html(env.mTrackTemp + "&#8451;");
                $("#mAvgPathWetness").html(env.mAvgPathWetness);
                $("#mDarkCloud").html(env.mDarkCloud);
                $("#mRaining").html(env.mRaining);
                $("#weatherDiv").slideDown();
            }else{
                $("#weatherDiv").slideUp();
            }
        });

        $.each(json.player , function(i,item){
            var place = item.mPlace;
            var mID = item.mID;
            midArr.push("player_" + mID);//把mid加入mid数组
            if (place == 1) {
                var backgroundStr = "style='color: #f3b25a;'"; 
            }else{
               var backgroundStr = "style=''";
            }
            var fontStr = "color:#ffffff'";
            

            if (batPs > 0 && place >= batPs && mode == 'RACE'){
                if (place == batPs){
                    var batShow = "<div class='bat' >\u4e89\u593a\u7b2c" + batPs +"\u540d</div>";
                }else{
                    var batShow = '';
                }
                if (place > battleEndPs){
                    var afterPs = 0.5;
                }else{
                    var afterPs = 0;
                }
                var realPs = place + afterPs + 1;
            }else{
                var realPs = place;
                var batShow = '';
            }

            var spanName = '#bestTimeShow';
            
            //$(spanName).html(mLastLapTime);
            var mBestLapTimeForPurple = item.mBestLapTime;
            var mBestLapTimeRow = item.mBestLapTimeRow;
            var bestLapShowOnTower = "";
            if (mode =='RACE' && mBestLapTimeRow > 0 && mBestLapTimeRow < BestLap){
                BestLap = mBestLapTimeRow;
                currentBestID = mID;
                if (lastBestID != currentBestID){
                    lastBestID = currentBestID;
                }
                
                $(spanName).html(mBestLapTimeForPurple);
                $(spanName).show();
                
            }
            env.bestID = lastBestID;
            if (mID ==  lastBestID && mode == 'RACE') {
                var backgroundStr = "style='background: #800080;'";
                if (BestLapShowTime < 10000 && BestLapShowTime > 0) bestLapShowOnTower = "<span class='bestlapOnTower'>" + mBestLapTimeForPurple + "</span>";
            }
            if (isInfoOn == 1){
                if (mID == focusID){
                    if (mode == 'RACE' || mode =='POS') driver_info(env,item);
                    if (mode == 'QUAL' ) qdriver_info(env,item);
                    var backgroundStr = "style='background: #51ddee;color:#000'";
                }
            }else{
                $("#driver").hide();
                $("#qdriver").hide();
            }
            
            if ($("#bestTimeShow").css("display") != 'none'){
                BestLapShowTime = BestLapShowTime + 50;
            }else{
                BestLapShowTime = 0;
            }
            if (BestLapShowTime > 5000) {
                $("#bestTimeShow").fadeOut("slow");
                BestLapShowTime = 0;
            }
            
            var placeShow = "<span class='pos' "+ backgroundStr + ">" + place + "</span>";
            //var driverName = item.mDriverName.substr(0,6).replace(/\s/ig,'');
            var driverNameShow = "<span class='name'>" + item.shortID + "</span>";
            var cnNameShow = "<span class='cnName'>" + item.ChineseName + "</span>";
            var mTimeBehindNext =  "<span class='offset' id ='offset_" + mID + "'>" + item.mTimeBehindNext + "</span>";
            var mBestLapTime = "<span class='offset' id ='offset_" + mID + "'>" + item.mBestLapTime + "</span>";
            
            var mLastLapTimeRow = item.mLastLapTimeRow;
            var mLastLapTime = item.mLastLapTime;
            var mInPits = item.mInPits;
            var mNumPenalties = item.mNumPenalties;
            var mNumPitstops = item.mNumPitstops;
            var mLapStartET = item.mLapStartET;
            if (mNumPitstops == 0) mNumPitstops = '';
            if (mLapStartET == '0') {
                var pitshow = "<span class='pit'>Out</span>";
            }else{
                var pitshow = "";
            }
            if (mInPits == 1) {
                if (mNumPitstops >0) mNumPitstops = mNumPitstops + 1; else mNumPitstops = "P"
                var pitshow = "<span class='pit'>" + mNumPitstops + "</span>";
            }else{
                var pitshow = '';
            }
            if (mNumPenalties > 0){
                var penaltyShow = "<span class='penalty'>P</span>";
            }else{
                var penaltyShow = "";
            }
            //penaltyShow = "<span class='penalty'>" + penaltyShow + "</span>";

            var mGrid = item.mGrid;
            var posChange = mGrid - place;
            if (posChange==0) posChange = "<span class='offset'>--</span>";
            if (posChange < 0) posChange = "<span class='offset'><b><font color=red>&darr;  " + (place - mGrid) + "</font></b></span>";
            if (posChange > 0) posChange = "<span class='offset'><b><font color=yellow>&uarr;  "  + posChange + "</font></b></span>";

            if (mode == 'QUAL') var showTime = mBestLapTime; 
            if (mode == 'RACE') var showTime = mTimeBehindNext;
            if (mode == 'POS') var showTime = posChange;
            
            var finish = "";
            if (item.mFinishStatus == 1 && (env.mSession == 7 || env.mSession ==10)) finish = "Finish";
            if (item.mFinishStatus == 2) finish = "DNF";
            if (item.mFinishStatus == 3) finish = "DQ";
            if (finish != "") {
                showTime = "<span class='offset' style='color:#bbb'>" + finish + "</span>";
            }


            var mVehicleClass = item.mVehicleClass;
            //if ((mVehicleClass != 'Bentley_Continental_GT3') && (mVehicleClass != 'McLaren_650S_GT3') && (mVehicleClass != 'Callaway_Corvette_C7_GT3-R') && (mVehicleClass != 'Mercedes_AMG_GT3'))
            //mVehicleClass = 'none';
            if (isBadgeOn == "1"){
                var initF = mVehicleClass.substr(3,1);
                if (initF == 'P') {
                	var colorF = "#ffffff";
                	var colorB = "#000000";
                }
                if (initF == "G") {
                	var colorF = "#000000";
                	var colorB = "#f1b44d";
                }
                var badge = "<span class='badge' style='background:" + colorB +";color:" + colorF +" '>" + initF + "</span>";
            }else{
                var badge = '';
            }
            //var bestTimeShow = "<span id='bestTimeShow_" + mID + "' class='best' ></span>"; 
            
            var htmlData = batShow + badge + placeShow + cnNameShow + driverNameShow + showTime + pitshow + penaltyShow + bestLapShowOnTower ;

            var moveDis = realPs * 26 + 50;
            var moveDis = moveDis + "px";
            var divName ="#player_" + mID;
            if ($(divName).length > 0){
                $(divName).html(htmlData);
                $(divName).show();
                $(divName).animate({top:moveDis},300);
            }else{
                var newdiv = $("<div></div>");
                newdiv.attr("id","player_" + mID);
                newdiv.attr("onclick","camChange(" + mID + "," + place + ")");
                newdiv.addClass("towerItem");
                newdiv.css("position","absolute");
                newdiv.css("top","0px");
                newdiv.css("cursor","pointer");
                newdiv.css("display","none");
                $("#tower").append(newdiv);
                $(divName).html(htmlData);
                $(divName).animate({top: moveDis},realPs * 100);
            }
        });
        $(".towerItem").each(function(){//删除已经不在mid 表里的元素
            var tempid = $(this).attr("id");
            if ($.inArray(tempid,midArr) == -1) $(this).remove();
        })
    });
}

function mode_weather(){
	$.get("http://localhost:9999/LIVEBROADCAST/function/data.php?mode=9",function(data)
	{
		$("#env").html(data);
		$("#env").slideDown();
	});
	setTimeout("mode_weather()",2000);
}



function mode_timeLeft(env,mode){
	if (mode == 1){
        var mCurrentET = parseInt(env.mCurrentET) - 30;
        var mEndET = parseInt(env.mEndET) - 30;
        if (mEndET > 0){
            var showLeftTime = mEndET - mCurrentET;
            if (showLeftTime < 0) showLeftTime = "+" + formatSeconds(0 -  showLeftTime); else showLeftTime = formatSeconds(mEndET - mCurrentET)
            var showEndEt =  formatSeconds(mEndET);
        }else{
            var showLeftTime='--:--:--';
            var showEndEt='--:--:--';
        }
       
        var mSession = env.mSession;

        var sessionArray = new Array();
        sessionArray[1] = "\u7ec3\u4e60";//练习
        sessionArray[5] = "\u6392\u4f4d";//排位
        sessionArray[6] = "\u6696\u80ce";//暖胎
        sessionArray[7] = "\u6bd4\u8d5b";//比赛
        sessionArray[9] = "\u70ed\u8eab";//热身
        sessionArray[10] = "\u6bd4\u8d5b";//比赛

        var showSession = sessionArray[mSession];

        //var showLeftTime = mEndET;
        $(".sessionName").slideDown();
        $(".sessionLeftTime").slideDown();
        $(".sessionName").html(showSession);
        $(".sessionLeftTime").html(showLeftTime + " / " + showEndEt);
    }else{
        $(".sessionLeftTime").hide();
        $(".sessionName").hide();
    }
}


function camChange(mID,place){
    $.get("http://127.0.0.1:34297/cameraControl?mID="+ mID + "&mCameraType=4" + '&date='+ (new Date()).getTime());
    $("#batPs").val(place);
    $("#batEndPs").val(parseInt(place) + 1);
}

function row2TimeStr(row,digi){
    if (row >= 60){
        if (row > 120){
            var min = 2;
            var sec = (row - 120);
            if (sec < 10) {
                sec = "0" + sec.toFixed(digi) ;
            }else{
                sec = sec.toFixed(digi);
            }
            return min + ":" + sec; 
        }else{
            var min = 1;
            var sec = (row - 60);
            if (sec < 10) 
            {
                sec = "0" + sec.toFixed(digi) ;
            }else{
                sec = sec.toFixed(digi);
            }
            return min + ":" + sec; 
        }
    }else{
        return row.toFixed(digi);
    }
}

function formatSeconds(value) {
    var secondTime = parseInt(value);// 秒
    var minuteTime = 0;// 分
    var hourTime = 0;// 小时
    if(secondTime > 60) {//如果秒数大于60，将秒数转换成整数
        //获取分钟，除以60取整数，得到整数分钟
        minuteTime = parseInt(secondTime / 60);
        //获取秒数，秒数取佘，得到整数秒数
        secondTime = parseInt(secondTime % 60);
        //如果分钟大于60，将分钟转换成小时
        if(minuteTime > 60) {
            //获取小时，获取分钟除以60，得到整数小时
            hourTime = parseInt(minuteTime / 60);
            //获取小时后取佘的分，获取分钟除以60取佘的分
            minuteTime = parseInt(minuteTime % 60);
        }
    }
    if (parseInt(secondTime) < 10) var showSec = "0" + parseInt(secondTime); else var showSec = parseInt(secondTime);
    var result = "" + showSec + "";

    if(minuteTime > 0) {
        if (minuteTime < 10) var showMin =  "0" + parseInt(minuteTime) ;else var showMin = parseInt(minuteTime);
        result = "" + showMin + ":" + result;
    }else{
        result = "00:" + result;
    }
    if(hourTime > 0) {
        result = "" + parseInt(hourTime) + ":" + result;
    }else{
        result = "0:" + result;
    }
    return result;
}


var count = 0;
var lineLimit = 8;
var ps = 1;

function mode_start(){//出发顺位
    $.getJSON("http://localhost:9999/LIVEBROADCAST/function/data.php?mode=1",function(json){
        $.each (json.env ,function (i,item){
            $("#EventName").html(item.raceName); 
            $("#RaceName").html(item.raceRound);
            $("#EventName").slideDown();
            $("#RaceName").slideDown();
            if (item.lastWatchID > ps){
                next();
            }
        });  
    });   
}

function next(){
    var driverNameArray = new Array();
    var cnNameArray = new Array();
    var pointsArray = new Array();
    var totalPPL = 0;
    $.getJSON("http://localhost:9999/LIVEBROADCAST/function/data.php?mode=1",function(json){
        $.each(json.player , function(i,item){
            var place = item.mPlace;
            var driverName = item.mDriverName.toUpperCase();
            var cnName = item.ChineseName;
            var points = item.points;
            driverNameArray[place] = driverName;
            cnNameArray[place] = cnName;
            pointsArray[place] = points;
            totalPPL = totalPPL + 1;
        });
        if (ps <= totalPPL){
            if (count >  lineLimit){
                var realCount = lineLimit + 1;
                setTimeout(function(){
                    $(".line").animate({"top":"-=50px"},500);
                },500)
                setTimeout(function(){
                    $("#player_"+(count - lineLimit - 2)+"_0").animate({opacity:0},500);
                    $("#player_"+(count - lineLimit - 2)+"_1").animate({opacity:0},500);
                },500);
            }else{
                var realCount = count;
            }

            var newdiv1 = $("<div></div>");    
            var moveDis1 = realCount * 50 + 100; 
            newdiv1.attr("id","player_" + count + "_0");
            newdiv1.attr("class","line");
            newdiv1.css("position","absolute");
            newdiv1.css("top", moveDis1 + "px");
            newdiv1.css("opacity","0");
            $("#grid").append(newdiv1);
            if (ps == 1) var poleStr = "style='background:#ff0000;color:#fff'";
            var htmlData1 = "<div class='gridPs'" + poleStr + ">" + ps + "</div><div class='gridPlayer'>" + cnNameArray[ps] + " " + driverNameArray[ps] + "</div>";
            var divName1 = "#player_"+ count + "_0";
            $(divName1).animate({left:"200px",opacity:1},300);
            $(divName1).html(htmlData1);
            ps ++;

            if (typeof(driverNameArray[ps]) != 'undefined'){
                var newdiv2 = $("<div></div>");
                var moveDis2 = realCount * 50 + 120;
                newdiv2.attr("id","player_" + count + "_1");
                newdiv2.attr("class","line");
                newdiv2.css("position","absolute");
                newdiv2.css("top", moveDis2 + "px");
                newdiv2.css("opacity","0");
                $("#grid").append(newdiv2);
                var htmlData2 = "<div class='gridPs'>" + ps + "</div><div class='gridPlayer'>"+ cnNameArray[ps] + " " + driverNameArray[ps] + "</div>";
                var divName2 = "#player_"+ count + "_1";
                $(divName2).css("right","0px");
                $(divName2).animate({right:"200px",opacity:1},300);
                $(divName2).html(htmlData2); 
                ps ++; 
            }
            count ++;
        }
    }); 
}


function qdriver_info(env,item){//排位模式单人数据
    var qBestLapSector1 = env.mBestLapSector1;
    var qBestLapSector1Row = env.mBestLapSector1Row;
    var qBestLapSector2 = env.mBestLapSector2;
    var qBestLapSector2Row = env.mBestLapSector2Row;
    var qBestLapTime = env.mBestLapTime;
    var qBestLapTimeRow = env.mBestLapTimeRow;
    var currentET = env.mCurrentET;

    var place = item.mPlace;
    var driverName = item.mDriverName;
    var cnName = item.ChineseName;
    var mBestLapTime = item.mBestLapTime;
    var mCurSector1 = item.mCurSector1;
    var mCurSector2 = item.mCurSector2;
    var mCurSector1Row = item.mCurSector1Row;
    var mCurSector2Row = item.mCurSector2Row;
    var mLastLapTime = item.mLastLapTime;
    var mLastLapTimeRow = item.mLastLapTimeRow;

    var bestRowArray = new Array(-1,qBestLapSector1Row,qBestLapSector2Row,qBestLapTimeRow,0);//最快计时圈的第1，第2，和最终计时点的纯数值 
    var bestArray = new Array("0.0",qBestLapSector1,qBestLapSector2,qBestLapTime,"0.0");//最快计时圈的第1，第2，和最终计时点的人类可读数值
    var mCurSectorRowArray = new Array(-1,mCurSector1Row,mCurSector2Row,mLastLapTimeRow,0);//当前计时圈的第1，第2，和最终计时点的纯数值
    var mCurSectorArray = new Array("0.0",mCurSector1,mCurSector2,mLastLapTime,"0.0");

    var mSector = item.mSector;
    var mInPits = item.mInPits;
    var mLapStartET = item.mLapStartET;
    var cTime = currentET - mLapStartET;
    var cTimeShow = row2TimeStr(cTime,1);
    if (mLapStartET == '0') {
        cTimeShow =  '\u51fa\u573a\u5708';//出场圈
        var cTimeSign = 'OUTLAP';
    }
    if (mInPits == 1) cTimeShow = '\u505c\u7ad9';//停站
    var msgShowTime = 5;//信息停留秒数
    if (mLapStartET != 0 && mInPits != 1 && qBestLapTimeRow < 9999){//开始计时圈,且有了最佳计时圈才开始显示这些东西
        var j = 1;
        if (mSector == 1) var j = 1;
        if (mSector == 2) var j = 2;
        if (mSector == 0) var j = 3;
        
        if (cTime < msgShowTime && cTimeSign !='OUTLAP') {
            var stage = 0;//刚过出发点
            //若是第一个飞驰圈，则CTIMESING会为OUTLAP
        }else{
            cTimeSign = "in Fly";
            var stage = 7;//无意义。
        }
        if (cTime > msgShowTime && cTime < (qBestLapSector1Row - msgShowTime)) var stage = 1 ;//在出发点和S1之前
        if (cTime >= (qBestLapSector1Row - msgShowTime) && cTime <= (qBestLapSector1Row + msgShowTime)) var stage = 2;//在S1附近
        if (cTime > (qBestLapSector1Row + msgShowTime) && cTime < (qBestLapSector2Row - msgShowTime)) var stage = 3;//在 S1和S2之间
        if (cTime >= (qBestLapSector2Row - msgShowTime) && cTime <= (qBestLapSector2Row + msgShowTime)) var stage = 4;//在S2附近
        if (cTime > (qBestLapSector2Row + msgShowTime) && cTime < (qBestLapTimeRow - msgShowTime)) var stage =5;//在S2和S3(终点)之间
        if (cTime >= (qBestLapTimeRow - msgShowTime) && cTime <(qBestLapTimeRow + msgShowTime)) var stage =6;//在S3附近

        switch (stage){
            case 0:
                showSplitDiff("F",cTime,mLastLapTimeRow,qBestLapTimeRow);
                break;
            case 1:
                hideSplitDiff();
                break;
            case 2:
                showSplitDiff("S"+j,cTime,mCurSector1Row,qBestLapSector1Row);
                break;
            case 3:
                hideSplitDiff();
                break;
            case 4:
                showSplitDiff("S"+j,cTime,mCurSector2Row,qBestLapSector2Row);
                break;
            case 5:
                hideSplitDiff();
                break;
            case 6:
                showSplitDiff("S"+j,cTime,mLastLapTimeRow, qBestLapTimeRow);
                break;
        }
    }
    $("#driver").hide();
    $("#playerInfo_name").html("");
    $("#playerInfo_lastLap").empty();
    $("#playerInfo_lastLap").hide();
    $("#qdriver").show();
    if ($("#q_name").html() != driverName){
        $("#qdriver").show();

        $("#q_pos").css("height","0px");
        $("#q_pos").animate({height:"60px"},300);

        $("#q_name").css("width","0px");
        $("#q_name").animate({width:"155px"},300);

        $("#q_cnName").css("width","0px");
        $("#q_cnName").animate({width:"250px"},300);

        $("#q_time").css("width","0px");
        $("#q_time").animate({width:"80px"},300);
    }
    $("#q_pos").html(place);
    $("#q_name").html(driverName);
    $("#q_cnName").html(cnName);
    $("#q_time").html(cTimeShow);


}

function showSplitDiff(sector,cTime,curSecRow,bestSecRow){//显示排位模式单节差距
    $("#q_splitDiff").css("background","#3e7a37");
    $("#q_splitName").html(sector);
    $("#q_splitTime").html(row2TimeStr(bestSecRow,3));
    $("#q_splitName").fadeIn("slow");
    $("#q_splitTime").fadeIn("slow");
    var splitDiff = cTime - bestSecRow;
    if (curSecRow != -1){
        var splitDiff = curSecRow - bestSecRow;
        var bestShow =  "(" + row2TimeStr(curSecRow,3) + ")";
        var splitDiffShow = checkSplitDiff(splitDiff,3) + bestShow;
    } else{
        var splitDiffShow = checkSplitDiff(splitDiff,1);
    }
   
    $("#q_splitDiff").html(splitDiffShow);
    $("#q_splitDiff").show();
}

function checkSplitDiff(split,digi){//检查排位模式单节差距
    if (split > 0){
        $("#q_splitDiff").css("background","#f3b25a");
        $("#q_splitDiff").css("color","#000");
        var re = "+" + row2TimeStr(split,digi);
    }else{
        $("#q_splitDiff").css("background","#3e7a37");
        $("#q_splitDiff").css("color","#FFF");
        var re = split.toFixed(digi);
    }
    return re;
}

function hideSplitDiff(){//关闭排位模式单节差距
    $("#q_splitName").fadeOut("slow");
    $("#q_splitTime").fadeOut("slow");
    $("#q_splitDiff").fadeOut("slow");// 关闭单节差距显示
}


function driver_info(env,item){//比赛模式单人信息
    var session = env.mSession;
    var bestID = env.bestID;
    var focusID = env.focusID;
    var place = item.mPlace;
    var driverName = item.mDriverName
    var name_in_row = item.name_in_row;
    var cnName = item.ChineseName;
    var mTimeBehindNext = item.mTimeBehindNext;
    var mBestLapTime = item.mBestLapTime;
    var mLastLapTime = item.mLastLapTime;
    var weight = item.weight;


    var lastLap =  "<small>\u4e0a\u5708:" +  mLastLapTime + "     \u6700\u5feb:" + mBestLapTime;
    if ($("#playerInfo_name").html() != driverName  && $("#playerInfo_name").html() != " "){
        $("#driver").show();
        $("#pitNumOnDriver").html("");
        $("#pitNumOnDriver").hide();
        $("#playerInfo_pos").css("height","0px");
        //if (focusID == bestID) {
            //$("#playerInfo_pos").css("background","#800080");
            //$("#playerInfo_pos").css("color","#800080");
        //}else{
            //$("#playerInfo_pos").css("background","rgba(0,0,0,0)");
        //    $("#playerInfo_pos").css("color","#fff");   
        //} 
        $("#playerInfo_pos").animate({height:"60px"},300);

        $("#playerInfo_name").css("width","0px");
        $("#playerInfo_name").animate({width:"285px"},300);

        $("#playerInfo_cnName").css("width","0px");
        $("#playerInfo_cnName").animate({width:"200px"},300);

        $("#playerInfo_offset").css("width","0px");
        $("#playerInfo_offset").animate({width:"95px"},300);

        if (mBestLapTime!='' && mLastLapTime != ''){
            $("#playerInfo_lastLap").slideDown();
            $("#playerInfo_lastLap").html(lastLap);
            lastLapShowTime = 0;
        }
    }
    $("#qdriver").hide();
    $("#q_name").html("");
    $("#driver").show();
    if (item.mNumPitstops > 0) {
        $("#pitNumOnDriver").html(item.mNumPitstops + "P");
        $("#pitNumOnDriver").fadeIn("slow")
     }else{
        $("#pitNumOnDriver").html("")
        $("#pitNumOnDriver").hide()
     }
    $("#playerInfo_pos").html(place);
    $("#playerInfo_name").html(driverName);
    if (name_in_row != '') {
        $("#playerInfo_name_in_row").html(name_in_row);
        $("#playerInfo_name_in_row").show();
    }else{
        $("#playerInfo_name_in_row").hide();
    }
    $("#playerInfo_cnName").html(cnName);
    $("#playerInfo_weight").html(weight);//2020.5.20
    if (session != 7 && session !=10 ) var showTime = mBestLapTime; else var showTime = mTimeBehindNext;

    var finish = "";
    if (item.mFinishStatus == 2) finish = "DNF";
    if (item.mFinishStatus == 3) finish = "DQ";
    if (finish != "") {
        showTime = finish ;
    }

    $("#playerInfo_offset").html(showTime);
    if ($("#playerInfo_lastLap").html() != '') lastLapShowTime = lastLapShowTime + 300;//控制最快圈显示时间
    if (lastLapShowTime >= 9000){
        $("#playerInfo_lastLap").slideUp();
        lastLapShowTime = 0;
    }

    var test = env.test;
    $("#test").html(test);
    $("#test").show();
}