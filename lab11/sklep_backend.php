<?php

class SystemZarzadzaniaKategoriami {
    private $conn;

    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->utworzTabele();
    }

    public function utworzTabele() {
        $sql = "
            CREATE TABLE IF NOT EXISTS Kategorie (
                id INT AUTO_INCREMENT PRIMARY KEY,
                matka INT DEFAULT 0,
                nazwa VARCHAR(255) NOT NULL
            )
        ";

        $this->conn->query($sql);
    }

    public function dodajKategorie($nazwa, $matka = 0) {
        $sql = "INSERT INTO Kategorie (matka, nazwa) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $matka, $nazwa);
        $stmt->execute();
        $stmt->close();
    }

    public function usunKategorie($kategoriaId) {
        $sql = "DELETE FROM Kategorie WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $kategoriaId);
        $stmt->execute();
        $stmt->close();
    }

    public function edytujKategorie($kategoriaId, $nowaNazwa) {
        $sql = "UPDATE Kategorie SET nazwa = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $nowaNazwa, $kategoriaId);
        $stmt->execute();
        $stmt->close();
    }

    public function pokazKategorie() {
        $sql = "SELECT * FROM Kategorie";
        $result = $this->conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . ", Matka: " . $row["matka"] . ", Nazwa: " . $row["nazwa"] . "<br>";
        }
    }

    public function generujDrzewoKategorii() {
        $drzewo = $this->pobierzDrzewoKategorii();
        
        echo '<ul>';
        $this->wyswietlDrzewoKategorii($drzewo);
        echo '</ul>';
    }
    
    private function pobierzDrzewoKategorii($matka = 0) {
        $sql = "SELECT * FROM Kategorie WHERE matka = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $matka);
        $stmt->execute();
        $result = $stmt->get_result();
        $drzewo = [];
    
        while ($row = $result->fetch_assoc()) {
            $row['podkategorie'] = $this->pobierzDrzewoKategorii($row['id']);
            $drzewo[] = $row;
        }
    
        $stmt->close();
        return $drzewo;
    }
    
    private function wyswietlDrzewoKategorii($drzewo) {
        foreach ($drzewo as $kategoria) {
            echo '<li>';
            echo $kategoria['nazwa'];
            if (!empty($kategoria['podkategorie'])) {
                echo '<ul>';
                $this->wyswietlDrzewoKategorii($kategoria['podkategorie']);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}

class SystemZarzadzaniaProduktami {
    private $conn;

    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->utworzTabele();
    }

    public function utworzTabele() {
        $sql = "
        CREATE TABLE IF NOT EXISTS Produkty (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tytul VARCHAR(255) NOT NULL,
            opis TEXT,
            data_utworzenia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            data_modyfikacji TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            data_wyg TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 30 DAY),
            cena_netto DECIMAL(10, 2) NOT NULL,
            podatek_vat DECIMAL(4, 2) NOT NULL,
            ilosc_dostepnych_sztuk INT NOT NULL,
            status_dostepnosci VARCHAR(20) NOT NULL,
            kategoria INT NOT NULL,
            gabaryt_produktu VARCHAR(50) NOT NULL,
            zdjecie BLOB,
            FOREIGN KEY (kategoria) REFERENCES Kategorie(id)
        );
        ";
    
        $this->conn->query($sql);
    }

    public function dodajProdukt($tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $zdjecie = null) {
        $sql = "INSERT INTO Produkty (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssddisssb", $tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $zdjecie);
        $stmt->execute();
        $stmt->close();
    }

    public function usunProdukt($produktId) {
        $sql = "DELETE FROM Produkty WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $produktId);
        $stmt->execute();
        $stmt->close();
    }

    public function edytujProdukt($produktId, $tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $zdjecie = null) {
        $sql = "UPDATE Produkty SET tytul = ?, opis = ?, cena_netto = ?, podatek_vat = ?, ilosc_dostepnych_sztuk = ?, status_dostepnosci = ?, kategoria = ?, gabaryt_produktu = ?, zdjecie = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssddisssbi", $tytul, $opis, $cenaNetto, $podatekVat, $iloscSztuk, $statusDostepnosci, $kategoria, $gabarytProduktu, $zdjecie, $produktId);
        $stmt->execute();
        $stmt->close();
    }

    public function pokazProdukty() {
        $sql = "SELECT * FROM Produkty";
        $result = $this->conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . ", Tytuł: " . $row["tytul"] . ", Cena netto: " . $row["cena_netto"] . ", Ilość dostępnych sztuk: " . $row["ilosc_dostepnych_sztuk"] . "<br>";
        }
    }
}

?>
