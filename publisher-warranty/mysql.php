<?php
$servername = "localhost";
$username = "root";
$password = "hjd124579";
$database = "odoo";
//建立数据库链接

$conn = mysql_connect($servername, $username, $password);
if (!$conn) {
    die('链接失败');
    }
mysql_select_db($database,$conn);
?>
