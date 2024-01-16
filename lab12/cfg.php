<?php
    // *************************************
    // *       plik konfiguracyjny         *
    // *************************************
    // Dane logowania
    $login = "wiktor";
    $pass = "wiktor123";

    // Konfiguracja SMTP
    $config = array(
        'smtp_host' => 'smtp.gmail.com',
        'smtp_auth' => true,
        'smtp_username' => 'x',
        'smtp_password' => 'x',
        'smtp_secure' => 'tls',
        'smtp_port' => 587,
    );

    // Dane bazy danych    
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';

    // Połączenie z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass);
    
    // Sprawdzenie połączenia z bazą danych
    if(!$link)
    {
        echo '<b>przerwane połączenie</b>';
    } 

    // Wybór bazy danych
    if(!mysqli_select_db($link, $baza))
    {
        echo 'nie wybrano bazy';
    }
?>