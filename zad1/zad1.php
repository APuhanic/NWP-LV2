<?php
// Podaci za spajanje na bazu podataka
$servername = "localhost";
$username = "root";
$password = "";
$database = "radovi";

// Stvaranje veze s bazom podataka
$conn = new mysqli($servername, $username, $password, $database);

// Provjera veze
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ime datoteke za backup
$backup_file = 'backup_' . date("Y-m-d-H-i-s") . '.txt';

// SQL upit za izvlačenje podataka iz baze
$sql = "SELECT * FROM diplomski_radovi";
$result = $conn->query($sql);

// Provjera rezultata upita
if ($result->num_rows > 0) {
    // Otvori datoteku za pisanje
    $backup_handle = fopen($backup_file, 'w');

    // Iteriraj kroz rezultate i spremi ih u datoteku
    while ($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            fwrite($backup_handle, $key . ': ' . $value . PHP_EOL);
        }
        fwrite($backup_handle, '-----------------------------------------' . PHP_EOL);
    }

    // Zatvori datoteku
    fclose($backup_handle);

    // Sažmi backup datoteku
    $gzipped_backup_file = $backup_file . '.gz';
    $gzipped_content = gzencode(file_get_contents($backup_file), 9);
    file_put_contents($gzipped_backup_file, $gzipped_content);
    
    // Obriši originalnu datoteku nakon kompresije
    unlink($backup_file);

    echo "Backup uspješno spremljen i sažet kao $gzipped_backup_file";
} else {
    echo "Nema rezultata za backup.";
}

// Zatvori vezu s bazom podataka
$conn->close();
?>
