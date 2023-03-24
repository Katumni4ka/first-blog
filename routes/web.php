<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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
Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);


Route::Group(['prefix'=>"admin"], function (){
    Route::get('/', [DashBoardController::class, 'index']);
    Route::resource('/categories', CategoriesController::class);
    Route::resource('/tags', TagsController::class);
    Route::resource('/users', UsersController::class);
   // Route::resource('/posts', PostsController::class);
    /**
     * Post Routes
     */
    Route::group(['prefix' => 'posts'], function() {
        Route::get('/', [PostsController::class, 'index'])->name('posts.index');
        Route::get('/create', [PostsController::class, 'create'])->name('posts.create');
        Route::post('/create', [PostsController::class, 'store'])->name('posts.store');
        Route::get('/{post}/edit', [PostsController::class, 'edit'])->name('posts.edit');
        Route::patch('/{post}/update', [PostsController::class, 'update'])->name('posts.update');
        Route::delete('/{post}/delete', [PostsController::class, 'destroy'])->name('posts.destroy');
    });
});

