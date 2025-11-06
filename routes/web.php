<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;


Route::get('/', function () {
    return view('welcome');
});


Route::post('/webhooks/c6', [WebhookController::class, 'handleC6']);
