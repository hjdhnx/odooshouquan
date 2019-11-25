<?php
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("mysql.php");
//echo "hello odoo!\n"; //打印测试页面
if(isset($_POST['arg0']) and isset($_POST['action']))
{
$arg0=$_POST['arg0'];
$action=$_POST['action'];
//echo $arg0."\n".$action;  //打印参数和动作
if($action != "update"){
    exit;
}
get_json($arg0);
}

function get_json($json){//获取json文本里面的值
    $json = str_replace("'",'"',$json);
    if(!is_string($json)){
        $json = strval($json); //判断不是字符串就进行转字符串
    }
    $json_dict = json_decode($json); //将json字符串转为json字典数组对象
    $dbuuid = $json_dict->dbuuid; //数据库唯一标识
    $nbr_users = $json_dict->nbr_users; //最大用户数
    $nbr_active_users = $json_dict->nbr_active_users;
    $nbr_share_users = $json_dict->nbr_share_users;
    $nbr_active_share_users = $json_dict->nbr_active_share_users;
    $dbname = $json_dict->dbname; //数据库的名称
    $db_create_date = $json_dict->db_create_date; //数据库创建时间
    $version = $json_dict->version; //版本信息
    $language = $json_dict->language; //使用的语言
    $web_base_url = $json_dict->web_base_url; //网址
    $apps = $json_dict->apps; //已安装的模块
    $enterprise_code = $json_dict->enterprise_code; //企业注册码
    $id = $json_dict->id;
    $company_name = $json_dict->name; //公司名称
    $company_email = $json_dict->email; //公司邮箱
    $company_phone = $json_dict->phone; //公司电话
    //echo "\n".$dbuuid."\n"."$enterprise_code"; //打印数据库标识和企业注册码
    $row = check_record($dbuuid); //拿到查出来的结果
    if(!$row){
        //echo "\n新建记录";
        new_record($id,$dbuuid,$nbr_users,$nbr_active_users,$nbr_share_users,$nbr_active_share_users,$dbname,$db_create_date,$version,$language,$web_base_url,$apps,$enterprise_code,$company_name, $company_email, $company_phone);
    }
    else{
        //echo "\n更新记录";
        $expiration_date = $row['expiration_date']; //旧的到期时间
        //$expiration_reason = $row['expiration_reason']; //旧的过期原因
        //$enterprise_code = $row['$enterprise_code']; //旧的企业注册码
        $db_create_date = $row['db_create_date']; //旧的数据库创建时间
        //echo "\n成功获取 旧的到期时间,旧的数据库创建时间".$expiration_date."   ".$db_create_date;
        update_record($id,$dbuuid,$nbr_users,$nbr_active_users,$nbr_share_users,$nbr_active_share_users,$dbname,$db_create_date,$version,$language,$web_base_url,$apps,$enterprise_code,$company_name,$company_email, $company_phone,$expiration_date);
    }
}

function check_record($dbuuid){
    $check="SELECT * FROM  `odoos` WHERE  `dbuuid` =  '$dbuuid'"; //查dbuuid判断是否存在
    $ret=mysql_query($check); //执行
    $row=mysql_fetch_array($ret); //得到查询结果
    if ($row["dbuuid"]==$dbuuid){
        return $row; //返回查到的这条记录
    }
    return False;
}
function new_record($id,$dbuuid,$nbr_users,$nbr_active_users,$nbr_share_users,$nbr_active_share_users,$dbname,$db_create_date,$version,$language,$web_base_url,$apps,$enterprise_code,$company_name, $company_email, $company_phone){
    $apps = implode(",", $apps); //将数组转为字符串存到数据库
    $nextmonth = time()+30*24*60*60;  //获取下个月今天的时间戳(30天24小时60分钟60秒) 31536000 是365天
    $expiration_date = date('Y-m-d h:i:s', $nextmonth); //时间戳转时间
    $sqlcode = "INSERT INTO `odoos` 
    (`id`, `created_at`, `updated_at`, `dbuuid`, `nbr_users`, `nbr_active_users`, `nbr_share_users`, `nbr_active_share_users`, `dbname`, `db_create_date`, `version`, `language`, `web_base_url`, `apps`, `enterprise_code`, `expiration_date`, `expiration_reason`, `company_name`, `company_email`, `company_phone`)
    VALUES (NULL, NOW(), NULL, '$dbuuid', '$nbr_users', '$nbr_active_users', '$nbr_share_users', '$nbr_active_share_users', '$dbname', '$db_create_date', '$version', '$language', '$web_base_url', '$apps', '$enterprise_code', '$expiration_date', 'trial', '$company_name', '$company_email', '$company_phone');";
    //echo $sqlcode;
    $ret=mysql_query($sqlcode);
}

