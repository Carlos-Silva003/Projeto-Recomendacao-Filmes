<?php

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

use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'index'])->name('books.index'); // Rota para a página inicial de livros
//Route::post('/books/recommend', [BookController::class, 'recommendMovies'])->name('books.recommend');

Route::get('/', function () {
    return view('welcome');
});

Route::post('/books', [BookController::class, 'store'])->name('books.store');

Route::get('books/{book}/generate-synopsis', [BookController::class, 'generateSynopsis'])->name('books.generate-synopsis');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');

Route::get('/books/{book}/recommend-movies', [BookController::class, 'recommendMovies'])->name('books.recommend-movies');