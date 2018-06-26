<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">

</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Установка

- Склонируйте данный репризиторий в папку хоста вашего сервера (обычно /var/www/) командой ```git clone``` и выполните команду ```composer install```. Это установит необходимые компоненты приложения и выполнит предварительную настройку.
- Сгенерируйте новый ключ приложения с помощью команды ```php artisan key:generate```
- Создайте и заполните файл настроек ```.env```. В комплекте с приложением поставляется файл ```.env.example```, который можно использовать как образец
- Выполните миграцию моделей в базу данных используя команду ```php artisan migrate```

## Настройка .ENV  

Заполните ```.env.example```, поставляемый в составе приложения в следующих местах.

- База данных
```
DB_CONNECTION= -- тип базы данных --
DB_HOST= -- адрес базы данных --
DB_PORT= -- порт --
DB_DATABASE= -- база --
DB_USERNAME= -- пользователь --
DB_PASSWORD= -- пароль --
```
По умолчанию это приложение поддерживает работу со следующими базами данных
 - MySQL (```mysql```)
 - Microsoft SQL Server (```sqlsrv```)
 - PostgreSQL (```pgsql```)
 - SQLite (```sqlite```)

База данных, указанная в этих настройках должна быть *уже* создана и доступна для представленных данных авторизации
 
- Сервер электронной почты
```
MAIL_HOST= -- адрес до smtp сервера электронной почты --
MAIL_PORT= -- порт --
MAIL_USERNAME= -- имя пользователя --
MAIL_PASSWORD= -- пароль --
 ```
Электронная почта отправляется через протокол smtp. Чтобы узнать, какие данные необходимо вводить, сверьтесь с настройками вашего сервера исходящей электронной почты.

- Авторизация ВКонтакте

Для того, чтобы подключить авторизацию через сервис [ВКонтакте](http://vk.com), вам необходимо иметь уже настроенное приложение. Приложения в ВКонтакте можно создать на [сайте разработчиков](http://vk.com/dev), в разделе [Мои приложения](https://vk.com/apps?act=manage).

```
VKONTAKTE_KEY= -- ID приложения ВКонтакте --
VKONTAKTE_SECRET= -- Защищённый ключ ВКонтакте --
VKONTAKTE_REDIRECT_URI= -- Доверенный redirect URI --
```  

Команда ```php artisan cache:clear``` выполняет пересчитывание данных настроек
