<?php
    include("./cfg.php");

    function FormularzLogowania()
    {
        $wynik = '
        <div class="logowanie">
            <h1 class="heading">Panel CMS:</h1>
            <div class="logowanie">
                <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_RRI'].'">
                    <table class="logowanie">
                        <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
        ';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $loginEmail = isset($_POST['login_email']) ? $_POST['login_email'] : '';
            $loginPassword = isset($_POST['login_pass']) ? $_POST['login_pass'] : '';
        
            $_SESSION['login'] = $loginEmail;
            $_SESSION['pass'] = $loginPassword;

            header("Location: index.php?idp=admin");
        }

        return $wynik;
    }

    function ListaPodstron($connection)
    {
        $query = "SELECT * FROM page_list LIMIT 100";
        
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Błąd zapytania: " . mysqli_error($connection));
        }

        $output = '<table><tr><td><div class="lista_podstron"><table>';

        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<tr><td>' . $row['id'] . ' ' . $row['page_title'] . '</td></tr>';
        }

        $output .= '</table></div></td><td>';
        $output .= '<div class="panel_admina"><form method="post" action="' . $_SERVER["PHP_SELF"] . '">
        <label for="operacja">Operacja:</label>
        <select name="operacja">
            <option value="edytuj">Edytuj</option>
            <option value="dodaj">Dodaj</option>
            <option value="usun">Usuń</option>
        </select><br>
        
        <label for="id">ID:</label>
        <input type="text" name="id">
        
        <label for="nowy_tytul">Nowy Tytuł:</label>
        <input type="text" name="nowy_tytul" >
        
        <label for="nowa_zawartosc">Nowa Zawartość:</label>
        <textarea name="nowa_zawartosc" rows="4" ></textarea>
        
        <label for="nowy_status">Nowy Status:</label>
        <input type="text" name="nowy_status" >
        
        <input type="submit" value="Wykonaj Operację">
        </form></div></td></tr></table>';

        mysqli_free_result($result);

        return $output;
    }

    function edytujWpis($id, $nowyTytul, $nowaZawartosc, $nowyStatus) {
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $baza = 'moja_strona';
    
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $baza);
    
        if ($conn->connect_error) {
            die("Połączenie nieudane: " . $conn->connect_error);
        }

        $id = $conn->real_escape_string($id);
        $nowyTytul = $conn->real_escape_string($nowyTytul);
        $nowaZawartosc = $conn->real_escape_string($nowaZawartosc);
        $nowyStatus = $conn->real_escape_string($nowyStatus);
    
        $query = "UPDATE page_list SET page_title='$nowyTytul', page_content='$nowaZawartosc', status='$nowyStatus' WHERE id='$id'";
    
        if ($conn->query($query) === TRUE) {
            echo "Wpis został zaktualizowany pomyślnie.";
        } else {
            echo "Błąd podczas aktualizacji wpisu: " . $conn->error;
        }
    
        $conn->close();
    }

    function dodajNowyWpis($tytul, $zawartosc, $status) {
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $baza = 'moja_strona';
    
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $baza);

        if ($conn->connect_error) {
            die("Połączenie nieudane: " . $conn->connect_error);
        }
    
        if (!mysqli_select_db($conn, $baza)) {
            die("Nie udało się wybrać bazy danych: " . mysqli_error($conn));
        }
    
        $tytul = $conn->real_escape_string($tytul);
        $zawartosc = $conn->real_escape_string($zawartosc);
        $status = $conn->real_escape_string($status);

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$zawartosc', '$status')";
    
        if ($conn->query($query) === TRUE) {
            echo "Nowy wpis został dodany pomyślnie.";
        } else {
            echo "Błąd podczas dodawania nowego wpisu: " . $conn->error;
        }

        $conn->close();
    }

    function usunWpis($id) {
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $baza = 'moja_strona';
    
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $baza);
    
        if ($conn->connect_error) {
            die("Połączenie nieudane: " . $conn->connect_error);
        }

        if (!mysqli_select_db($conn, $baza)) {
            die("Nie udało się wybrać bazy danych: " . mysqli_error($conn));
        }
    
        $id = $conn->real_escape_string($id);
    
        $query = "DELETE FROM page_list WHERE id='$id'";
    
        if ($conn->query($query) === TRUE) {
            echo "Wpis został usunięty pomyślnie.";
        } else {
            echo "Błąd podczas usuwania wpisu: " . $conn->error;
        }
    
        $conn->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['operacja'])) {
        $operacja = $_POST['operacja'];
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $nowyTytul = isset($_POST['nowy_tytul']) ? $_POST['nowy_tytul'] : '';
        $nowaZawartosc = isset($_POST['nowa_zawartosc']) ? $_POST['nowa_zawartosc'] : '';
        $nowyStatus = isset($_POST['nowy_status']) ? $_POST['nowy_status'] : '';
    
        switch ($operacja) {
            case 'edytuj':
                edytujWpis($id, $nowyTytul, $nowaZawartosc, $nowyStatus);
                break;
            case 'dodaj':
                dodajNowyWpis($nowyTytul, $nowaZawartosc, $nowyStatus);
                break;
            case 'usun':
                usunWpis($id);
                break;
            default:
                echo "Nieznana operacja";
        }
    }

    function Wyloguj()
    {
        session_destroy();
    }
?>

