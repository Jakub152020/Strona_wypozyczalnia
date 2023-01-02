<?php
require_once "config.php";
require_once "session.php";
$mysqli = polaczenieZBaza();
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $email = trim($_POST['email']);
    $haslo = trim($_POST['haslo']);

    if (empty($email)) {
        $error .= '<p class="error">Proszę wprowadź email.</p>';
    }

    if (empty($haslo)) {
        $error .= '<p class="error">Proszę wprowadź hasło.</p>';
    }

    if (empty($error)) {
        if ($query = mysqli_query($mysqli, "SELECT * FROM klienci WHERE email = {'$email'};")) {
            if ($row = mysqli_fetch_array($result)) {
                if (password_verify($haslo, $row['haslo'])) {
                    $_SESSION["userid"] = $row['id'];
                    $_SESSION["name"] = $row['imie'];

                    header("location: welcome.php");
                    exit;
                } else {
                    $error .= '<p class="error">Hasło jest niepoprawne.</p>';
                }
            } else {
                $error .= '<p class="error">Użytkownik z tym adresem email nie istnieje.</p>.</p>';
            }
        }
        $query->close();
    }
    mysqli_close($mysqli);
}
?>


<!DOCTYPE html>
<html lang="pl">


<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href=".\css\login_style.css"> -->
</head>

<body>
    
    <div class="logon">

        <h2>Logowanie</h2>

        <p class="form-description">Wypełnij ten formularz, aby się zalogować</p>

        <?php echo $error; ?>

        <form id="zaloguj" method="POST" action="login.php">

            <div class="form-group">
                <label>E-mail:<br></label> <input type="email" name="email" class="form-control" placeholder="Podaj e-mail..." required></br>
            </div>

            <div class="form-group">
                <label>Hasło:<br></label> <input type="password" name="haslo" class="form-control" placeholder="Podaj hasło..." required></br>
            </div>

            <button type="submit" form="zaloguj" name="submit" class="submit-btn">Zaloguj</button>

            <p class="form-description">Nie masz jeszcze konta? <a class="form-link" href="register.php">Zarejestruj się tutaj</a>.</p>

        </form>
    </div>
</body>

</html>