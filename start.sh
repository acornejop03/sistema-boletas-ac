#!/bin/bash

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Verificando si se necesita seed..."
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "Base de datos vacía, ejecutando seeders..."
    php artisan db:seed --force
    echo "Seeders completados."
else
    echo "Base de datos ya tiene datos ($USER_COUNT usuarios), omitiendo seed."
fi

echo "Iniciando servidor Laravel en puerto $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
