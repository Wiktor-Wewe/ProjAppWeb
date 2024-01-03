<?php
    // rozpoczęcie sesji
    session_start();
    
    // zamknięcie jej
    session_unset();
    session_destroy();

    // przeniesienie na index.php
    header("Location: index.php");
    exit();
?>