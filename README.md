# User API (Symfony 6)

REST API для управления пользователями. Реализовано на **Symfony 6** с поддержкой валидации и обработкой ошибок.  

---

## Установка и запуск

1. Клонировать репозиторий:
   git clone https://github.com/KseniaKatargina/symfony-rest.git

2. Установить зависимости:
   composer install
   
3. Настроить .env:
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/rest_symfony"
   
4. Создать базу и выполнить миграции:
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate

5. Запустить сервер:
   symfony server:start

# REST API Методы

Базовый URL: http://127.0.0.1:8000/api/users  


---

## Методы

| Метод   | URL                 | Описание                  | Успешный ответ | Ошибки                         |
|---------|---------------------|---------------------------|----------------|--------------------------------|
| **POST**   | `/api/users`        | Создать пользователя      | `201 Created`  | `400 Bad Request`, `409 Conflict` |
| **PUT**    | `/api/users/{id}`   | Обновить пользователя     | `200 OK`       | `400 Bad Request`, `404 Not Found` |
| **GET**    | `/api/users/{id}`   | Получить пользователя     | `200 OK`       | `404 Not Found` |
| **DELETE** | `/api/users/{id}`   | Удалить пользователя      | `204 No Content` | `404 Not Found` |

---

## Создать пользователя
**POST** `/api/users`

### Request body (JSON)
{
  "email": "john@example.com",
  "name": "John Doe",
  "password": "123456"
}
### Успешный ответ (JSON)
{
  "id": 1,
  "email": "john@example.com",
  "name": "John Doe"
}

## Обновить пользователя
**PUT** `/api/users/{id}`

### Request body (JSON)
{
  "name": "John Updated",
  "password": "newpass123"
}

### Успешный ответ (JSON)
{
  "id": 1,
  "email": "john@example.com",
  "name": "John Updated"
}

## Получить пользователя
**GET** `/api/users/{id}`

### Успешный ответ (JSON)
{
  "id": 1,
  "email": "john@example.com",
  "name": "John Updated"
}

## Удалить пользователя
**DELETE** `/api/users/{id}`

### Успешный ответ (JSON)
{
}



   
