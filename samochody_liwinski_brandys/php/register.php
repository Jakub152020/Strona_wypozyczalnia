<?php

require_once "config.php";
require_once "session.php";
$error = '';
$mysqli = polaczenieZBaza();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    
    $imie = trim($_POST['imie']);
    $nazwisko = trim($_POST['nazwisko']);
    $email = trim($_POST['email']);
    $haslo = trim($_POST['haslo']);
    $potwierdz_haslo = trim($_POST['potwierdz_haslo']);
    $haslo_hash = password_hash($haslo, PASSWORD_BCRYPT);

    if ($query = mysqli_query($mysqli, "SELECT * FROM klienci WHERE email = {'$email'};")) {
        if ($query->num_rows > 0) {
            $error .= '<p class="error">Ten adres e-mail jest już zarejestrowany!</p>';
        } else {

            if (strlen($haslo) < 6) {
                $error .= '<p class="error">Hasło musi mieć conajmniej 6 znaków!</p>';
            }

            if (empty($potwierdz_haslo)) {
                $error .= '<p class="error">Proszę potwierdź hasło!</p>';
            } else {
                if (empty($error) && ($haslo != $potwierdz_haslo)) {
                    $error .= '<p class="error">Hasła nie są takie same!</p>';
                }
            }
            if (empty($error)) {
                if ($insertQuery = mysqli_query($mysqli, "INSERT INTO klienci (imie, nazwisko, email, haslo) VALUES ({'$imie'}, {'$nazwisko'}, {'$email'}, {'$haslo_hash'});")) {
                    $error .= '<p class="error">Rejestracja przebiegła pomyślnie!</p>';
                } else {
                    $error .= '<p class="error">Coś poszło źle!</p>';
                }
            }
        }
    }
    $query->close();
    $insertQuery->close();
    mysqli_close($mysqli);
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href=".\css\register_style.css"> -->
</head>

<body>
    <header>


    </header>
    <div class="logon">

        <h2>Rejestracja</h2>
        <p class="form-description">Wypełnij ten formularz, aby stworzyć konto</p>
        <?php echo $error; ?>
        
        <form id="utworz_konto" method="POST" action="register.php">
            <div class="form-group">
                <label>Imię:<br></label> <input type="textbox" name="imie" class="form-control"
                    placeholder="Podaj imię..." required></br>
            </div>
            <div class="form-group">
                <label>Nazwisko:<br></label> <input type="textbox" name="nazwisko" class="form-control"
                    placeholder="Podaj nazwisko..." required></br>
            </div>
            <div class="form-group">
                <label>E-mail:<br></label> <input type="email" name="email" class="form-control"
                    placeholder="Podaj e-mail..." required></br>
            </div>
            <div class="form-group">
                <label>Hasło:<br></label> <input type="password" name="haslo" class="form-control"
                    placeholder="Podaj hasło..." required></br>
            </div>
            <div class="form-group">
                <label>Potwierdź hasło:<br></label> <input type="password" name="potwierdz_haslo" class="form-control"
                    placeholder="Podaj hasło..." required></br>
            </div>
            <button type="submit" form="utworz_konto" name="submit" class="submit-btn">Załóż konto</button>
            <p class="form-description">Masz już konto? <a class="form-link" href="login.php">Zaloguj się tutaj</a>.</p>
        </form>

    </div>

</body>

</html>