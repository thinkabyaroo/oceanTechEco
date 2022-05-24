
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;

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

Route::apiResource("brand",\App\Http\Controllers\BrandController::class);
Route::apiResource("category",\App\Http\Controllers\CategoryController::class);
Route::apiResource("product",\App\Http\Controllers\ProductController::class);

Route::post('/register',[ApiAuthController::class,'register']);
Route::post('/login',[ApiAuthController::class,'login']);
Route::post('/logout',[ApiAuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/change-passsword',[ApiAuthController::class,'updatePassword'])->name('change-password')->middleware('auth:sanctum');
