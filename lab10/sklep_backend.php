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

?>
