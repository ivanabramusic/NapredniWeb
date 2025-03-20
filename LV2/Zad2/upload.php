<?php
session_start();

// Postavi tajni ključ (treba ga čuvati sigurno)
define("ENCRYPTION_KEY", md5('Ovo je tajni kljuc 123'));

// Provjera je li korisnik poslao datoteku
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    // Dozvoljeni formati
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Neispravan format datoteke.");
    }

    // Učitavanje originalnog sadržaja
    $fileContent = file_get_contents($file['tmp_name']);
    
    // Generiranje nasumičnog IV-a
    $cipher = "AES-256-CBC";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    // Kriptiranje datoteke
    $encryptedContent = openssl_encrypt($fileContent, $cipher, ENCRYPTION_KEY, 0, $iv);
    
    if (!$encryptedContent) {
        die("Greška pri kriptiranju datoteke.");
    }

    // Spremanje kriptirane datoteke (IV + kriptirani sadržaj)
    $encryptedFileName = 'uploads/' . basename($file['name']) . '.enc';
    file_put_contents($encryptedFileName, $iv . $encryptedContent);

    echo "Datoteka uspješno učitana i kriptirana!";
}
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload i Kriptiraj</button>
</form>
