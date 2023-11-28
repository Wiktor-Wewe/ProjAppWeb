<?php
    session_start();
    $value = 1;
    $_SESSION["newsession"]=$value;

    $nr_indeksu = '164439';
    $nrGrupy = '4';

    echo 'Wiktor Wewersajtys '.$nr_indeksu.' grupa '.$nrGrupy.' <br>';
    echo 'Zastosowanie metody include() <br><br>';

    include 'student.php';
    echo "Mój kolega ma na imię $imie $nazwisko <br>";

    $text = require_once('sometext.php');
    echo "Jakiś tam tekst: $text<br>";

    if ($imie == 'Adrian') {
        echo "Adrian to miły chłopak.";
    }
    else if($imie == "Kuba"){
        echo "Kuba ma fajny plecak.";
    }
    else{
        echo "Mam dużo kolegów.";
    }
    echo "<br><br>";

    $kasztan = 2;
    if(isset($_GET["kasztany"])){
        $kasztan = $_GET["kasztany"];
    }

    # https://reqbin.com/ <- testowanie online np post
    if(isset($_POST["kasztany"])){
        $k = $_POST["kasztany"];
        echo "Dziękuję za $k kasztanów! <br>";
    }

    switch($kasztan){
        case 1:
            echo "Masz aktualnie $kasztan kasztan w kieszeni.";
            break;
        case 2:
            echo "Masz aktualnie $kasztan kasztany w kieszeni.";
            break;
        case 3:
            echo "Masz aktualnie $kasztan kasztany w kieszeni.";
            break;
        case 4:
            echo "Masz aktualnie $kasztan kasztany w kieszeni.";
            break;
        default:
            echo "Masz aktualnie $kasztan kasztanów w kieszeni.";
            break;
    }
    echo "<br>";

    $i = 1;
    echo "Bomba zaczęła tykać!<br>";
    while($i<=10){
        echo "$i ... ";
        $i++;
    }
    echo "BOOOOM!<br><br>";

    echo "Ooo nie!! To druga bomba!<br>";
    for($i=1; $i<=10; $i++){
        echo "$i ... ";
    }
    echo "KOLEJNE BOOOOM!<br><br>";
    
?>