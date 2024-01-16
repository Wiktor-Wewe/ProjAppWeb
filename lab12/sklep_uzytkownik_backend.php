<?php

class Produkt {
    public $id;
    public $tytul;
    public $cena_netto;
    public $podatek_vat;
    public $zdjecie;

    public function __construct($id, $tytul, $cena_netto, $podatek_vat, $zdjecie) {
        $this->id = $id;
        $this->tytul = $tytul;
        $this->cena_netto = $cena_netto;
        $this->podatek_vat = $podatek_vat;
        $this->zdjecie = $zdjecie;
    }

    public function cenaBrutto() {
        return $this->cena_netto * (1 + $this->podatek_vat);
    }
}



class Koszyk {
    private $produkty = [];

    public function dodajProdukt($produkt, $ilosc) {
        if (!isset($_SESSION['koszyk'])) {
            $_SESSION['koszyk'] = [];
        }
    
        $produktId = $produkt->id;
    
        if (isset($_SESSION['koszyk'][$produktId])) {
            // Produkt już istnieje w koszyku, aktualizuj ilość
            $_SESSION['koszyk'][$produktId]['ilosc'] += $ilosc;
        } else {
            // Dodaj nowy produkt do koszyka
            $_SESSION['koszyk'][$produktId] = [
                'produkt' => $produkt,  // Zapisz obiekt produktu
                'ilosc' => $ilosc,
            ];
        }
    
        $this->produkty = $_SESSION['koszyk'];
    }

    public function usunProdukt($produktId) {
        if (isset($_SESSION['koszyk'][$produktId])) {
            unset($_SESSION['koszyk'][$produktId]);
        }
    }

    public function edytujIlosc($produktId, $nowaIlosc) {
        if (isset($_SESSION['koszyk'][$produktId])) {
            $_SESSION['koszyk'][$produktId]['ilosc'] = $nowaIlosc;
        }
    }

    public function zliczWartosc()
    {
        $suma = 0;

        foreach ($this->produkty as $item) {
            $produkt = $item['produkt'];
            $ilosc = $item['ilosc'];

            // Dodaj do sumy cenę brutto pomnożoną przez ilość
            if ($produkt instanceof Produkt) {
                $suma += $ilosc * $produkt->cenaBrutto();
            } elseif (is_array($produkt)) {
                // Dostosuj sposób obliczania ceny brutto dla tablicy
                $suma += $ilosc * ($produkt['cena_netto'] * (1 + $produkt['podatek_vat']));
            }
        }

        return $suma;
    }


    public function wyczyscKoszyk()
    {
        $_SESSION['koszyk'] = [];
    }
}