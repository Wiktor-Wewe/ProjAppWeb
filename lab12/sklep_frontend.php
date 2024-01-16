<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Zarządzania Kategoriami</title>
</head>
<body>

<?php

require_once 'sklep_backend.php';

$systemKategorii = new SystemZarzadzaniaKategoriami('localhost', 'root', '', 'moja_strona');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj'])) {
        $nazwa = $_POST['nazwa'];
        $matka = $_POST['matka'];
        $systemKategorii->dodajKategorie($nazwa, $matka);
    } elseif (isset($_POST['usun'])) {
        $kategoriaId = $_POST['kategoria_id'];
        $systemKategorii->usunKategorie($kategoriaId);
    } elseif (isset($_POST['edytuj'])) {
        $kategoriaId = $_POST['kategoria_id'];
        $nowaNazwa = $_POST['nowa_nazwa'];
        $systemKategorii->edytujKategorie($kategoriaId, $nowaNazwa);
    }
}

?>

<h2>Formularz Zarządzania Kategoriami</h2>

<form method="post" action="">
    <label for="nazwa">Nazwa Kategorii:</label>
    <input type="text" name="nazwa" required>

    <label for="matka">ID Matki (dla podkategorii):</label>
    <input type="number" name="matka" value="0">

    <button type="submit" name="dodaj">Dodaj Kategorię</button>
</form>

<h2>Lista Kategorii</h2>

<?php
$systemKategorii->pokazKategorie();
?>

<h2>Drzewo Kategorii</h2>

<?php
$systemKategorii->generujDrzewoKategorii();
?>

<h2>Usuwanie Kategorii</h2>

<form method="post" action="">
    <label for="kategoria_id">ID Kategorii do Usunięcia:</label>
    <input type="number" name="kategoria_id" required>

    <button type="submit" name="usun">Usuń Kategorię</button>
</form>

<h2>Edycja Kategorii</h2>

<form method="post" action="">
    <label for="kategoria_id">ID Kategorii do Edycji:</label>
    <input type="number" name="kategoria_id" required>

    <label for="nowa_nazwa">Nowa Nazwa:</label>
    <input type="text" name="nowa_nazwa" required>

    <button type="submit" name="edytuj">Edytuj Kategorię</button>
</form>

<?php

require_once 'sklep_backend.php';

$systemProduktow = new SystemZarzadzaniaProduktami('localhost', 'root', '', 'moja_strona');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj_produkt'])) {
        $tytul = $_POST['tytul'];
        $opis = $_POST['opis'];
        $cenaNetto = $_POST['cena_netto'];
        $podatekVat = $_POST['podatek_vat'];
        $iloscSztuk = $_POST['ilosc_sztuk'];
        $statusDostepnosci = $_POST['status_dostepnosci'];
        $kategoria = $_POST['kategoria'];
        $gabarytProduktu = $_POST['gabaryt_produktu'];

        // Sprawdzenie, czy plik został przesłany
        if (isset($_FILES['zdjecie']) && $_FILES['zdjecie']['error'] === UPLOAD_ERR_OK) {
            $zdjecie = file_get_contents($_FILES['zdjecie']['tmp_name']);
        } else {
            $zdjecie = null;
        }

        $systemProduktow->dodajProdukt($tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $zdjecie);
    } elseif (isset($_POST['usun_produkt'])) {
        $produktId = $_POST['produkt_id'];
        $systemProduktow->usunProdukt($produktId);
    } elseif (isset($_POST['edytuj_produkt'])) {
        $produktId = $_POST['produkt_id'];
        $tytul = $_POST['nowy_tytul'];
        $opis = $_POST['nowy_opis'];
        $cenaNetto = $_POST['nowa_cena_netto'];
        $podatekVat = $_POST['nowy_podatek_vat'];
        $iloscSztuk = $_POST['nowa_ilosc_sztuk'];
        $statusDostepnosci = $_POST['nowy_status_dostepnosci'];
        $kategoria = $_POST['nowa_kategoria'];
        $gabarytProduktu = $_POST['nowy_gabaryt_produktu'];

        // Sprawdzenie, czy plik został przesłany
        if (isset($_FILES['nowe_zdjecie']) && $_FILES['nowe_zdjecie']['error'] === UPLOAD_ERR_OK) {
            $noweZdjecie = file_get_contents($_FILES['nowe_zdjecie']['tmp_name']);
        } else {
            $noweZdjecie = null;
        }

        $systemProduktow->edytujProdukt($produktId, $tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $noweZdjecie);
    }
}

?>

<h2>Formularz Zarządzania Produktami</h2>

<form method="post" action="" enctype="multipart/form-data">
    <label for="tytul">Tytuł Produktu:</label>
    <input type="text" name="tytul" required>

    <label for="opis">Opis Produktu:</label>
    <textarea name="opis"></textarea>

    <label for="cena_netto">Cena Netto:</label>
    <input type="number" name="cena_netto" step="0.01" required>

    <label for="podatek_vat">Podatek VAT (%):</label>
    <input type="number" name="podatek_vat" step="0.01" required>

    <label for="ilosc_sztuk">Ilość Dostępnych Sztuk:</label>
    <input type="number" name="ilosc_sztuk" required>

    <label for="status_dostepnosci">Status Dostępności:</label>
    <select name="status_dostepnosci">
        <option value="Dostępny">Dostępny</option>
        <option value="Niedostępny">Niedostępny</option>
    </select>

    <label for="kategoria">Kategoria:</label>
    <input type="text" name="kategoria" required>

    <label for="gabaryt_produktu">Gabaryt Produktu:</label>
    <input type="text" name="gabaryt_produktu" required>

    <label for="zdjecie">Zdjęcie Produktu:</label>
    <input type="file" name="zdjecie">

    <button type="submit" name="dodaj_produkt">Dodaj Produkt</button>
</form>

<h2>Lista Produktów</h2>

<?php
$systemProduktow->pokazProdukty();
?>

<h2>Usuwanie Produktu</h2>

<form method="post" action="">
    <label for="produkt_id">ID Produktu do Usunięcia:</label>
    <input type="number" name="produkt_id" required>

    <button type="submit" name="usun_produkt">Usuń Produkt</button>
</form>

<h2>Edycja Produktu</h2>

<form method="post" action="" enctype="multipart/form-data">
    <label for="produkt_id">ID Produktu do Edycji:</label>
    <input type="number" name="produkt_id" required>

    <label for="nowy_tytul">Nowy Tytuł:</label>
    <input type="text" name="nowy_tytul" required>

    <label for="nowy_opis">Nowy Opis:</label>
    <textarea name="nowy_opis"></textarea>

    <label for="nowa_cena_netto">Nowa Cena Netto:</label>
    <input type="number" name="nowa_cena_netto" step="0.01" required>

    <label for="nowy_podatek_vat">Nowy Podatek VAT (%):</label>
    <input type="number" name="nowy_podatek_v


</body>
</html>
