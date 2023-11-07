
# Собрать фронт
- npm run dev

# Artisan
php artisan serve - подключение к серверу

php artisan list - список команд

php artisan cache:clear   - очистка кэша приложения

php artisan route:clear   - очистка кэша роутинга приложения

php artisan tinker - заполнить фейковыми данными

выполнить команды интерактивной консоли внутри:
App\Models\Category::factory()->count(5)->create();
App\Models\Tag::factory()->count(5)->create();
App\Models\Post::factory()->count(5)->create();

Выйти из Tinker - Control + C

php artisan make:controller Admin/SubscriberController -r  - создать контроллер с "resource"

Добавить поле в существующую таблицу:
1. php artisan make:migration add_profile_status_to_users_table --table=users  
2. public function up()
   {
   Schema::table('users', function (Blueprint $table) {
   $table->text('profile_status');
   });
   }

    public function down()
    {
    Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('profile_status');
    });
    }
3. php artisan migrate



# IDE Helper

После осуществления миграции:
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta


