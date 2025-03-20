<?php
session_start();
define("ENCRYPTION_KEY", md5('Ovo je tajni kljuc 123'));

if (!isset($_GET['file'])) {
    die("Datoteka nije odabrana.");
}

$file = $_GET['file'];

if (!file_exists($file)) {
    die("Datoteka ne postoji.");
}

// Učitavanje sadržaja
$encryptedContent = file_get_contents($file);
$cipher = "AES-256-CBC";
$ivLength = openssl_cipher_iv_length($cipher);

// Razdvajanje IV-a i kriptiranog sadržaja
$iv = substr($encryptedContent, 0, $ivLength);
$encryptedData = substr($encryptedContent, $ivLength);

// Dekriptiranje
$decryptedData = openssl_decrypt($encryptedData, $cipher, ENCRYPTION_KEY, 0, $iv);

if (!$decryptedData) {
    die("Greška pri dekripciji.");
}

// Postavljanje zaglavlja za preuzimanje
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file, '.enc') . '"');
echo $decryptedData;
?>
