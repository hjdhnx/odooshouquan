<?php
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
if(isset($_POST['katype']))
//如果包含卡密类型
{
$katype=htmlentities($_POST['katype']); //卡类型
if($katype != "all"){
switch($katype){
    case "天":
        $time_space = 24*3600;
        break;
    case "周":
        $time_space = 7*24*3600;
        break;
    case "月":
        $time_space = 30*24*3600;
        break;
    case "年":
        $time_space = 365*24*3600;
        break;
    default :
        $time_space = 365*24*3600;
}
$mysql="SELECT * FROM `enterprisecodes` WHERE `time_space` = $time_space";
}
else{
$mysql="SELECT * FROM `enterprisecodes`";
}
$ret = mysql_query($mysql);
//$row=mysql_fetch_array($ret);
$rows=mysql_affected_rows($conn);//获取行数
$colums=mysql_num_fields($ret);//获取列数
echo "共计".$rows."行 ".$colums."列{分隔符}";

while($row=mysql_fetch_row($ret)){
echo "<tr class='success'>";
echo "<td>$row[0]</td>";
echo "<td>$row[3]</td>";
echo "<td>$row[4]</td>";
echo "<td>$row[5]</td>";
echo "<td>$row[6]</td>";
echo "</tr>";
}

}
else{
    echo '无权访问该文件！';
}
?>