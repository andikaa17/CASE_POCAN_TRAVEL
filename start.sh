#!/bin/bash
echo "========================================"
echo "🚀 STARTING LARAVEL ON RAILWAY"
echo "========================================"

echo "📦 Menjalankan migrasi database..."
php artisan migrate --force

echo "🔥 Menjalankan Laravel server..."
php artisan serve --host=0.0.0.0 --port=$PORT