<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\StripeWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/devices/deviceform/{deviceId}', [DeviceController::class, 'getDeviceForm']);
Route::post('/submit-response', [ResponseController::class, 'submitResponse']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
