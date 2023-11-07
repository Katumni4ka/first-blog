<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\Admin\CommentsController as AdminCommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/post/{slug}', [HomeController::class, 'show'])->name('post.show');
Route::get('/tag/{slug}', [HomeController::class, 'tag'])->name('tag.show');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::post('/subscribe', [SubsController::class, 'subscribe']);
Route::get('/verify/{token}', [SubsController::class, 'verify']);


Route::group(['middleware' => 'guest'], function (){
    Route::get('/register', [AuthController::class, 'registerForm']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/comment', [CommentsController::class, 'store']);
});

Route::Group(['prefix'=>"admin", 'middleware' => 'admin'], function (){
    Route::get('/', [DashBoardController::class, 'index']);
    Route::resource('/categories', CategoriesController::class);
    Route::resource('/tags', TagsController::class);
    Route::resource('/users', UsersController::class);
    Route::get('/comments', [AdminCommentsController::class, 'index'])->name('comments_index');
    Route::get('/comments/toggle/{id}', [AdminCommentsController::class, 'toggle'])->name('comments_toggle');
    Route::delete('/comments/{id}/destroy', [AdminCommentsController::class, 'destroy'])->name('comments.destroy');
    Route::get('/users/toggle/{id}', [UsersController::class, 'toggle'])->name('users_toggle');
    Route::resource('/subscribers', SubscribersController::class);
    // Route::resource('/posts', PostsController::class);
    /**
     * Post Routes
     */
    Route::group(['prefix' => 'posts', 'middleware' => 'admin'], function() {
        Route::get('/', [PostsController::class, 'index'])->name('posts.index');
        Route::post('/{id}/toggle_status', [PostsController::class, 'toggleStatus'])->name('posts.toggleStatus');
        Route::get('/create', [PostsController::class, 'create'])->name('posts.create');
        Route::post('/create', [PostsController::class, 'store'])->name('posts.store');
        Route::get('/{id}/edit', [PostsController::class, 'edit'])->name('posts.edit');
        Route::patch('/{id}/update', [PostsController::class, 'update'])->name('posts.update');
        Route::delete('/{id}/delete', [PostsController::class, 'destroy'])->name('posts.destroy');
    });
});