function update_record($id,$dbuuid,$nbr_users,$nbr_active_users,$nbr_share_users,$nbr_active_share_users,$dbname,$db_create_date,$version,$language,$web_base_url,$apps,$enterprise_code,$company_name, $company_email, $company_phone,$expiration_date){
    $check = "SELECT * FROM `enterprisecodes` WHERE `enterprisecode` = '$enterprise_code' AND `dbuuid` is null;"; //根据数据库标识和企业注册码查该卡增加时间(dbuuid=Null说明未使用)
    $ret=mysql_query($check); //执行
    $row=mysql_fetch_array($ret); //得到查询结果
    //echo "\n".$check;
    if($row["enterprisecode"]==$enterprise_code and $row["enterprisecode"]!=""){//查到相等且不是空卡密
        $time_space = intval($row["time_space"]); //得到这张卡的时间戳并转为整数
        //echo "\n此卡有效。并且时间戳为".$time_space;
        //echo "expiration_date: ".$expiration_date;
        if(strtotime($expiration_date)<time()){//如果已经过期了，再点注册
        $new_expiration_space = time() + $time_space; //授权时间戳为当前时间戳加卡密增加时间戳
        $new_expiration_date = date('Y-m-d h:i:s', $new_expiration_space); //时间戳转时间
        //echo "\n过期了注册".$new_expiration_date;
        }
        else{ //如果现在数据库还没过期就点注册
            $new_expiration_space = strtotime($expiration_date) + $time_space; //新授权到期时间戳
            $new_expiration_date = date('Y-m-d h:i:s', $new_expiration_space); //时间戳转时间
            //echo "\n没过期点注册".$new_expiration_date;
        }
        
        $apps = implode(",", $apps); //将数组转为字符串存到数据库
        $sql_code ="UPDATE `odoos` SET `updated_at` = NOW(),
            `nbr_users` = '$nbr_users',
            `nbr_active_users` = '$nbr_active_users',
            `nbr_share_users` = '$nbr_share_users',
            `nbr_active_share_users` = '$nbr_active_share_users',
            `web_base_url` = '$web_base_url',
            `apps` = '$apps',
            `enterprise_code` = '$enterprise_code',
            `expiration_date` = '$new_expiration_date',
            `dbname` = '$dbname',
            `version` = '$version',
            `language` = '$language',
            `expiration_reason` = '$expiration_reason',
            `company_name` = '$company_name',
            `company_email` = '$company_email',
            `company_phone` = '$company_phone' WHERE `dbuuid` ='$dbuuid';";
        $ret=mysql_query($sql_code);
        $sql_code = "UPDATE `enterprisecodes` SET `dbuuid` = '$dbuuid',
        `updated_at` = NOW() WHERE `enterprisecode` ='$enterprise_code';";
        //echo $sql_code;
        $ret=mysql_query($sql_code);
        $back_code = "{'messages': [], 'contracts': [], 'enterprise_info': {'expiration_date': '$new_expiration_date', 'enterprise_code': '$enterprise_code'}}";
        echo $back_code;  //返回执行码
    }
    else{
        $row["enterprisecode"]."无效";
        echo "注册失败"; //返回执行码
    }
}
?>