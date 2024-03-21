<?php
// Učitavanje XML datoteke
$xml = simplexml_load_file('LV2.xml');

// Provjera je li uspješno učitana datoteka
if ($xml === false) {
    die('Greška prilikom učitavanja XML datoteke.');
}

// Prikaz profila svake osobe
$profiles = [];
foreach ($xml->record as $person) {
    $id = (string) $person->id;
    $ime = (string) $person->ime;
    $prezime = (string) $person->prezime;
    $email = (string) $person->email;
    $slika = (string) $person->slika;
    $zivotopis = (string) $person->zivotopis;

    // Dodavanje profila u listu profila
    $profiles[] = [
        'ime' => $ime,
        'prezime' => $prezime,
        'email' => $email,
        'slika' => $slika,
        'zivotopis' => $zivotopis
    ];
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil osobe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2; /* Sivkasta pozadina */
        }
        .profile {
            margin-bottom: 20px;
            background-color: #ffffff; /* Bijela pozadina profila */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sjenčanje */
        }
        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .profile p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <?php foreach ($profiles as $profile): ?>
        <div class="profile">
            <img src="<?php echo $profile['slika']; ?>" alt="<?php echo $profile['ime'] . ' ' . $profile['prezime']; ?>">
            <div>
                <p><strong>Ime:</strong> <?php echo $profile['ime']; ?></p>
                <p><strong>Prezime:</strong> <?php echo $profile['prezime']; ?></p>
                <p><strong>Email:</strong> <?php echo $profile['email']; ?></p>
                <p><strong>Životopis:</strong> <?php echo $profile['zivotopis']; ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>