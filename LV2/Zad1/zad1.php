<?php

function backupDatabase($host, $username, $password, $database, $outputFile) {
    // Kreiranje MySQL konekcije
    $conn = new mysqli($host, $username, $password, $database);

    // Provjera konekcije
    if ($conn->connect_error) {
        die("Greška pri povezivanju s bazom podataka: " . $conn->connect_error);
    }

    // Otvaranje fajla za backup
    $file = fopen($outputFile, 'w');
    if (!$file) {
        die("Ne mogu otvoriti datoteku za pisanje: $outputFile");
    }

    try {
        // Dohvati sve tablice
        $tables = $conn->query("SHOW TABLES;");
        if (!$tables) {
            throw new Exception("Ne mogu dohvatiti tabele: " . $conn->error);
        }

        while ($table = $tables->fetch_array(MYSQLI_NUM)) {
            $tableName = $table[0];

            // Dohvati nazive stupaca
            $columnsResult = $conn->query("SHOW COLUMNS FROM $tableName;");
            if (!$columnsResult) {
                throw new Exception("Greška pri dohvaćanju stupaca tabele $tableName: " . $conn->error);
            }

            $columnNames = [];
            while ($columnInfo = $columnsResult->fetch_assoc()) {
                $columnNames[] = "`" . $columnInfo['Field'] . "`";
            }

            // Dohvati podatke iz tablice
            $rows = $conn->query("SELECT * FROM $tableName;");
            if (!$rows) {
                throw new Exception("Greška pri dohvaćanju podataka iz $tableName: " . $conn->error);
            }

            // Piši INSERT upite u fajl
            while ($row = $rows->fetch_array(MYSQLI_NUM)) {
                $values = array_map(function($value) use ($conn) {
                    return "'" . $conn->real_escape_string($value) . "'";
                }, $row);

                $columns = implode(', ', $columnNames);
                $valuesStr = implode(', ', $values);
                fwrite($file, "INSERT INTO `$tableName` ($columns)\nVALUES ($valuesStr);\n");
            }
        }

        echo "Backup baze podataka spremljen u: $outputFile\n";

    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    } finally {
        fclose($file);
        $conn->close();
    }
}

function compressFile($inputFile, $outputFile) {
    // Provjeri da li file postoji prije čitanja
    if (!file_exists($inputFile)) {
        die("Ne postoji file za kompresiju: $inputFile");
    }

    try {
        $content = file_get_contents($inputFile);
        if ($content === false) {
            throw new Exception("Greška pri čitanju datoteke: $inputFile");
        }

        $compressed = gzencode($content, 9);
        if ($compressed === false) {
            throw new Exception("Greška pri kompresiji datoteke: $inputFile");
        }

        if (file_put_contents($outputFile, $compressed) === false) {
            throw new Exception("Ne mogu spremiti sažetu datoteku: $outputFile");
        }

        echo "Datoteka uspješno sažeta: $outputFile\n";

    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

// Parametri baze
$host = "localhost";
$username = "root";
$password = "";
$database = "radovi";

// Putanje do fajlova
$backupFile = __DIR__ . '/backup_' . date('Y-m-d_H-i-s') . '.txt';
$compressedFile = $backupFile . '.gz';

// Pozivanje funkcija
backupDatabase($host, $username, $password, $database, $backupFile);
compressFile($backupFile, $compressedFile);

// Obriši originalni .txt fajl nakon kompresije
if (file_exists($compressedFile)) {
    unlink($backupFile);
}

?>
