#!/bin/bash
set -e

echo "[OPINA DOCKER] Iniciando contenedor Laravel..."

# Asegurar permisos correctos en storage y bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enlace simbólico de storage si no existe
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || true
fi

# Esperar a que MySQL esté listo antes de correr migraciones
echo "[OPINA DOCKER] Verificando conexión a la base de datos..."
MAX_TRIES=30
TRIES=0
until php -r "try { new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT', 3306).';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
    TRIES=$((TRIES+1))
    if [ $TRIES -ge $MAX_TRIES ]; then
        echo "[OPINA DOCKER] Advertencia: Base de datos no respondió a tiempo. Continuando inicio..."
        break
    fi
    echo "[OPINA DOCKER] Esperando a MySQL ($TRIES/$MAX_TRIES)..."
    sleep 2
done

# Ejecutar migraciones automáticamente
echo "[OPINA DOCKER] Ejecutando migraciones..."
php artisan migrate --force || true

# Caché de configuración, rutas y vistas para máximo rendimiento
echo "[OPINA DOCKER] Optimizando cachés de Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "[OPINA DOCKER] ¡Sistema listo! Iniciando servidor web..."
exec apache2-foreground
