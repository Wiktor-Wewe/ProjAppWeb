<?php
    // *************************************
    // *  Kod dotyczący kontaktu i maili   *
    // *************************************

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Dołączanie pliku konfiguracyjnego
    include('cfg.php');

    // Dołączanie wymaganych plików PHPMailer
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHPMailer/src/Exception.php';

    // Funkcja wyświetlająca formularz kontaktowy
    function PokazKontakt()
    {
        echo '
        <form method="post">
            <label for="imie">Imie</label>
            <input type="text" name="imie" required><br>

            <label for="email">Adres e-mail:</label>
            <input type="email" name="email" required><br>

            <label for="temat">Temat:</label>
            <input type="text" name="temat" required><br>

            <label for="wiadomosc">Wiadomość</label>
            <textarea name="wiadomosc" required></textarea><br>

            <input type="submit" name="wyslij" value="Wyslij">
        </form>
        ';
    }

    // Funkcja wysyłająca mail z formularza kontaktowego
    function WyslijMailKontakt()
    {
        global $config;

        $odbiorca = "jddisy@gmail.com";

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['wyslij'])) {
            $mail = new PHPMailer(true);
            
            // Konfiguracja PHPMailer
            try {
                $mail->SMTPDebug = 0; 
                $mail->isSMTP();
                $mail->Host = $config['smtp_host'];
                $mail->SMTPAuth = $config['smtp_auth'];
                $mail->Username = $config['smtp_username'];
                $mail->Password = $config['smtp_password'];
                $mail->SMTPSecure = $config['smtp_secure'];
                $mail->Port = $config['smtp_port'];
                
                // Ustawienia maila
                $mail->setFrom($_POST['email'], $_POST['imie']);
                $mail->addAddress($odbiorca);

                $mail->isHTML(false);
                $mail->Subject = $_POST['temat'];
                $mail->Body = $_POST['wiadomosc'];

                // Wysyłanie maila
                $mail->send();
                echo 'Wiadomość została wysłana.';
            } catch (Exception $e) {
                echo 'Błąd: Wiadomość nie została wysłana. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            echo 'Wypełnij wszystkie pola.';
        }
    }

    // Funkcja generująca formularz przypomnienia hasła
    function Zapomniane_haslo()
    {
        $wynik = '
        <div class="Zapomniane_haslo">
            <h1 class="heading">wyslij mail:</h1>
            <div class="formularzZapomniane">
                <form method="post" name="mail" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                    <table class="formularz">
                        <tr><td class="for4_t">email:</td><td><input type="text" name="emailf" class="formularzZapomniane" /></td></tr>
                        <tr><td class="for4_t">na jaki mail?:</td><td><input type="text" name="emaild" class="formularzZapomniane" /></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="x5_submit" class="formularzZapomniane" value="wyslij" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
        ';

        return $wynik;
    }

    // Funkcja obsługująca przypomnienie hasła
    function PrzypomnijHaslo()
    {
        global $link;
        echo Zapomniane_haslo();
        global $config;
        global $pass;

        if (isset($_POST['x5_submit'])) {
            $email = $_POST['emailf'];
            $emaild = $_POST['emaild'];

            $haslo = $pass;

            $mail = new PHPMailer(true);

            // Konfiguracja PHPMailer
            try {
                $mail->SMTPDebug = 2;
                $mail->isSMTP();
                $mail->Host = $config['smtp_host'];
                $mail->SMTPAuth = $config['smtp_auth'];
                $mail->Username = $config['smtp_username'];
                $mail->Password = $config['smtp_password'];
                $mail->SMTPSecure = $config['smtp_secure'];
                $mail->Port = $config['smtp_port'];

                // Ustawienia maila
                $mail->setFrom($config['smtp_username'], 'Formularz kontaktowy');
                $mail->addAddress($emaild);

                $mail->isHTML(false);
                $mail->Subject = 'Przypomnienie hasła';
                $mail->Body = 'Twoje hasło: ' . $haslo;

                // Wysyłanie maila
                $mail->send();
                echo '[wiadomosc_wyslana]';
                exit();
            } catch (Exception $e) {
                echo 'Wiadomość nie została wysłana: ' . $mail->ErrorInfo;
            }

            mysqli_close($link);
        } else {
            echo 'Wypełnij pola.';
        }
    }

    // Sprawdzenie akcji
    if(!isset($_GET['action'])){
        $_GET['action'] = 'pokaz';
    }

    if($_GET['action'] == 'pokaz'){
        PokazKontakt();
        WyslijMailKontakt();
    }
    else if($_GET['action'] == 'haslo'){
        Zapomniane_haslo();
        PrzypomnijHaslo();
    }
    else{
        $_GET['action'] = 'pokaz';
        PokazKontakt();
        WyslijMailKontakt();
    }

?>
