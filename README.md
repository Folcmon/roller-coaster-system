# roller-coaster-system

## Założenia

## Uruchomienie

## Wymagania

## Wdrożenie

- W środowisku produkcyjnym należy zależności instalować z użyciem komendy "composer install --no-dev"
- Po uruchomieniu aplikacji należy zaktualizować zależności o konkretne wersje w celu zapewnienia stabilności działania
  aplikacji. Mowa tutaj o zależnościach systemowych ('Nginx', 'PHP', 'REDIS) oraz zależnościach aplikacji.

## Testy

- Testy jednostkowe i integracyjne można uruchomić komendą `phpunit` z katalogu głównego aplikacji. Bądź z poziomu IDE
  (np. "PhpStorm" - tutaj należy skonfigurować ustawienia Interpretera i PHPUNIT).

## Dokumentacja

- w Katalogu demoImages znajdują sie przykładowe zrzuty ekranu z działania aplikacji
- Basic auth development u: dev p: password
- monitorowanie kolejki w katalogu głównym aplikacji uruchamiamy komendę: `php spark monitor:coasters'

## API: Zarządzanie personelem i statusami

### Pobierz liczbę dostępnych pracowników

**GET /api/coasters/personnel**

**Odpowiedź:**

```json
{
  "personnel": 42
}
```

### Ustaw liczbę dostępnych pracowników

**PUT /api/coasters/personnel**

**Body:**

```json
{
  "personnel": 42
}
```

**Walidacja:**

- `personnel` – liczba całkowita >= 0 (wymagane)

**Odpowiedź:**

```json
{
  "status": "zaktualizowano"
}
```

### Statusy systemowe (braki/nadmiary)

**GET /api/coasters/status**

**Odpowiedź:**

```json
[
  {
    "coaster": "coaster_abc",
    "type": "brak",
    "message": "Brakuje 2 pracowników"
  },
  {
    "coaster": "coaster_abc",
    "type": "brak",
    "message": "Brakuje 1 wagonów"
  },
  {
    "coaster": "coaster_xyz",
    "type": "nadmiar",
    "message": "Nadmiar 1 pracowników"
  }
]
```

### Status dla konkretnej kolejki

**GET /api/coasters/{coasterId}/status**

**Odpowiedź:**

```json
[
  {
    "coaster": "coaster_abc",
    "type": "brak",
    "message": "Brakuje 2 pracowników"
  },
  {
    "coaster": "coaster_abc",
    "type": "brak",
    "message": "Brakuje 1 wagonów"
  }
]
```

### Walidacja i obsługa błędów

- Wszystkie pola wymagane są walidowane (np. liczba personelu nie może być ujemna, brakujące pola zwracają błąd 400).
- Przykład błędu:

```json
{
  "error": "Brak wymaganej liczby personelu"
}
```

### TODO

- walidacja danych wejściowych godziny

- POST /api/coasters/:coasterId/wagons walidacja danych wejściowych wagonów (czy istnieje kolejka)
- aktualizacja kolejki górskiej przy zmianie dodać walidacje na to ze nie można zmodyfikować długości trasy