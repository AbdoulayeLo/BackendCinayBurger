<?php

use App\Http\Controllers\CommandeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::apiResource('burgers',\App\Http\Controllers\BurgerController::class);
Route::apiResource('commandes',\App\Http\Controllers\CommandeController::class);
Route::apiResource('paiements',\App\Http\Controllers\PaiementController::class);
Route::apiResource('paiements',\App\Http\Controllers\PaiementController::class);
//Route::Route('/commandes/{commande}/cancel', \App\Http\Controllers\CommandeController::class, 'cancel');
Route::post('/commandes/valider/{id}', [CommandeController::class, 'valider']);
Route::post('/commandes/envoyer-email/{id}', [CommandeController::class, 'envoyerEmail']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register",[\App\Http\Controllers\AuthController::class,'register']);
Route::post("/login",[\App\Http\Controllers\AuthController::class,'login']);

Route::middleware('auth:api')->group(function (){
    Route::post("/logout",[\App\Http\Controllers\AuthController::class,'logout']);

});


Route::get('/valider/{id}',[\App\Http\Controllers\CommandeController::class,'valider']);
