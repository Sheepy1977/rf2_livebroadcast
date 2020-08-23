<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />

<title>SRFC RF2 living Conrtrol</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">

function camChange(mID,mPlace,camID)
{
    $.get("http://127.0.0.1:34297/cameraControl?mID="+ mID + "&mCameraType=" + camID + '&date='+ (new Date()).getTime())
    $(".name").css("font-weight","normal");
    $("#name" + mPlace).css("font-weight","bold");
    $("#batPs").val(parseInt(mPlace) - 1);
    $("#batEndPs").mPlace;
}

function updateBoard()
{
    $.getJSON("http://localhost:9999/LIVEBROADCAST/function/data.php?mode=7" , function(json)
    {
        $.each (json.player , function (i,item)
        {
            var mPlace = item.mPlace;
            var mDriverName = item.mDriverName;
            var mID = item.mID;
            $("#name" + mPlace).html(mDriverName);
            $("#onTrack" + mPlace).attr("onclick","camChange(" + mID + "," + mPlace + ",4)")
            $("#onBoard" + mPlace).attr("onclick","camChange(" + mID + "," + mPlace + ",1)")
            $("#hood" + mPlace).attr("onclick","camChange(" + mID + "," + mPlace + ",5)")
            $("#p" + mPlace).show();
        });
    });
    setTimeout ("updateBoard()",500);
}



</script>
</head>
<body style="font-size:12px" onload='updateBoard()'>
<?php
error_reporting(E_WARNING | E_ERROR);
$db = mysql_connect("127.0.0.1:8888", "root","root");
mysql_select_db("broadcast", $db);
mysql_query("set names gbk");

date_default_timezone_set('PRC');
$sessionID = 2;
$sql = "select * from SRFC_LIVE where sessionID = $sessionID";
$ok = mysql_query($sql);
$row = mysql_fetch_array($ok);


$raceName = $row['raceName'];
$raceRound = $row['raceRound'];
$raceppl = $row['raceppl'];
$mode = $row['mode'];//�ۿ�ģʽ
$battlePs = $row['battlePs'];//��������
$battleEndPs = $row['battleEndPs'];
$lastWatchID = $row['lastWatchID'];//����˳λ��
$isTimeOn = $row['isTimeOn'];
$isWeatherOn = $row['weatherOn'];
$isBadgeOn = $row['badgeOn'];
$isBattleOn = $row['battlePs'];
$isAutoOn = $row['autoOn'];
$isInfoOn = $row['infoOn'];


$playerCount = 0;
for ($p = 1;$p <= 30 ;$p++)
{
    if ($p<10) $showp = "0$p"; else $showp = $p;
    echo "<p id ='p$p' style='display:none'>";
    echo $showp;
    echo "&nbsp;<button id = 'onTrack$p'>����</button><button id = 'onBoard$p'>����</button><button id = 'hood$p'>����</button>&nbsp;<span id ='name$p' class='name'></span><span>";
    echo "</p>";
}

?>
<form action='' method = 'post'>
����:<input type = 'text' name='raceName' value='<?=$raceName?>'><br>
����:<input type = 'text' name='raceRound' value='<?=$raceRound?>'><br>
<div style='text-align: right'>
    <input type = 'submit' name = 'submit' value = '�ύ'>
    <input type = 'submit' name = 'submit2' value = '�����˼�¼'> <input type = 'submit' name = 'submit2' value = '�������Ȧ'> <br><br>
</div>
<hr>
<input type = 'radio' name = 'watch_mode' value="START" <?php if ($mode == 'START') echo "checked";?>>START
<input type = "submit"  name = "submit3" value="NEXT">
��ǰ��ʾ�����У�<?=$lastWatchID?> <hr>
<input type = 'radio' name = 'watch_mode' value="QUAL" <?php if ($mode == 'QUAL') echo "checked";?>>QUAL
<input type = 'radio' name = 'watch_mode' value="RACE" <?php if ($mode == 'RACE') echo "checked";?>>RACE
<input type = 'radio' name = 'watch_mode' value="POS" <?php if ($mode == 'POS') echo "checked";?>>POS

