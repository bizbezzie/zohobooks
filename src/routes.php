<?php

use Bizbezzie\Zohobooks\Zohobooks;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('auth/zoho/{user_id}/first-token', [ZohoBooks::class, 'firstToken']);

Route::get('/auth/zoho/callback', [ZohoBooks::class, 'callback'])->name('auth.zoho.callback');

Route::get('/auth/zoho/refresh-token', [ZohoBooks::class, 'refreshToken']);

