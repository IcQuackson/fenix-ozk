# ---------------------------------------
# Laravel Makefile for Fenix OZK
# ---------------------------------------

APP_PORT ?= 8000
PHP      ?= php
COMPOSER ?= composer
ARTISAN  ?= $(PHP) artisan

# --- Basic App Tasks ---

serve:
	$(ARTISAN) serve --host=127.0.0.1 --port=$(APP_PORT)

migrate:
	$(ARTISAN) migrate

migrate-refresh:
	$(ARTISAN) migrate:fresh --seed

seed:
	$(ARTISAN) db:seed

tinker:
	$(ARTISAN) tinker

clean-serve: cache-clear build serve

# --- Frontend (Vite / NPM) ---

dev:
	npm run dev

build:
	npm run build

watch:
	npm run watch


# --- Cache / Config ---

cache-clear:
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear

cache-optimize:
	$(ARTISAN) config:cache
	$(ARTISAN) route:cache
	$(ARTISAN) view:cache

optimize: cache-clear cache-optimize

# --- Testing ---

test:
	$(ARTISAN) test

test-coverage:
	XDEBUG_MODE=coverage $(ARTISAN) test --coverage-html coverage/

# --- Composer / Dependencies ---

install:
	$(COMPOSER) install

update:
	$(COMPOSER) update

outdated:
	$(COMPOSER) outdated

# --- Linting / QA ---

lint:
	vendor/bin/pint --test

fix:
	vendor/bin/pint

phpstan:
	vendor/bin/phpstan analyse

# --- Debugging ---

debug:
	XDEBUG_MODE=debug $(ARTISAN) serve --host=127.0.0.1 --port=$(APP_PORT)

# --- Queues / Scheduler ---

queue:
	$(ARTISAN) queue:work

scheduler:
	$(ARTISAN) schedule:work

# --- Database ---

db-wipe:
	$(ARTISAN) db:wipe

# --- Custom Helpers ---

routes:
	$(ARTISAN) route:list

logs:
	tail -f storage/logs/laravel.log

# Default target
.DEFAULT_GOAL := serve
