# roller-coaster-system

## Założenia

## Uruchomienie

## Wymagania

## Wdrożenie

- W środowisku produkcyjnym należy zależności instalować z użyciem komendy "composer install --no-dev"
- Po uruchomieniu aplikacji należy zaktualizować zależności o konkretne wersje w celu zapewnienia stabilności działania
  aplikacji. Mowa tutaj o zależnościach systemowych ('Nginx', 'PHP', 'REDIS) oraz zależnościach aplikacji.

## Testy
- Testy jednostkowe i integracyjne można uruchomić komendą `phpunit` z katalogu głównego aplikacji. Badź z poziomu IDE
  (np. "PhpStorm" - tutaj należy skonfigurować ustawienia Interpretera i PHPUNIT).

## Dokumentacja
 - Basic auth development u: dev  p: password

### TODO

- walidacja danych wejściowych godziny

- POST /api/coasters/:coasterId/wagons walidacja danych wejściowych wagonów (czy istnieje kolejka)
- aktualizacja kolejki gorskiej  przy zmianie dodac walidacje na to ze nie mozna zmodyfikować długości trasy
- wersja developerska niedostępna dla osób z zewnątrz (basic auth)