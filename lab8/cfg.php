<?php
    $login = "wiktor";
    $pass = "wiktor123";

    $config = array(
        'smtp_host' => 'smtp.gmail.com',
        'smtp_auth' => true,
        'smtp_username' => 'x',
        'smtp_password' => 'x ',
        'smtp_secure' => 'tls',
        'smtp_port' => 587,
    );

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    $link = mysqli_connect($dbhost, $dbuser, $dbpass);
    if(!$link) echo '<b>przerwane połączenie</b>';
    if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';
?>