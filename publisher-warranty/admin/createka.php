<?php 
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
if(isset($_POST['kanum']) and isset($_POST['time_space']) and isset($_POST['nb_users'])){//生成数量、时间戳、数据库最大用户数
if($_POST['kanum']>10000){
	echo "卡密生成失败!单次数量不得超过1w";
	exit;
}
function makeCardPassword(){
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)]
            .strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return  $d;
}
$kanum = intval($_POST['kanum']);
$time_space = $_POST['time_space'];
$nb_users = $_POST['nb_users'];
//echo $kanum."---".$time_space."---".$nb_users."<br/>";
$sqlcode="alter table `enterprisecodes` AUTO_INCREMENT=1";
mysql_query($sqlcode);
for($i=0;$i<$kanum;$i++){
  //echo $i."<br/>";
  $enterprisecode = makeCardPassword();
  $sqlcode ="INSERT INTO `enterprisecodes` (`id` ,`created_at` ,`updated_at` ,`enterprisecode` ,`nb_users` ,`time_space` ,`dbuuid`)
    VALUES (NULL, NOW(), NOW(), '$enterprisecode', '$nb_users', '$time_space', NULL);";
  //echo $sql_code."<br/>";
  mysql_query($sqlcode);
}
echo "卡密生成完毕！<br/>共计生成".$kanum."张卡";
}
else{
echo "无权访问该文件！";  
}
?>