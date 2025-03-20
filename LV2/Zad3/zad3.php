<?php
/* SimpleXML parser */

// Učitaj XML datoteku
$xml = simplexml_load_file('LV2.xml') or die("Greška pri učitavanju XML-a!");

// Iteracija kroz XML
foreach ($xml->record as $person) {
    // Prikazivanje podataka osobe
    echo "<div style='border: 1px solid #ddd; padding: 15px; margin: 10px; display: flex; align-items: center;'>";

    // Profilna slika
    echo "<img src='{$person->slika}' alt='Slika osobe' style='border-radius: 50%; width: 80px; height: 80px; margin-right: 15px;'>";

    // Prikaz informacija o osobi
    echo "<div style='flex: 1;'>
            <h3>{$person->ime} {$person->prezime}</h3>
            <p><strong>Email:</strong> {$person->email}</p>
            <p><strong>Životopis:</strong> {$person->zivotopis}</p>
          </div>";
    
    echo "</div>";
}
?>
