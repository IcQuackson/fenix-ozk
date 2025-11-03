# Fenix OZK Project Overview

Fenix OZK is a Laravel-12 application that integrates the FenixEdu API to provide a student-oriented dashboard, course information and Fenix OAuth integration.  The back-end is written in PHP 8.2 and follows a domain-driven architecture.  The front-end uses Blade templates enhanced with Vite, Tailwind CSS and Alpine JS.  A generated PHP client for the FenixEdu OpenAPI specification is included under `app/Infrastructure/Fenix/Generated`.

## Running the application

### Prerequisites

* PHP ≥ 8.2 with the required extensions.
* Node.js with npm for the Vite/Tailwind build pipeline.
* SQLite, MariaDB or another database supported by Laravel.  SQLite is used by default in `.env.example`.
* Composer for PHP dependency management.

### Setup steps

1. **Clone and prepare the repository**.  Copy `.env.example` to `.env` and fill in application settings such as `APP_URL`, database credentials and Fenix OAuth settings (`FENIX_CLIENT_ID`, `FENIX_CLIENT_SECRET`, `FENIX_BASE_URL`, `FENIX_OAUTH_AUTHORIZE`, `FENIX_OAUTH_ACCESS_TOKEN`, `FENIX_OAUTH_REFRESH_TOKEN` and `FENIX_REDIRECT_URI`).
2. **Install PHP dependencies**.  Run `composer install`.  The `composer.json` file defines Laravel 12 as the framework and pulls in testing, linting and development tools.
3. **Install front-end dependencies**.  Run `npm install`.  The `package.json` file lists Vite, Tailwind CSS, Alpine JS and other build dependencies.
4. **Generate an application key**.  Run `php artisan key:generate` if it is not already generated.
5. **Run database migrations**.  Execute `php artisan migrate`.  This creates the required tables, including a `fenix_tokens` table used to store access and refresh tokens.
6. **Build or watch front-end assets**.  For development, run `npm run dev`; for a production build, run `npm run build`.
7. **Start the application**.  Use `php artisan serve` or the Makefile helper `make serve` to launch the HTTP server.  To concurrently run the queue listener, log viewer and Vite dev server, you can use the `dev` script defined in Composer (`composer run dev`).
8. **Run queue workers and scheduler** as needed with `php artisan queue:work` and `php artisan schedule:work` (provided by the Makefile targets `queue` and `scheduler`).

## Project architecture

### Domain-driven structure

The codebase is organized around a domain-driven design.  Core business objects such as `Course`, `CourseEvaluation`, `Person`, `Curriculum` and related value objects reside in `app/Domain/Entities`.  These objects include factory methods (e.g., `fromApi`) to translate raw API payloads into domain models.

### Application services

Application services coordinate use cases and encapsulate caching logic.  For example, `DashboardService` provides methods to retrieve the authenticated student’s personal data, sum of earned ECTS credits, upcoming evaluations and enrolled courses.  Each method caches its result under a key with a time-to-live of 10–15 minutes and uses locks to prevent thundering-herd effects.  It calls into domain services and the Fenix client to fetch data and transforms the results into view models.  Likewise, `CourseService` retrieves course details, evaluations, groups, schedules, students and announcements, caching each call and mapping raw API results into domain entities.

### Fenix adapter

The `FenixHttpClient` implements the `FenixPort` interface and acts as the gateway to the FenixEdu API.  It exposes methods to retrieve public information (about, academic terms, courses, degrees and spaces) as well as private student information (person details, calendar events, evaluations, curriculum and payments).  Each public method delegates to a helper `getPublic` call.  Private calls include the user’s OAuth access token provided by a `TokenProvider` and handle automatic refresh on 401 responses.

### Token management

`DatabaseTokenProvider` stores Fenix OAuth tokens in the `fenix_tokens` table and refreshes them when expired.  It retrieves the stored access token for a user, refreshes it if necessary and persists the updated expiry timestamp.  The `FenixAuthController` is responsible for initiating the OAuth flow (`/fenix/connect`), exchanging the authorization code for access and refresh tokens, retrieving the user’s identity from `/person`, creating or updating a local `User` record and saving the tokens.

### Controllers and routes

Routes are defined in `routes/web.php`.  There is a health endpoint (`/healthz`), two public routes for Fenix OAuth (`/fenix/connect` and `/fenix/callback`) and a group of authenticated routes requiring both Laravel auth and a valid Fenix token.  Authenticated users access the dashboard at `/`, list courses, and view course details, evaluations, groups, schedules and students.  The `DashboardController` aggregates dashboard data from services and passes view models to Blade templates.  `CourseController` provides both web views and JSON API endpoints for course information.

### Front-end

User interfaces are rendered using Blade templates with Tailwind CSS.  Assets are compiled via Vite.  Development builds are run with `npm run dev`, and production bundles are generated with `npm run build`.  Alpine JS is used for lightweight interactivity.  View models transform domain entities into array structures suitable for the views.

### Database

Laravel migrations manage schema changes.  Besides the default user and session tables, a `fenix_tokens` table stores OAuth tokens for each user.  The default `.env.example` configures an SQLite database, but you can switch to MySQL or PostgreSQL by adjusting `DB_CONNECTION` and related variables.

## Extending the project

When adding new features, follow the established patterns to preserve separation of concerns and maintainability:

1. **Define or update domain entities**.  If the Fenix API exposes new resources, create corresponding domain classes (e.g., `NewEntity`) with static factory methods to transform API payloads into typed objects.
2. **Extend `FenixPort`**.  Add new method signatures to the `FenixPort` interface for each endpoint.  Implement these methods in `FenixHttpClient` by calling the appropriate Fenix API path and returning raw arrays.
3. **Add application services**.  Create a service (or extend an existing one) that orchestrates calls to `FenixPort`, applies caching using `cache->remember` and returns domain entities or arrays.  Follow the example of `DashboardService` and `CourseService` which cache with per-user keys and use locking to avoid concurrent fetches.
4. **Create controllers and routes**.  Expose the new functionality through a controller.  Use route groups with appropriate middleware (e.g., `auth` and `fenix` for endpoints that require a valid Fenix token).  Return Blade views or JSON responses as needed.
5. **Update view models and templates**.  For new pages, add view model classes that convert domain objects to arrays.  Create Blade templates in `resources/views` and reference them in controllers.
6. **Modify the front-end as needed**.  If new UI elements require JavaScript or styles, update the corresponding files and rebuild assets with Vite.
7. **Write tests**.  Use the `FakeFenix` class in `tests/Fakes` to mock API responses when testing new services and controllers.  Run tests using `php artisan test` or the `make test` target.

By adhering to this structure—domain entities, application services with caching, an infrastructure adapter implementing `FenixPort`, controllers and view models—the project remains modular and extensible.  Adding features typically involves updating the port and its implementation, implementing a service layer and exposing it through controllers and views.
