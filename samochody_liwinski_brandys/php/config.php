<?php
function polaczenieZBaza()
{
    define('SERWER', 'localhost');
    define('NAZWA_UZYTKOWNIKA', 'root');
    define('HASLO', '');
    define('NAZWA_BAZY', 'wypozyczalnia');
    $mysqli = mysqli_connect(SERWER, NAZWA_UZYTKOWNIKA, HASLO, NAZWA_BAZY);
    if ($mysqli === false) {
        die("Nie udało się połączyć z MySQL: " . mysqli_connect_error());
    }
    return $mysqli;
}

function pobierzSamochody()
{
    $mysqli = polaczenieZBaza();
    $result = mysqli_query($mysqli, "SELECT * FROM samochody WHERE czy_dostepny = 1;");
    while ($row = mysqli_fetch_array($result)) {
        echo '<div class="box">';
        echo '<div class="box-img">';
        echo '<img src="' . $row['image'] . '" alt="">';
        echo '</div>';
        echo '<p>' . $row['rok_produkcji'] . ' &bull; ' . $row['przebieg'] . 'km &bull; ' . $row['pojemnosc'] . ' cm&sup3; &bull; ' . $row['rodzaj_paliwa'] . ' &bull; ' . $row['moc'] . ' KM</p>';
        echo '<h3>' . $row['nazwa'] . '</h3>';
        echo '<h2>' . $row['cena_wypozyczenia_za_dobe'] . 'zł<span> /dobę</span></h2>';
        echo '<input type="radio" name="samochod" id="samochod" value="' . $row['id'] . '"> <label>Wypożycz teraz!</label>';
        echo '</div>';
    }
}

function cenaDoba($id)
{
    $mysqli = polaczenieZBaza();
    $result = mysqli_query($mysqli, "SELECT cena_wypozyczenia_za_dobe FROM samochody WHERE id = {$id};");
    $row = mysqli_fetch_array($result);
    return $row['cena_wypozyczenia_za_dobe'];
}

function brakDostepnosci()
{
    $mysqli = polaczenieZBaza();
    $dzisiaj = date("Y-m-d");
    $result = mysqli_query($mysqli, "SELECT samochod_id, data_wypozyczenia FROM wypozyczenie;");
    while ($row = mysqli_fetch_array($result)) {
        if ($dzisiaj >= $row['data_wypozyczenia']) {
            $result = mysqli_query($mysqli, "UPDATE samochody SET czy_dostepny = 0 WHERE id = {$row['samochod_id']};");
        }
    }
}

function przywrocenieDostepnosci()
{
    $mysqli = polaczenieZBaza();
    $dzisiaj = date("Y-m-d");
    $result = mysqli_query($mysqli, "SELECT samochod_id, data_oddania FROM wypozyczenie;");
    while ($row = mysqli_fetch_array($result)) {
        if ($dzisiaj > $row['data_oddania']) {
            $result = mysqli_query($mysqli, "UPDATE samochody SET czy_dostepny = 1 WHERE id = {$row['samochod_id']};");
        }
    }
}
