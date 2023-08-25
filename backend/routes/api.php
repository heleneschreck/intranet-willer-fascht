<?php

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\API\LikesController;
use App\Http\Controllers\API\TacheController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\ProfilsController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\MessagesController;
use App\Http\Controllers\API\RendezvousController;
use App\Http\Controllers\API\SoustachesController;
use App\Http\Controllers\API\CommentairesController;
use App\Http\Controllers\API\CompterendusController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\ParticipantsController;
use App\Http\Controllers\API\NoparticpantsController;

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
Route::apiResource("messages", MessagesController::class); // Les routes "messages
Route::apiResource("conversations", ConversationController::class); // Les routes "conversations
Route::apiResource("conversation_users", Conversation_UsersController::class); // Les routes "convers
Route::apiResource("profils", ProfilsController::class); 
Route::apiResource("rendezvous", RendezvousController::class);
Route::apiResource("participants", ParticipantsController::class);
Route::get('/participants/rendezvous/{rendezvous_id}', [ParticipantsController::class, 'getParticipantsByRendezvous']);
Route::get('/participants/user/{user_id}', [ParticipantsController::class, 'getUserByRendezvous']);

Route::apiResource("noparticipants", NoparticpantsController::class);
Route::apiResource("image", ImageController::class);
Route::get('/images/{path}', 'App\Http\Controllers\Api\ImageController@index');

Route::apiResource("like", LikesController::class);
Route::get('/like/image/{image_id}', [LikesController::class, 'getLikesByImage']);
Route::get('/likes/{user_id}/{image_id}', [LikesController::class, 'getLikesByUserAndImage']);
Route::delete('/likes/{id}',[LikesController::class, 'destroy']);

Route::post('image',[ImageController::class, 'imageStore']);
Route::get('/image/{id}', 'ImageController@show');
Route::apiResource("commentaires", CommentairesController::class);
Route::get('/commentaires/image/{image_id}', [CommentairesController::class, 'getCommentairesByImage']);
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
