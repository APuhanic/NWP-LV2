<?php
session_start();

// Funkcija za dekriptiranje podataka
function decryptData($data, $key)
{
    $iv = $_SESSION['iv'];
    return openssl_decrypt($data, 'AES-128-CTR', $key, 0, $iv);
}

// Provjera je li direktorij "uploads" postoji
$uploads_directory = "uploads";
if (!is_dir($uploads_directory)) {
    echo "Direktorij 'uploads' ne postoji.";
    exit;
}

// Dohvaćanje svih kriptiranih datoteka
$uploaded_files = glob($uploads_directory . '/*.{pdf,jpeg,jpg,png}', GLOB_BRACE);

if (empty($uploaded_files)) {
    echo "Nema kriptiranih datoteka za dekriptiranje.";
    exit;
}

// Prolazak kroz sve kriptirane datoteke
foreach ($uploaded_files as $uploaded_file) {

    // Čitanje sadržaja datoteke
    $encrypted_data = file_get_contents($uploaded_file);

    // Dohvaćanje dijela imena datoteke koji predstavlja originalno ime datoteke
    $file_name_parts = explode('_', basename($uploaded_file));
    if (count($file_name_parts) !== 2) {
        echo "Neispravan format imena datoteke: {$uploaded_file}.";
        continue;
    }

    $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc'); 

    // Dekriptiranje sadržaja datoteke
    $decrypted_data = decryptData($encrypted_data, $encryption_key);

    // Dohvaćanje originalnog imena datoteke
    $decrypted_file_name = "dekriptirani_" . $file_name_parts[1];

    // Spremanje dekriptiranog sadržaja u direktorij uploads
    $downloaded_file_path = $uploads_directory . "/" . $decrypted_file_name;
    if (file_put_contents($downloaded_file_path, $decrypted_data) !== false) {
        // Ispis linka za preuzimanje dekriptiranog dokumenta
        echo "<a href='{$downloaded_file_path}'>Preuzmi dekriptirani dokument ({$decrypted_file_name})</a><br>";
    } else {
        echo "Greška prilikom spremanja dekriptiranog dokumenta {$decrypted_file_name}.<br>";
    }
}
