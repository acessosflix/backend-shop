<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\WebhookController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::post('/webhooks/c6', [WebhookController::class, 'handleC6']);
Route::post('/webhooks/crypto', [WebhookController::class, 'handleCrypto']);
