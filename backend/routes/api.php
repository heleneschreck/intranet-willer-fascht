<?php

use App\Http\Controllers\API\ArticlesController;
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
use App\Http\Controllers\API\CandidatsController;
use App\Http\Controllers\API\SouvenirsController;
use App\Http\Controllers\API\RendezvousController;
use App\Http\Controllers\API\SoustachesController;
use App\Http\Controllers\API\PartenairesController;
use App\Http\Controllers\API\CommentairesController;
use App\Http\Controllers\API\CompterendusController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\ParticipantsController;
use App\Http\Controllers\API\ConversationUsersController;
use App\Http\Controllers\API\IllustrationsController;

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
Route::get('/messages/conversation/{conversation_id}', [MessagesController::class, 'getMessageByConversation']);


Route::apiResource("conversations", ConversationController::class); // Les routes "conversations
Route::get('/conversations/user/{user_id}', [ConversationController::class, 'getConversationByCreateur']);

Route::apiResource("conversation_users", ConversationUsersController::class); // Les routes "convers
Route::get('/conversation_users/conversation/{conversation_id}', [ConversationUsersController::class, 'getMessagesByConversations']);
Route::get('/conversation_users/participants/{conversation_id}', [ConversationUsersController::class, 'getParticipantsByMessages']);
Route::get('/conversation_users/user/{users_id}', [ConversationUsersController::class, 'getMessagesByUser']);
Route::delete('/conversation_users/{user_id}/{conversation_id}', [ConversationUsersController::class, 'destroyByUserAndConversation']);

Route::apiResource("partenaires", PartenairesController::class);
Route::apiResource("articles", ArticlesController::class);
Route::get('/articles/user/{user_id}', [ArticlesController::class, 'getArticlesByUser']);
Route::apiResource("illustrations", IllustrationsController::class);
Route::delete('/illustrations/article/{article_id}', [IllustrationsController::class, 'destroyByArticle']);
Route::get('/illustrations/article/{article_id}', [IllustrationsController::class, 'getIllustrationByArticle']);
Route::get('/illustrations/articlereduit/{article_id}', [IllustrationsController::class, 'getIllustrationByArticleReduits']);
Route::apiResource("candidats", CandidatsController::class);
Route::get('/candidats/see/{vu}', [CandidatsController::class, 'getCandidatsBySee']);
Route::put('/candidats/update-by-vu/{vu}',[CandidatsController::class,'updateByVu'] );

Route::apiResource("profils", ProfilsController::class);
Route::apiResource("rendezvous", RendezvousController::class);
Route::apiResource("participants", ParticipantsController::class);
Route::get('/participants/rendezvous/{rendezvous_id}', [ParticipantsController::class, 'getParticipantsByRendezvous']);
Route::get('/participants/user/{user_id}', [ParticipantsController::class, 'getUserByRendezvous']);


Route::apiResource("image", ImageController::class);
Route::get('/images/{path}', 'App\Http\Controllers\Api\ImageController@index');

Route::apiResource("like", LikesController::class);
Route::get('/like/image/{image_id}', [LikesController::class, 'getLikesByImage']);
Route::get('/likes/{user_id}/{image_id}', [LikesController::class, 'getLikesByUserAndImage']);
Route::delete('/likes/{id}', [LikesController::class, 'destroy']);

Route::apiResource("souvenirs", SouvenirsController::class);
Route::post('image', [ImageController::class, 'imageStore']);
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
