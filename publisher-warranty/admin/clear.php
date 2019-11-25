<?php
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
if(isset($_POST['clear']) and isset($_POST['type']))
//查看是否包含清理操作和清理类型
{
$type=htmlentities($_POST['type']); //清除类型
if($type=="all"){//清空所有卡
    $sqlcode="DELETE FROM `enterprisecodes` WHERE 1;";
    $ret = mysql_query($sqlcode); 
    $sqlcode="alter table `enterprisecodes` AUTO_INCREMENT=1";
    mysql_query($sqlcode);
}
else if($type=="used"){ //清空废卡
    $sqlcode="DELETE FROM `enterprisecodes` WHERE `dbuuid` is not null;";
    $ret = mysql_query($sqlcode); 
    $sqlcode="alter table `enterprisecodes` AUTO_INCREMENT=1";
    mysql_query($sqlcode);
}
echo '指定卡密记录清除完毕！';
}
else{
    echo '无权访问该文件！';
}
?>