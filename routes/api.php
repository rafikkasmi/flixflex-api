<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Movies\MovieController;
use App\Http\Controllers\Api\Movies\FavoritesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'/auth','name'=>'auth.'],function(){

    Route::post('/register',[UserController::class,"register"]);
    Route::post('login',[UserController::class,"login"]);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get("/movies",[MovieController::class,"getAllMovies"]);
    Route::get("/tv-series",[MovieController::class,"getAllTvSeries"]);

    Route::get("/popular-movies",[MovieController::class,"getPopularMovies"]);
    Route::get("/popular-tv-series",[MovieController::class,"getPopularTvSeries"]);

    Route::get("/movies/{id}",[MovieController::class,"getMovie"]);
    Route::get("/tv-series/{id}",[MovieController::class,"getTvSerie"]);

    Route::get("/movies/search/{query}",[MovieController::class,"searchMovies"]);
    Route::get("/tv-series/search/{query}",[MovieController::class,"searchTvSeries"]);

    Route::get("/movies/{id}/trailer",[MovieController::class,"getMovieTrailer"]);
    Route::get("/tv-series/{id}/trailer",[MovieController::class,"getTvSerieTrailer"]);


    Route::group(['prefix'=>'/favorites'],function(){

        Route::get("/movies",[FavoritesController::class,"getFavorites"]);
        Route::get("/tv-series",[FavoritesController::class,"getFavorites"]);

        Route::post("/movies",[FavoritesController::class,"addToFavorites"]);
        Route::post("/tv-series",[FavoritesController::class,"addToFavorites"]);
        
        Route::delete("/{id}",[FavoritesController::class,"deleteFromFavorites"]);
        
    });

});
