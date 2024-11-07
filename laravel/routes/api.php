<?php

use Illuminate\Support\Facades\Route;
use App\Http\Responses\ResponseCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// https://laravel.com/docs/11.x/controllers#actions-handled-by-resource-controllers
$path_update = 'update';
$path_delete = 'delete';

// Users Started
$user_section = "users";
// Route::get("$user_section", [UserController::class, 'index']);
// Route::get("$user_section/{id}", [UserController::class, 'show']);

// Route::post("$user_section", [UserController::class, 'store']);
// Route::post("$user_section/$path_update/{id}", [UserController::class, 'update']);
// Route::post("$user_section/$path_delete/{id}", [UserController::class, 'destroy']);
// Users Ended

Route::apiResource("$user_section", UserController::class);






// Different Section //
// Route::post('/tokens/create', function (Request $request) {
//     $GetResponseCode = new ResponseCode();
//     $token = $request->user()->createToken($request->token_name);
//     return response()->json(['status' => $GetResponseCode->NOT_FOUND()], $GetResponseCode->NOT_FOUND());
// });

Route::fallback(function (Request $request) {
    Log::warning('URL Endpoint API tidak ditemukan', [
        'url' => $request->fullUrl(),
        'method' => $request->method()
    ]);

    $GetResponseCode = new ResponseCode();
    return response()->json(['status' => $GetResponseCode->NOT_FOUND(), 'message' => 'URL Endpoint API tidak ditemukan'], $GetResponseCode->NOT_FOUND());
});
