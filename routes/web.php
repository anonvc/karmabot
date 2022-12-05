<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


Route::get('/', function () {

  return view('welcome');
});



// DISCORD OAUTH ROUTES
Route::get('/auth/discord/redirect', [App\Http\Controllers\DiscordController::class, 'redirect'])->name('discord_redirect');
Route::get('/auth/discord/callback', [App\Http\Controllers\DiscordController::class, 'callback'])->name('discord_callback');
Route::get('/auth/discord/install', [App\Http\Controllers\DiscordController::class, 'install'])->name('discord_install');

Route::post('/logout', function () {
  Auth::logout();
  return redirect('/');
})->name('logout');

// ONBOARDING ROUTES
Route::get('/onboarding', [App\Http\Controllers\OnboardingController::class, 'index'])->middleware(['auth'])->name('onboarding');
Route::post('/onboarding', [App\Http\Controllers\OnboardingController::class, 'submit'])->middleware(['auth'])->name('onboarding_submit');
Route::post('/onboarding/set_channel', [App\Http\Controllers\OnboardingController::class, 'set_channel'])->middleware(['auth'])->name('onboarding_set_channel');

// DASHBOARD ROUTES
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth','onboarded'])->name('dashboard');


// Rewards Routes
Route::get('/rewards', [App\Http\Controllers\RewardController::class, 'index'])->middleware(['auth','onboarded'])->name('rewards');
Route::get('/rewards/create', [App\Http\Controllers\RewardController::class, 'create'])->middleware(['auth','onboarded'])->name('rewards_create');
Route::get('/rewards/edit/{id}', [App\Http\Controllers\RewardController::class, 'edit'])->middleware(['auth','onboarded'])->name('rewards_edit');
Route::post('/rewards/submit', [App\Http\Controllers\RewardController::class, 'submit'])->middleware(['auth','onboarded'])->name('rewards_submit');
Route::get('/rewards/delete/{id}', [App\Http\Controllers\RewardController::class, 'delete'])->middleware(['auth','onboarded'])->name('rewards_delete');


// Redemption Routes
Route::get('/redemptions', [App\Http\Controllers\RedemptionController::class, 'index'])->middleware(['auth','onboarded'])->name('redemptions');
Route::get('/redemptions/deliver/{id}', [App\Http\Controllers\RedemptionController::class, 'deliver'])->middleware(['auth','onboarded'])->name('redemptions_deliver');


// Member Routes
Route::get('/{collection_uid}', [App\Http\Controllers\MemberController::class, 'login'])->name('member_login');
Route::post('/{collection_uid}', [App\Http\Controllers\MemberController::class, 'login_submit'])->name('member_login_submit');
Route::get('/{collection_uid}/dashboard', [App\Http\Controllers\MemberController::class, 'dashboard'])->name('member_dashboard');
Route::post('/{collection_uid}/claim', [App\Http\Controllers\MemberController::class, 'claim'])->name('member_claim');


