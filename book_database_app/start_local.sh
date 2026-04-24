#!/usr/bin/env bash
set -euo pipefail

# start_local.sh — start mysql (Homebrew), import schema, and run PHP dev server
# Usage: ./start_local.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$SCRIPT_DIR"

echo "Project dir: $PROJECT_DIR"

echo "Starting MySQL (Homebrew) if it's not running..."
if brew services list | grep -E '^mysql\s' | grep -q started; then
  echo "MySQL already started"
else
  brew services start mysql
  echo "Started MySQL"
fi

echo "Ensuring database 'book_database' exists..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS book_database;"

echo "Importing schema.sql (if not already imported)..."
if mysql -u root -e "USE book_database; SHOW TABLES;" | grep -q Author; then
  echo "Tables already present — skipping import"
else
  mysql -u root book_database < "$PROJECT_DIR/schema.sql" || true
  echo "Schema import attempted"
fi

echo "Exporting DB env vars for PHP (local defaults). You can override these before running the script."
export DB_HOST=${DB_HOST:-localhost}
export DB_USER=${DB_USER:-root}
export DB_PASS=${DB_PASS:-}
export DB_NAME=${DB_NAME:-book_database}

echo "Starting PHP built-in server at http://localhost:8000"
php -S localhost:8000 -t "$PROJECT_DIR"
