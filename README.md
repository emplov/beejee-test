# Test task for BeeJee

Основные компоненты:

Для роутинга и di
 * league/router
 * league/container

Для валидации
 * rakit/validation

Для вьюшек
 * twig

Для миграция
 * doctrine/migrations

Для базы
 * illuminate/database

Задеплои на digitalocean

Установка:
1.
```
git clone https://github.com/emplov/test
```
2. Перейти в папке проекта и создать .env из .env.example. Ввести данные с базы в .env

3. Установить зависимости
```
composer install
```
4. Запустить миграции
```
./vendor/bin/doctrine-migrations migrate
```
5. Запуск проекта в локалке
```
php -S localhost:8000 -t public
```
6. И впринципе всё )