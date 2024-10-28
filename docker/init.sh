#!/bin/bash

# Ожидание MySQL
echo "Waiting for MySQL to start..."
sleep 10

# Выполнение миграций
php /var/www/src/Commands/migrate.php
