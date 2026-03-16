# Battle Simulator

A Laravel web app that simulates a battle between two fighters based on random stats and special skills.

## Features

- Start a new battle
- Run the battle **one turn at a time**
- Or run the battle **all the way to the end**
- View full **battle history**
- View detailed **turn-by-turn logs**
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
- Battles can last up to **15 turns**
- If no one is defeated after 15 turns, the battle ends in a **draw**
- Skills can modify the turn outcome if triggered

## Database Design

Main tables used:

- `fighters` — reusable fighter templates with stat ranges
- `skills` — skills that belong to fighters
- `battles` — battle sessions
- `battle_fighters` — generated fighter stats for a specific battle
- `battle_turns` — turn-by-turn battle log

## How to Run

### 1. Install dependencies

```bash
composer install
npm install
