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

</body>
</html>
