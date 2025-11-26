<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChunkController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TeamMemberController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();



});

Route::get('/chunks', [ChunkController::class, 'index']);
Route::get('/chunks/{slug}', [ChunkController::class, 'showBySlug']);
Route::get('/team', [TeamMemberController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
