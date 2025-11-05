# Fenix OZK

Laravel 12 dashboard that turns the FenixEdu API into a live student hub. OAuth login, cached data pulls, modern Blade UI.

## Highlights
- Dashboard shows photo, contact info, degree badge, and curriculum KPIs (ECTS, average, pace).
- `ClassScheduleService` ranks the next classes from the Fenix calendar and surfaces them inline.
- Aggregated RSS announcements keep students current across every enrolled course.
- Upcoming evaluations, enrolled courses, and curriculum metrics are cached with per-user locks to avoid API spam.
- Course pages drill into schedules, groups, rosters, and evaluations with shared view models.
- Generated OpenAPI client plus light Tailwind/Alpine layer keeps the UI responsive.

## Stack
- PHP 8.2 · Laravel 12 · Domain-first services under `app/Application`.
- Blade + Tailwind 4 + Alpine, bundled via Vite.
- SQLite by default; MySQL or PostgreSQL work with `.env` tweaks.
- OAuth tokens live in the `fenix_tokens` table via `DatabaseTokenProvider`.

## Quick Start
1. Copy `.env.example` to `.env`; set `APP_URL`, DB creds, and Fenix OAuth keys (`FENIX_CLIENT_ID`, `FENIX_CLIENT_SECRET`, base URLs, redirect, scope).
2. Install deps: `composer install` and `npm install`.
3. Generate app key with `php artisan key:generate`.
4. Run migrations: `php artisan migrate`.
5. Launch everything with `composer run dev` (serves app, queue listener, log tail, and Vite). Use `npm run build` for production assets.

## Useful Commands
- `php artisan serve` or `make serve` for a PHP-only boot.
- `php artisan queue:work` / `make queue` and `php artisan schedule:work` to process async jobs.
- `composer test` clears config and runs the test suite.

## Architecture Notes
- Domain entities under `app/Domain/Entities` handle API hydration (e.g. `Course`, `ClassEvent`, `CourseAnnouncement`, `Curriculum`).
- `FenixPort` abstracts remote calls; `FenixHttpClient` wires OAuth headers, retry logic, and the generated OpenAPI client.
- Services (`DashboardService`, `ClassScheduleService`, `CourseService`, `PersonService`, `InstitutionService`) own caching, locking, and aggregation logic.
- View models translate rich objects into arrays consumed by Blade components, keeping the templates lean.
- Dashboard components (`resources/views/dashboard`) render announcement feeds, upcoming classes, evaluations, and KPI cards with scroll-friendly layouts.

## Extending
- Add new Fenix endpoints by updating `FenixPort` + `FenixHttpClient`, then layering services, view models, and Blade partials.
- Use `tests/Fakes/FakeFenix` to cover new flows without hitting the live API.
- Run `@openapitools/openapi-generator-cli` via `npm` if the upstream contract changes.
