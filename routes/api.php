<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Noticias;
use App\Http\Controllers\TipoNoticias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('auth.register'); //registra um noovo jornalista
Route::post('/login', [AuthController::class, 'login'])->name('auth.login'); //loga o jornalista


Route::group(['middleware' => 'auth:api'], function () { //grupo de rotas autenticadas
    Route::post('/me', [AuthController::class, 'me'])->name('auth.me'); //retorna os dados dos jornalistas


    //rotas dos tipos de noticias
    Route::post('/type/create', [TipoNoticias::class, 'store'])->name('type.create'); //cria um novo tipo de noticia
    Route::get('/type/me', [TipoNoticias::class, 'show'])->name('type.me'); //retorna os dados dos tipos de noticias de jornalistas
    Route::post('/type/update/{id}', [TipoNoticias::class, 'update'])->name('type.update'); //edita um tipo de noticia de um jornalista
    Route::post('/type/delete', [TipoNoticias::class, 'destroy'])->name('type.delete'); //deleta um tipo de noticia de um jornalista
    

    //rotas das noticias
    Route::post('/news/create', [Noticias::class, 'store'])->name('news.create'); //cria uma nova noticia
    Route::get('/news/me', [Noticias::class, 'show'])->name('news.me'); //lista toda as noticias
    Route::post('/news/update/{id}', [Noticias::class, 'update'])->name('news.update'); //edita uma noticia
    Route::post('/news/delete/{id}', [Noticias::class, 'destroy'])->name('news.delete'); //deleta uma noticia
    Route::get('/news/type/{id}', [Noticias::class, 'filter'])->name('news.filter'); //filtra as noticias por tipo



});


