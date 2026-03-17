# Веб-сервис "Заявки в ремонтную службу"

Тестовое задание для компании "База Бизнеса". Простая CRM-система для приема заявок от клиентов и распределения их между мастерами.

## Как запустить проект (Docker / Laravel Sail)

Проект настроен для запуска через Laravel Sail (Docker).

1. Склонируйте репозиторий и перейдите в папку проекта.
2. Скопируйте файл окружения: `cp .env.example .env`
3. Установите зависимости (если у вас локально есть composer): `composer install`
   *(Если composer нет, используйте docker: `docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install`)*
4. Поднимите контейнеры: `./vendor/bin/sail up -d` (или `docker compose up -d`)
5. Сгенерируйте ключ приложения: `./vendor/bin/sail artisan key:generate`
6. Запустите миграции и сидеры (наполнит БД тестовыми данными): 
   `./vendor/bin/sail artisan migrate --seed`
7. Соберите фронтенд: `./vendor/bin/sail npm install && ./vendor/bin/sail npm run build`

Проект будет доступен по адресу: **http://localhost**

## Тестовые пользователи (из сидера)
- **Диспетчер:** `admin@test.com` / Пароль: `12345678`
- **Мастер 1:** `master1@test.com` / Пароль: `12345678`
- **Мастер 2:** `master2@test.com` / Пароль: `12345678`

## Проверка проблемы "гонки" (Race Condition)

В ТЗ было требование: предотвратить ошибку, если два мастера одновременно попытаются взять заявку в работу.
Мы решили это через **атомарное обновление** в базе данных (`update` с условием `whereIn('status', ['new', 'assigned'])`). 

**Как проверить через cURL (в два терминала одновременно):**

1. Создайте заявку №1 (через интерфейс сайта).
2. Залогиньтесь под двумя разными мастерами, вытащите их сессионные куки (или сделайте это через два разных браузера).
3. Либо просто отправьте два параллельных cURL запроса на смену статуса (заменив `[COOKIE]` и `[CSRF_TOKEN]` на ваши):

```bash
curl -X PATCH http://localhost/requests/1/status \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -H "Cookie: laravel_session=[COOKIE]" \
     -d "_token=[CSRF_TOKEN]&status=in_progress"