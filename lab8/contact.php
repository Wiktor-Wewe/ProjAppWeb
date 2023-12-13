<?php
// contact.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ścieżka do pliku autoload.php w katalogu PHPMailer
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

include('cfg.php');

class Kontakt {
    private $config; // Dodaj prywatne pole do przechowywania konfiguracji

    // Przekazanie konfiguracji do konstruktora
    public function __construct($config) {
        $this->config = $config;
    }

    public function PokazKontakt() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'wyslij') {
            $this->WyslijMailKontakt($this->config);
        } else {
            // Tutaj umieść kod HTML formularza kontaktowego
            echo "<h2>Formularz Kontaktowy</h2>";
            echo "<form method='post' action='contact.php?action=wyslij'>";
            echo "Imię: <input type='text' name='imie'><br>";
            echo "Email: <input type='email' name='email'><br>";
            echo "Wiadomość: <textarea name='wiadomosc'></textarea><br>";
            echo "<input type='submit' name='action' value='Wyślij'>";
            echo "</form>";
        }
    }

    public function WyslijMailKontakt($config) {
        //if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'wyslij') {
            $imie = $_POST['imie'];
            $email = $_POST['email'];
            $wiadomosc = $_POST['wiadomosc'];
    
            // Adres email, na który będzie wysłana wiadomość
            $adres_odbiorcy = 'mrstpl@wp.pl';
    
            // Temat wiadomości
            $temat = 'Nowa wiadomość od ' . $imie;
    
            // Treść wiadomości
            $wiadomosc_email = "Imię: $imie\n";
            $wiadomosc_email .= "Email: $email\n";
            $wiadomosc_email .= "Wiadomość:\n$wiadomosc";
    
            // Nagłówki maila
            $naglowki = 'From: ' . $email . "\r\n" .
                        'Reply-To: ' . $adres_odbiorcy . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
    
                        $mail = new PHPMailer(true);

                        try {
                            // Ustawienia serwera SMTP
                            $mail->isSMTP();
                            $mail->Host = $config['smtp_host'];
                            $mail->SMTPAuth = $config['smtp_auth'];
                            $mail->Username = $config['smtp_username'];
                            $mail->Password = $config['smtp_password'];
                            $mail->SMTPSecure = $config['smtp_secure'];
                            $mail->Port = $config['smtp_port'];
                    
                            // Wysyłka maila
                            $sukces = $mail->send();
                    
                            if ($sukces) {
                                echo "Mail został wysłany na adres: $adres_odbiorcy";
                            } else {
                                echo "Wystąpił błąd podczas wysyłania maila.";
                            }
                        } catch (Exception $e) {
                            echo "Błąd: " . $mail->ErrorInfo;
                        }
        //}
    }

    public function PrzypomnijHaslo() {
        // Tutaj umieść kod do obsługi przypomnienia hasła
        echo "<h2>Przypomnienie Hasła</h2>";
        // ...
    }
}

// Utwórz instancję klasy Kontakt
$kontakt = new Kontakt($config);

// Sprawdź, czy istnieje akcja i wywołaj odpowiednią metodę
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'pokaz':
            $kontakt->PokazKontakt();
            break;
        case 'wyslij':
            $kontakt->WyslijMailKontakt($config);
            break;
        case 'przypomnij':
            $kontakt->PrzypomnijHaslo();
            break;
        default:
            echo "Nieznana akcja";
    }
} else {
    echo "Brak akcji";
}
?>
