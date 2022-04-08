<?php

use Bizbezzie\Zohobooks\Zohobooks;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

if (!cache('zoho_refresh_token')) {
    Route::get('auth/zoho/first-token', [ZohoBooks::class, 'firstToken']);
}

Route::get('/auth/zoho/callback', [ZohoBooks::class, 'callback']);

Route::get('/auth/zoho/redirect', function () {
    return Socialite::driver('zoho')->scopes(['ZohoBooks.fullaccess.all'])->stateless()->redirect();
})->name('auth.zoho.redirect');

Route::get('/auth/zoho/refresh-token', [ZohoBooks::class, 'refreshToken']);

