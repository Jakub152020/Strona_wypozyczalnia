DROP DATABASE IF EXISTS wypozyczalnia;
CREATE DATABASE wypozyczalnia;
USE wypozyczalnia;

CREATE TABLE klienci (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  imie VARCHAR(255) NOT NULL,
  nazwisko VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE KEY,
  haslo VARCHAR(255) NOT NULL
);

CREATE TABLE samochody (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nazwa VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  moc INT NOT NULL,
  przebieg INT NOT NULL,
  pojemnosc INT NOT NULL,
  rodzaj_paliwa VARCHAR(255) NOT NULL,
  rok_produkcji VARCHAR(4) NOT NULL,
  czy_dostepny BIT NOT NULL,
  cena_wypozyczenia_za_dobe INT NOT NULL
);

CREATE TABLE wypozyczenie (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  samochod_id INT UNSIGNED NOT NULL,
  klient_id INT UNSIGNED NOT NULL,
  data_wypozyczenia DATE NOT NULL,
  data_oddania DATE NOT NULL,
  koszt INT UNSIGNED NOT NULL,
  FOREIGN KEY (samochod_id) REFERENCES samochody(id),
  FOREIGN KEY (klient_id) REFERENCES klienci(id)
);