<input type = 'submit' name = 'submit2' value = '�ύ'><br><br><hr>
����������ʼ��<input type = "text" name = "batPs" value="<?=$battlePs?>" style='width:30px' id = 'batPs'>
�������ν�����<input type = "text" name = "batEndPs" value="<?=$battleEndPs?>" style='width:30px' id = 'batEndPs'><br><br>

<?
if ($isBattleOn > 0) $str = "�ر�����";else $str ="������";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
<hr>
<?
if ($isTimeOn == 1) $str = "�ر�ʱ��";else $str = "��ʱ��";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
<hr>
<?
if ($isBadgeOn == 1) $str = "�رճ���"; else $str = "�򿪳���";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
<hr>
<?
if ($isWeatherOn == 1) $str = "�ر�����"; else $str = "������";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
<hr>
<?
if ($isInfoOn == 1) $str = "�ر���Ϣ"; else $str = "����Ϣ";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
<hr>
<?
if ($isAutoOn == 1) $str = "�ر��Զ�"; else $str = "���Զ�";
?>
<input type = "submit" name = "submit2" value='<?=$str?>'>
</form>
</body>
</html>

<?php
error_reporting(E_WARNING | E_ERROR);
$db = mysql_connect("127.0.0.1:8888", "root","root");
mysql_select_db("broadcast", $db);

date_default_timezone_set('PRC');
$sessionID = 2;

if ($_POST)
{
    $submit = $_POST['submit'];

    if ($submit == '�ύ')
    {
        $raceName = $_POST['raceName'];
        $raceRound = $_POST['raceRound'];
        $sql = "update SRFC_LIVE set raceName = '$raceName', raceRound = '$raceRound' where sessionID=$sessionID";
        mysql_query($sql);
        $sql = "delete from srfc_grid";
        mysql_query($sql);//�������˳λ���Ա�������α仯
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '�ύ'){
        $mode = $_POST['watch_mode'];

        $sql = "update SRFC_LIVE set mode = '$mode',lastWatchID = 1 where sessionID=$sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '������'){
        $battlePs = $_POST['batPs'];
        $battleEndPs = $_POST['batEndPs'];
        $sql = "update SRFC_LIVE set battlePs = '$battlePs',battleEndPs = '$battleEndPs' where sessionID=$sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�ر�����'){
        $mode = $_POST['watch_mode'];
        $sql = "update SRFC_LIVE set battlePs = '0', mode = '$mode' where sessionID=$sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '��ʱ��'){
        $sql = "update SRFC_LIVE set isTimeOn = 1 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�ر�ʱ��'){
        $sql = "update SRFC_LIVE set isTimeOn = 0 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�򿪳���'){
        $sql = "update SRFC_LIVE set badgeOn = 1 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�رճ���'){
        $sql = "update SRFC_LIVE set badgeOn = 0 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '������'){
        $sql = "update SRFC_LIVE set weatherOn = 1 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�ر�����'){
        $sql = "update SRFC_LIVE set weatherOn = 0 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '�ر���Ϣ'){
        $sql = "update SRFC_LIVE set infoOn = 0 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '����Ϣ'){
        $sql = "update SRFC_LIVE set infoOn = 1 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '���Զ�'){
        $sql = "update SRFC_LIVE set autoOn = 1 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
    if ($_POST['submit2'] == '�ر��Զ�'){
        $sql = "update SRFC_LIVE set autoOn = 0 where sessionID = $sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '�����˼�¼'){
        $sql = "TRUNCATE en_player_in_row";//�����˼�¼��
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }

    if ($_POST['submit2'] == '�������Ȧ'){
        $sql = "update auto_watch_record set bestlap = 9999";//�������Ȧ��¼Ϊ9999
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
   

    if ($_POST['submit3'] == 'NEXT'){
        $sql = "update SRFC_LIVE set lastWatchID = lastWatchID + 2 where sessionID=$sessionID";
        mysql_query($sql);
        exit ("<META HTTP-EQUIV=REFRESH CONTENT='0;URL=control.php'>");
    }
}


