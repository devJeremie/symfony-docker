# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**TravelDock** — a Symfony 7 / PHP 8.3 travel booking application running in Docker. Manages destinations, travels, and bookings.

## Environment

- **App**: http://localhost:8080 (Nginx → PHP-FPM)
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: `travelDock` database, user `symfony` / password `symfony_password`

All PHP/Symfony commands must run inside the `php` container:
```bash
docker compose exec php <command>
```

## Common Commands

```bash
# Start/stop
make start          # docker compose up -d
make stop           # docker compose down
make build          # rebuild containers (no cache)

# Full setup from scratch
make setup          # build + start + install + db-create + migrate

# Day-to-day
make install        # composer install + cache:clear
make migrate        # doctrine:migrations:migrate
make cache          # cache:clear
make shell          # bash shell in php container
make logs           # follow container logs
make routes         # debug:router
make fixtures       # load test data

# Symfony console (direct)
docker compose exec php php bin/console <command>
```

## Architecture

### Stack
- Symfony 7, PHP 8.3, Doctrine ORM, Twig, MySQL 8.0, Nginx 1.25

### Domain Model

Three entities with a clear hierarchy:
- **`Destination`** — a city/country (name, country, iataCode). Has many `Travel`s.
- **`Travel`** — a trip offering (title, dates, price, maxParticipants, status). Belongs to one `Destination`, has many `Booking`s.
- **`Booking`** — a reservation by a traveller (firstName, lastName, email, numberOfPersons). Belongs to one `Travel`.

**Travel statuses**: `draft` → `published` (only published travels appear in public listings)  
**Booking statuses**: `pending` → `confirmed` | `cancelled`

Available seats are computed at runtime in `Travel::getAvailableSeats()` by summing non-cancelled bookings.

### Controllers

Controllers handle forms manually via `$request->request->all()` — **no Symfony Form component is used**. Each controller follows the same pattern: read POST data, set entity fields, persist, flash, redirect.

| Route prefix | Controller | Key actions |
|---|---|---|
| `/` | `HomeController` | Shows published travels + active destinations |
| `/travel` | `TravelController` | CRUD; `findPublished()` filters status=published & future dates |
| `/destination` | `DestinationController` | CRUD; supports `?search=` query param |
| `/booking` | `BookingController` | New booking validates seat availability before persisting |

### Custom Repository Methods

- `TravelRepository::findPublished()` — status=published AND departureDate > now, eager-loads destination
- `TravelRepository::findWithBookings()` — eager-loads both destination and bookings
- `TravelRepository::findByMaxPrice(float)` — filter by budget
- `BookingRepository::findConfirmed()` — eager-loads travel
- `BookingRepository::countPersonsForTravel(int)` — SUM of non-cancelled persons

### Templates

Twig templates in `symfony/templates/`, one subdirectory per entity. All extend `base.html.twig`.

### Migrations

Located in `symfony/migrations/`. Generate new migrations after entity changes:
```bash
docker compose exec php php bin/console doctrine:migrations:diff
```

## Key Design Notes

- Forms use raw `$request->request->all()` rather than the Form component — keep this pattern consistent when adding new forms.
- CSRF protection is applied on destructive actions (delete, cancel) via `isCsrfTokenValid()`.
- `Booking::calculateTotalPrice()` must be called explicitly before persisting a new booking.
- `Travel::price` is stored as `DECIMAL` and typed as `string` in PHP — cast to float when doing arithmetic.
