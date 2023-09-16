<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WhatsappAPIController;

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

//Route::post("/register", [AuthController::class, "register"]);

Route::post("/send_message", [ChatController::class, "send_message"]);

Route::get("/templates", [TemplateController::class, "index"]);
Route::get("/messages", [MessageController::class, "index"]);
Route::get("/fields", [TemplateController::class, "fields"]);
/*


Route::get('/enviar_mensaje', [ChatController::class, 'enviarMensaje']);
Route::get('/webhook', [ChatController::class, 'verificacionwebhook']);
Route::post('/webhook', [ChatController::class, 'procesarWebhook']);
*/