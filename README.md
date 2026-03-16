# Battle Simulator

A Laravel web application that simulates a battle between two fighters using randomized stats and special skills.

## Features

- Start a new battle
- Run the battle one turn at a time
- Or run the battle all the way to the end
- View full battle history
- View detailed turn-by-turn logs
- Randomized stats for each battle
- Skills with trigger chances
- Automated tests

## Tech Stack

- PHP / Laravel
- SQLite
- Blade
- Vite
- PHPUnit

## Battle Rules

- Each fighter has a range for:
  - health
  - strength
  - defence
  - speed
  - luck
- When a battle starts, actual stats are randomly generated from those ranges
- The fighter with higher speed attacks first
- If speed is equal, the fighter with higher luck attacks first
- Battles can last up to 15 turns
- If no one is defeated after 15 turns, the battle ends in a draw
- Skills can modify the turn outcome if triggered

## Database Design

Main tables used:

- fighters — reusable fighter templates with stat ranges
- skills — skills that belong to fighters
- battles — battle sessions
- battle_fighters — generated fighter stats for a specific battle
- battle_turns — turn-by-turn battle log

## How to Run

### 1. Install dependencies

    composer install
    npm install

### 2. Set up environment

    cp .env.example .env
    php artisan key:generate

### 3. Configure database

This project uses SQLite.

Make sure the file exists:

    database/database.sqlite

Update .env if needed:

    DB_CONNECTION=sqlite
    DB_DATABASE=absolute/path/to/database/database.sqlite

### 4. Run migrations and seed data

    php artisan migrate --seed

### 5. Build frontend assets

    npm run build

### 6. Start the app

    php artisan serve

Then open:

    http://127.0.0.1:8000

## Running Tests

    php artisan test

## Notes

- The app supports both full battle runs and step-by-step turn execution
- Battle state is stored in the database, so progress is preserved between requests
- Default seeded fighters are:
  - Kratos
  - Wild Beast
