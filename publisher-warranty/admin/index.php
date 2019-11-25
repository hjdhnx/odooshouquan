<?php 
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=utf-8"); //设置编码
ini_set( 'display_errors', 'off' );//屏蔽错误和警告提示
date_default_timezone_set('PRC');//设置中国时区
include("../mysql.php");
//echo "hello php!<br>";
session_start(); 
 //首先判断Cookie是否有记住用户信息 
 if(isset($_COOKIE['username'])) 
 { 
  $_SESSION['username']=$_COOKIE['username']; 
  $_SESSION['islogin']=1; 
 } 
 if(isset($_SESSION['islogin'])) 
 { 
  if(!isset($_GET['view']) || ($_GET['view']=="") || ($_GET['view']=="searchkami")){ //首页是一个完整的页面，其他都是拼接的html
  $string = file_get_contents("views/kamisearch.html");
  $string = str_replace("{{username}}",$_SESSION['username'],$string);
  echo $string;
  }
  else if(isset($_GET['view']) && ($_GET['view']=="searchshouquan")){
  $string = file_get_contents("views/shouquansearch.html");
  $string = str_replace("{{username}}",$_SESSION['username'],$string);
  echo $string;
  }
  else if(isset($_GET['view']) && ($_GET['view']=="createkami")){
  $string = file_get_contents("views/createkami.html");
  $string = str_replace("{{username}}",$_SESSION['username'],$string);
  echo $string;
  }
  else{
      echo "访问出错404<br/><br/><a href='./'>返回首页</a>";
  }
 } 
 else
 { //为登录 
  //echo "你还未登录，请<a href='login.html'>登录</a>"; 
  header('refresh:0;url=login.html'); 
 }
?>