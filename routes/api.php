<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CocktailController;

Route::post('register',[AuthController::class,'register']);
Route::post('login', [AuthController::class, 'authenticate']);

Route::get('cocktails', [CocktailController::class, 'index']);
Route::post('createCocktail', [CocktailController::class,'store']);
Route::get('cocktailEdit/{id}', [CocktailController::class, 'edit']);
Route::put('cocktailUpdate/{id}', [CocktailController::class, 'update']);
Route::delete('deleteCocktail/{id}', [CocktailController::class, 'destroy']);



Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get_user', [AuthController::class, 'get_user']);
    Route::get('users', [UserController::class, 'index']);
    Route::post('createUser', [UserController::class, 'store']);
    Route::get('userEdit/{id}', [UserController::class, 'edit']);
    Route::put('userUpdate/{id}', [UserController::class, 'update']);
    Route::delete('deleteUser/{id}', [UserController::class, 'destroy']);
   

});