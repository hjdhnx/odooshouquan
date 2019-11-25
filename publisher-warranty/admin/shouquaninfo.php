<?php
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
if(isset($_POST['dbuuid']))
//如果包含卡密类型
{
$dbuuid=htmlentities($_POST['dbuuid']); //卡类型
if($dbuuid == "all" || $dbuuid == "*"){
$mysql="SELECT * FROM `odoos` WHERE 1";
}
else{
$mysql="SELECT * FROM `odoos` WHERE `dbuuid` = '$dbuuid'";
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
echo "<td>$row[8]</td>";
echo "<td>$row[12]</td>";
echo "<td>$row[14]</td>";
echo "<td>$row[15]</td>";
echo "</tr>";
}
}
else{
    echo '无权访问该文件！';
}
?>