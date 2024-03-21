<?php
session_start();

// Provjera je li forma za upload poslana
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];

    // Provjera je li uploadana datoteka slika ili PDF
    $allowed_extensions = array("pdf", "jpeg", "jpg", "png");
    // Dohvaćanje ekstenziju datoteke
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    // Ako je ekstenzija dozvoljena
    if (in_array($file_extension, $allowed_extensions)) {

        // Generiranje ključ za kriptiranje
        $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc');
        $cipher = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($cipher);

        // Generiranje inicijalizacijskog vektroa
        $iv = random_bytes($iv_length);

        // Spremanje IV u sesiju
        $_SESSION['iv'] = $iv;

        // Kriptiranje sadržaja datoteke
        $encrypted_data = openssl_encrypt(file_get_contents($file["tmp_name"]), $cipher, $encryption_key, 0, $iv);

        // Spremanje kriptiranog sadržaja u direktorij uploads
        $file_name = uniqid() . "_" . $file["name"];
        $file_path = "uploads/" . $file_name;
        // Spremanje kriptiranog sadržaja u datoteku
        file_put_contents($file_path, $encrypted_data);

        echo "Uspješno kriptiran i uploadan dokument.";
    } else {
        echo "Dozvoljeni formati su: PDF, JPEG, JPG, PNG.";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    Odaberite datoteku za upload: <input type="file" name="file">
    <input type="submit" value="Upload">
</form>