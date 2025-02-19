<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LorcanaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/me/cards', [CardController::class, 'userCards']);
    Route::post('/me/{id}/update-owned', [CardController::class, 'updateUserCard']);
    Route::get('/my-cards', [AuthController::class, 'myCards']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/add', [WishlistController::class, 'add']);
    Route::post('/wishlist/remove', [WishlistController::class, 'delete']);

});


Route::middleware('auth:sanctum')->post('/my-cards/add', [AuthController::class, 'addCardToCollection']);


Route::get('/test-auth', function (Request $request) {
    return response()->json($request->user());
})->middleware('auth:sanctum');

Route::get("/sets", [SetController::class, "index"]);
Route::get("/sets/{id}", [SetController::class, "single"]);
Route::get("/sets/{id}/cards", [SetController::class, "cards"]);

Route::get("/cards", [CardController::class, "index"]);
Route::get("/cards/{id}", [CardController::class, "single"]);

