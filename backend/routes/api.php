<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TacheController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\RendezvousController;
use App\Http\Controllers\API\CompterendusController;
use App\Http\Controllers\API\ParticipantsController;
use App\Http\Controllers\API\SoustachesController;

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
Route::apiResource("users", UserController::class); // Les routes "users.*" de l'API
Route::apiResource("rendezvous", RendezvousController::class);
Route::apiResource("participants", ParticipantsController::class);
// Route::get('/participant/{rendezvous_id}', function (Request $request, string $rendezvousId) {
//     return 'Participants '.$request-> rendezvous_id;
// });
// Route::get('/participants/{rendezvous_id}', ParticipantsController::class);
Route::apiResource("compterendu", CompterendusController::class);
Route::apiResource("taches", TacheController::class);
Route::apiResource("soustaches", SoustachesController::class);
Route::apiResource("statut", StatusController::class);
Route::apiResource("project", ProjectController::class);
Route::post('/auth/register', [AuthController::class, 'createUser']); //inscription 
Route::post('/auth/login', [AuthController::class, 'loginUser']); //connexion
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
