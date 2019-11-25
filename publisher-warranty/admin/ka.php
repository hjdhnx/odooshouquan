<?php
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
if(isset($_GET['enterprisecode']) and isset($_GET['admin']) and isset($_GET['time_space']) and isset($_GET['nb_users']))
//查看是否包含企业码,管理员密码,时间戳,最大数据库用户数
{
$enterprisecode=htmlentities($_GET['enterprisecode']); //企业码
$admin=$_GET['admin']; //管理员密码
$time_space=htmlentities($_GET['time_space']); //卡的时间戳
$nb_users = $_GET['nb_users']; //数据库最大用户
//echo $enterprisecode.'--'.$admin.'--'.$time_space.'==='.$nb_users.'<br>';
if($admin!="hjdhnx"){
    echo '非法生成卡密！';
    exit;
}
$mysql="SELECT * FROM `enterprisecodes` WHERE `enterprisecode` = '$enterprisecode'";
$ret = mysql_query($mysql);
$row=mysql_fetch_array($ret);
if($row){
    echo '此卡已存在！不可重复生成';
    exit;
}

$sql="alter table `enterprisecodes` AUTO_INCREMENT=1";
mysql_query($sql);  //序列号自动归0

$mysql="INSERT INTO `odoo`.`enterprisecodes` (`id` ,`created_at` ,`updated_at` ,`enterprisecode` ,`nb_users` ,`time_space` ,`dbuuid`)
VALUES (NULL , NOW(), NOW(), '$enterprisecode', '$nb_users', '$time_space', NULL);";

//echo $mysql;
mysql_query($mysql);
echo '卡密生成成功！';
}
else{
    echo '无权访问该文件！';
}
?>