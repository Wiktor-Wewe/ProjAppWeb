<?php
    function PokazPodstrone($id)
    {
        $id_clear = htmlspecialchars($id);
 
        include("./cfg.php");

        // Użycie prepared statement do zapytania SQL
        $query = "SELECT * FROM page_list WHERE id = ? LIMIT 1";

        // Sprawdzenie, czy prepared statement zostało poprawnie utworzone
        $stmt = mysqli_prepare($link, $query);
        
        // Użycie funkcji bind_param do bezpiecznego przekazania wartości
        mysqli_stmt_bind_param($stmt, 's', $id_clear);

        // Wykonanie prepared statement
        mysqli_stmt_execute($stmt);
    
        // Pobranie wyników z zapytania
        $result = mysqli_stmt_get_result($stmt);

        // Pobranie pojedynczego wiersza
        $row = mysqli_fetch_array($result);

        // Sprawdzenie, czy znaleziono stronę
        if (empty($row['id'])) {
            $web = '[nie_znaleziono_strony]';
        } else {
            $web = $row['page_content'];
        }

        // Zamknięcie połączenia z bazą danych
        mysqli_close($link);

        return $web;
    }
?>