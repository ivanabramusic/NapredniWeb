<?php
$files = glob('uploads/*.enc');

echo "<h2>Popis kriptiranih datoteka:</h2>";

foreach ($files as $file) {
    $originalName = basename($file, '.enc'); // Izbaci .enc ekstenziju
    echo "<p><a href='decrypt.php?file=" . urlencode($file) . "'>Dekriptiraj i preuzmi: $originalName</a></p>";
}
?>
