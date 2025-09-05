whoami
if php artisan migrate:status --no-ansi | grep -q "Pending"; then
    echo "🚀 Pending migrations found. Running..."
    php artisan migrate --force # :fresh --seed
else
    echo "✅ No pending migrations. Skipping..."
fi
php artisan storage:link
php artisan google-fonts:fetch
php artisan optimize:clear
php artisan optimize
