
# Собрать фронт
- npm run dev

# Artisan
php artisan serve - подключение к серверу

php artisan list - список команд

php artisan cache:clear   - очистка кэша приложения

php artisan tinker - заполнить фейковыми данными

выполнить команды интерактивной консоли внутри:
App\Models\Category::factory()->count(5)->create();
App\Models\Tag::factory()->count(5)->create();
App\Models\Post::factory()->count(5)->create();

Выйти из Tinker - Control + C

php artisan make:controller Admin/SubscriberController -r  - создать контроллер с "resource"

