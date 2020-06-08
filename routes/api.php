<?php

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user());
}));*/
Route::get("/apoderado/puddle/usaring/", "ApoderadoController@getApodoByUsername")->middleware("android_passer","auth_person");


Route::get("/apoderado/correr/", ["uses" => "ApoderadoController@showByCorreo"])->middleware("android_passer","auth_apoderado", "login_apoderado");
Route::get("/apoderado/renovado/", ["uses" => "ApoderadoController@update"])->middleware("android_passer","auth_apoderado", "login_apoderado");

Route::get("/apoderado/correr/terminator/", ["uses" => "ApoderadoController@destroy"])->middleware("android_passer","auth_apoderado", "login_apoderado");
Route::get("/apoderado/logotipo/", ["uses" => "ApoderadoController@login"])->middleware("android_passer");

Route::get("/apoderado/napolitana/vida/", ["uses" => "ApoderadoController@existeUsername"])->middleware("android_passer");
Route::get("/apoderado/poso/", ["uses" => "ApoderadoController@guardar"])->middleware("android_passer");

Route::get("/apoderado/partida/usaring/", "ApoderadoController@getPuntoPartidaFinalByUsername")->middleware("android_passer","auth_person");




Route::get("/conductor/correr/", ["uses" => "ConductorController@showByCorreo"])->middleware("android_passer","auth_conductor", "login_conductor");
Route::get("/conductor/renovado/", ["uses" => "ConductorController@update"])->middleware("android_passer","auth_conductor", "login_conductor");

Route::get("/conductor/correr/terminator/", ["uses" => "ConductorController@destroy"])->middleware("android_passer","auth_conductor", "login_conductor");
Route::get("/conductor/logotipo/", ["uses" => "ConductorController@login"])->middleware("android_passer");

Route::get("/conductor/napolitana/vida/", ["uses" => "ConductorController@existeUsername"])->middleware("android_passer");
Route::get("/conductor/poso/", ["uses" => "ConductorController@guardar"])->middleware("android_passer");
Route::get("/conductor/pua/", "ConductorController@getLocalizacion")->middleware("android_passer","auth_person");
Route::get("/conductor/puddle/usaring/", "ConductorController@getApodoByUsername")->middleware("android_passer","auth_person");
