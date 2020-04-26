# Тестовое задание fl.ru

- Скачать / Склонировать данный гит
- В папке **fltest-master** в командной строке выполнить:
```sh
    docker-compose up --build
```

- В браузере открыть http://127.0.0.1:6080, и войти в mysql admin под следующими данными:
```
    имя пользователя root
    пароль 123456
```

- создать Базу данных
```
имя: fltest
сопоставление: utf8mb4_unicode_ci
```

 - выполнить команды в папке fltest-master
```sh
docker-compose exec web php artisan key:generate
docker-compose exec web php artisan migrate
```

- Открыть http://127.0.0.1:8080/ и проверить выполнение