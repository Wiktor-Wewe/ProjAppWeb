<?php
    function PokazPodstrone($id)
    {
        $id_clear = htmlspecialchars($id);
 
        include("./cfg.php");

        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM page_list WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 's', $id_clear);
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);

        // Check if the page is found
        if (empty($row['id'])) {
            $web = '[nie_znaleziono_strony]';
        } else {
            $web = $row['page_content'];
        }

        // Close the connection
        mysqli_close($link);

        return $web;
    }
?>