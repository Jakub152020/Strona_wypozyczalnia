<?php
require_once "config.php";
session_start();
$error = '';
$koszt = '';
$mysqli = polaczenieZBaza();
if (!isset($_SESSION["userid"]) || $_SESSION["userid"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $klient = $_SESSION["userid"];
    $samochod = $_POST['samochod'];
    $wypozyczenie = date('Y-m-d', strtotime($_POST['wypozyczenie']));
    $oddanie = date('Y-m-d', strtotime($_POST['oddanie']));
    $dzisiaj = date("Y-m-d");
    $roznica_dni = $oddanie - $wypozyczenie;
    $ilosc_dni = round($roznica_dni / (60 * 60 * 24));
    $cena_doba = cenaDoba($samochod);

    if (!isset($_POST['samochod'])) 
        $error .= '<p class="error">Nie wybrałeś żadnego samochodu!</p>';
    
    if ($wypożyczenie <= $dzisiaj) {
        $error .= '<p class="error">Data wypożyczenia nie może być mniejsza lub równa dzisiejszej!</p>';
        if ($oddanie <= $wypozyczenie) {
            $error .= '<p class="error">Data oddania nie może być mniejsza lub równa dacie wypożyczenia!</p>';
        }
    }
    if (empty($error)) {
        $koszt = $ilosc_dni * $cena_doba;
        if ($result = mysqli_query($mysqli, "INSERT INTO wypozyczenie (samochod_id, klient_id, data_wypozyczenia, data_oddania, koszt)  VALUES ({'$samochod'}, {'$klient'}, {'$wypozyczenie'}, {'$oddanie'}, {'$koszt'});")) {
            $error .= '<p class="error">Wypożyczono auto!</p>';
            brakDostepnosci($samochod);
        } else {
            $error .= '<p class="error">Coś poszło źle!</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj <?php echo $_SESSION["name"]; ?></title>
    <!-- <link rel="stylesheet" href=".\css\welcome_style.css"> -->
</head>

<body>
    <h1>Cześć, <strong><?php echo $_SESSION["name"]; ?></strong> Witaj na naszej stronie</h1>

    <h2>Wypożyczenie auta</h2>
    <p>Wypełnij ten formularz, aby wypożyczyć auto</p>
    <?php echo $error; ?>
    <form id="wypozyczenie" method="POST" action="welcome.php">
        <div class="form-group">
            <label>Wybierz auto: </label>
                <?php include_once "config.php";
                pobierzSamochody(); ?>
        </div>
        <div class="form-group">
            <label>Data wypożyczenia:</label> <input type="date" name="wypozyczenie" value="<?php echo date('Y-m-d'); ?>" class="form-control" required></br>
        </div>
        <div class="form-group">
            <label>Data oddania:</label> <input type="date" name="oddanie" value="<?php echo date('Y-m-d'); ?>" class="form-control" required></br>
        </div>
        <?php echo "{$koszt} zł"; ?>
        <button type="submit" form="wypozyczenie" name="submit" class="submit-btn">Wypożycz</button>
    </form>

    <a href="logout.php" class="btn-submit" role="button" aria-presses="true">Wyloguj się</a>
</body>

</html>