<?php
include_once 'iRadovi.php';

class DiplomskiRadovi implements iRadovi {
    private $conn;
    private $naziv_rada;
    private $tekst_rada;
    private $link_rada;
    private $oib_tvrtke;

    public function __construct($conn) {
        $this->conn = $conn; 
    }

    public function create($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke) {
        $this->naziv_rada = $naziv_rada;
        $this->tekst_rada = $tekst_rada;
        $this->link_rada = $link_rada;
        $this->oib_tvrtke = $oib_tvrtke;
    }

    // Spremanje radova u bazu podataka
    public function save($radovi)
    {
        try {
            // Početak transakcije
            $this->conn->begin_transaction();

            // SQL upit za unos
            $stmt = $this->conn->prepare("INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) 
                                         VALUES (?, ?, ?, ?)");

            // Prolazimo kroz svaki rad u nizu
            foreach ($radovi as $rad) {
                // Vežemo parametre
                $stmt->bind_param("ssss", $rad['naziv_rada'], $rad['tekst_rada'], $rad['link_rada'], $rad['oib_tvrtke']);
                
                // Izvršavamo upit
                $stmt->execute();
            }

            // Potvrda transakcije
            $this->conn->commit();
            echo "Radovi su uspješno spremljeni!";
        } catch (mysqli_sql_exception $e) {
            // U slučaju greške, poništavamo transakciju
            $this->conn->rollback();
            echo "Greška pri spremanju radova: " . $e->getMessage();
        }
    }

    public function read()
    {
        try {
            // SQL upit za dohvat podataka
            $stmt = $this->conn->prepare("SELECT * FROM diplomski_radovi");
            $stmt->execute();
            
            // Dohvaćanje svih podataka
            $result = $stmt->get_result();

            // Ispis svih radova
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Naziv rada: " . $row['naziv_rada'] . "<br>";
                    echo "Tekst rada: " . $row['tekst_rada'] . "<br>";
                    echo "Link rada: " . $row['link_rada'] . "<br>";
                    echo "OIB tvrtke: " . $row['oib_tvrtke'] . "<br><br>";
                }
            } else {
                echo "Nema podataka u tablici!";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Greška pri dohvaćanju radova: " . $e->getMessage();
        }
    }

    
}
?>
