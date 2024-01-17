<?php
session_start();

// Załaduj klasę zarządzania produktami
require_once 'sklep_backend.php';
require_once 'sklep_uzytkownik_backend.php';

// Stwórz instancję systemu zarządzania produktami
$systemProduktow = new SystemZarzadzaniaProduktami('localhost', 'root', '', 'moja_strona');

// Utwórz instancję koszyka
$koszyk = new Koszyk();


// Obsługa dodawania do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj_do_koszyka'])) {
        // Kod dodawania do koszyka
        $produktId = $_POST['produkt_id'];
    $ilosc = isset($_POST['ilosc']) ? intval($_POST['ilosc']) : 1;

    // Pobierz informacje o produkcie z bazy danych
    $produkt = $systemProduktow->pobierzProdukt($produktId);

    // Dodaj produkt do koszyka
    if ($produkt) {
        $koszyk->dodajProdukt($produkt, $ilosc);
    }
    } elseif (isset($_POST['czysc_koszyk'])) {
        // Wyczyść koszyk 
        $koszyk->wyczyscKoszyk();
    }
}

// Obsługa dodawania do koszyka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_do_koszyka'])) {
    
}

$produkty = [];
$produktyRaw = $systemProduktow->pobierzWszystkieProdukty();

foreach ($produktyRaw as $produktRaw) {
    $produkty[] = new Produkt(
        $produktRaw->id,
        $produktRaw->tytul,
        $produktRaw->cena_netto,
        $produktRaw->podatek_vat,
        $produktRaw->zdjecie,
        'jpg'  // Stałe rozszerzenie pliku
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Internetowy</title>
</head>
<body>

<h2>Wszystkie Artykuły</h2>

<?php foreach ($produkty as $produkt) : ?>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <h3><?= $produkt->tytul; ?></h3>
        <?php if ($produkt->zdjecie !== null) : ?>
            <?php
                // Sprawdź rozszerzenie pliku obrazu
                $extension = pathinfo($produkt->tytul, PATHINFO_EXTENSION);

                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    // Dodaj inne rozszerzenia, jeśli są używane
                ];

                $imageType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';

                $base64Image = base64_encode($produkt->zdjecie);
                $imageSrc = "data:{$imageType};base64,{$base64Image}";
            ?>
            <img src="<?= $imageSrc; ?>" alt="<?= $produkt->tytul; ?>" style="max-width: 100%;">
        <?php else : ?>
            <p>Brak obrazka</p>
        <?php endif; ?>
        <p>Cena netto: <?= $produkt->cena_netto; ?> PLN</p>
        <p>Podatek VAT: <?= $produkt->podatek_vat; ?></p>
        <form method="post" action="">
            <label for="ilosc">Ilość sztuk:</label>
            <input type="number" name="ilosc" value="1" min="1" required>
            <input type="hidden" name="produkt_id" value="<?= $produkt->id; ?>">
            <button type="submit" name="dodaj_do_koszyka">Dodaj do koszyka</button>
        </form>
    </div>
<?php endforeach; ?>


<h2>Zawartość Koszyka</h2>

<?php if (!empty($_SESSION['koszyk'])) : ?>
    <table border="1" style="width: 50%;">
        <tr>
            <th>Produkt</th>
            <th>Ilość</th>
            <th>Cena jednostkowa (brutto)</th>
            <th>Wartość</th>
        </tr>
        <?php foreach ($_SESSION['koszyk'] as $item) : ?>
            <?php $produkt = $item['produkt']; ?>
            <?php $ilosc = $item['ilosc']; ?>
            <tr>
                <td>
                    <?php
                    if (is_object($produkt)) {
                        echo $produkt->tytul;
                    } elseif (is_array($produkt)) {
                        echo $produkt['tytul'];
                    }
                    ?>
                </td>
                <td><?= $ilosc; ?></td>
                <td>
                    <?php
                    if (is_object($produkt) && method_exists($produkt, 'cenaBrutto')) {
                        echo $produkt->cenaBrutto();
                    } elseif (is_array($produkt)) {
                        // Dostosuj sposób obliczania ceny brutto dla tablicy
                        echo $produkt['cena_netto'] * (1 + $produkt['podatek_vat']);
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (is_object($produkt) && method_exists($produkt, 'cenaBrutto')) {
                        echo $ilosc * $produkt->cenaBrutto();
                    } elseif (is_array($produkt)) {
                        // Dostosuj sposób obliczania wartości dla tablicy
                        echo $ilosc * ($produkt['cena_netto'] * (1 + $produkt['podatek_vat']));
                    }
                    ?> PLN
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Suma:</strong></td>
            <td><?= $koszyk->zliczWartosc(); ?> PLN</td>
        </tr>
    </table>
    <!-- Guzik do czyszczenia koszyka -->
    <form method="post" action="">
        <button type="submit" name="czysc_koszyk">Wyczyść koszyk</button>
    </form>

<?php else : ?>
    <p>Koszyk jest pusty.</p>
<?php endif; ?>


</body>
</html>
