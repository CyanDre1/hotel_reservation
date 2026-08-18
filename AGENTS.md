# AGENTS.md

Laravel 13.25 (PHP 8.4) app for a hotel reservation system. Currently bare skeleton (default migrations, `User` model, `welcome` view only). Project docs are written in Indonesian.

## Mandatory project instructions
- Before doing any work, read `.agents/task-instruction.md`, `.agents/prd.md` (requirements), and `.agents/design.md` (UI/UX — follow strictly for all frontend work).
- After each completed task/group, update `.agents/tasklist.md` (create if missing): mark `[x]`, add ✅, update overall progress %, and note touched files. Record skipped/bugged tasks under `## Known Issues` instead of deleting silently.
- If `prd.md`/`design.md` are ambiguous/contradictory, ask before proceeding. Say why before adding any new dependency.

## Commands
- `composer test` — run test suite (runs `artisan config:clear` first, then `artisan test`). Single test: `php artisan test --filter=Name`.
- `composer dev` — starts `php artisan dev` (Laravel 13 combined server + Vite).
- `composer setup` — full bootstrap: composer install, copy `.env.example`→`.env` if missing, `key:generate`, migrate, npm install, build.
- `npm run build` / `npm run dev` — Vite. Tailwind CSS **v4** via `@tailwindcss/vite`; no `tailwind.config.js`, styles via `@import "tailwindcss"` in `resources/css/app.css`.
- `vendor/bin/pint` — Laravel Pint for code style.

## Database split
- Dev `.env` uses **MySQL** (`reservasi_hotel_db`); `.env.example` and `phpunit.xml` default to **SQLite** (tests run on `:memory:`). New migrations/seeders must work on both.
- Tests are preconfigured for in-memory SQLite; run them without touching the dev DB.

## Stack / state gotchas
- No auth package installed yet (PRD calls for Breeze) — skeleton has no auth, no booking domain models/migrations.
- `bootstrap/app.php` and `routes/web.php` are stock; new routes live in `routes/web.php`.
- Only default models/migrations exist (`users`, `cache`, `jobs`); controllers directory is empty.
- `composer.json` scripts hold the canonical workflow (`setup`, `dev`, `test`); check there rather than guessing.
