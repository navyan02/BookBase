BookBase — Local run instructions

Quick start (macOS, with Homebrew)

1. Ensure Homebrew is installed (https://brew.sh/) and you have PHP & MySQL installed.
   - To install PHP: brew install php
   - To install MySQL: brew install mysql

2. From this folder run:
   ./start_local.sh

   The script will:
   - start Homebrew MySQL if not already running
   - create the `book_database` database if missing
   - import `schema.sql` (only if tables are missing)
   - export local DB env vars used by `db.php`
   - start PHP's built-in server on http://localhost:8000

3. Open http://localhost:8000/index.php in your browser.

Notes
- If your MySQL root user has a password, set DB_PASS before running the script:
  DB_PASS='yourpassword' ./start_local.sh
- The project reads DB credentials from environment variables (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`) with safe defaults.
- For a reproducible setup you can use Docker; ask me and I'll add a docker-compose file.

Troubleshooting
- If you get "Database connection failed" edit `db.php` or set DB_* env vars.
- If port 8000 is busy, stop the script (Ctrl+C) and run `php -S localhost:8080 -t .` instead.
