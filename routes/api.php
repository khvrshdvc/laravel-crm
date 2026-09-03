<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\DealController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\TaskController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
    ]);
});

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('companies', CompanyController::class)->only(['index', 'show']);
    Route::apiResource('contacts', ContactController::class)->only(['index', 'show']);
    Route::apiResource('leads', LeadController::class)->only(['index', 'show']);
    Route::apiResource('deals', DealController::class)->only(['index', 'show']);
    Route::apiResource('tasks', TaskController::class)->only(['index', 'show']);
});
