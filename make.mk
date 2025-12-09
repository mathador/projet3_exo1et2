cache: ##@cache Nettoie le cache
    php artisan config:clear
    php artisan cache:clear
    php artisan config:cache
    php artisan route:cache
dump : cache ##@cache Régénérer le fichier d'autoload de Composer
    composer dump-autoload
