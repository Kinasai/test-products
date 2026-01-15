# Balance Management API

#### Простое API приложение для управления задачами.

# Требования
- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/#install-compose)

# Как запустить

### Первый запуск
- `git clone https://github.com/Kinasai/test-products.git`
- `cd test-products`
- `docker compose up -d --build`
- `docker compose exec php bash`
- `composer update`
- `cp .env.example .env && php artisan key:generate && php artisan storage:link`
- `php artisan migrate && php artisan db:seed`

### Последующий запуск
- `docker compose up -d`

# API Endpoints
### Возвращает список товаров с возможностью фильтрации и сортировки

`GET /api/products`

### Параметры запроса (query parameters)

#### Фильтрация

- `q` - поиск по названию (например: ?q=iphone)
- `price_from, price_to` - диапазон цен (например: ?price_from=1000&price_to=50000)
- `category_id` - фильтр по категории (например: ?category_id=3)
- `in_stock` - наличие на складе (например: ?in_stock=true)
- `rating_from` - минимальный рейтинг (например: ?rating_from=4.0)

#### Сортировка:

- `sort` - критерий сортировки:
  - `price_asc` - по возрастанию цены
  - `price_desc` - по убыванию цены
  - `rating_desc` - по убыванию рейтинга
  - `newest` - сначала новые

#### Пагинация:

- `page` - номер страницы (по умолчанию: 1)
- `per_page` - количество элементов на странице (по умолчанию: 20, максимум: 100)

