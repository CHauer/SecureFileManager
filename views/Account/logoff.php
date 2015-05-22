<?php
session_start();

$_SESSION = array();

if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-300, '/');
}
session_destroy();

if(isset($_COOKIE['SecureRememberMe']))
{
    setcookie('SecureRememberMe', 'gone', time() - 100);
}

header ("Location: /home/index");
?>