<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchRequestController;
use App\Http\Controllers\MatchResultController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('home');

// OAuth routes (accessible to both guest and authenticated users)
Route::get('/auth/google', [OAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Team routes
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
    Route::post('/teams/{team}/leave', [TeamController::class, 'leave'])->name('teams.leave');
    Route::post('/teams/{team}/promote/{user}', [TeamController::class, 'promote'])->name('teams.promote');
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.remove-member');

    // Team join request routes
    Route::post('/teams/{team}/join-requests/{joinRequest}/approve', [TeamController::class, 'approveJoinRequest'])->name('teams.join-requests.approve');
    Route::post('/teams/{team}/join-requests/{joinRequest}/reject', [TeamController::class, 'rejectJoinRequest'])->name('teams.join-requests.reject');
    Route::delete('/teams/{team}/join-requests/{joinRequest}/cancel', [TeamController::class, 'cancelJoinRequest'])->name('teams.join-requests.cancel');

    // Match request routes
    Route::get('/match-requests', [MatchRequestController::class, 'index'])->name('match-requests.index');
    Route::get('/match-requests/create', [MatchRequestController::class, 'create'])->name('match-requests.create');
    Route::post('/match-requests', [MatchRequestController::class, 'store'])->name('match-requests.store');
    Route::get('/match-requests/{matchRequest}', [MatchRequestController::class, 'show'])->name('match-requests.show');
    Route::post('/match-requests/{matchRequest}/apply', [MatchRequestController::class, 'apply'])->name('match-requests.apply');
    Route::delete('/match-requests/{matchRequest}/cancel', [MatchRequestController::class, 'cancel'])->name('match-requests.cancel');
    Route::post('/match-requests/{matchRequest}/applications/{application}/accept', [MatchRequestController::class, 'acceptApplication'])->name('match-requests.applications.accept');
    Route::delete('/match-requests/{matchRequest}/applications/{application}', [MatchRequestController::class, 'cancelApplication'])->name('match-requests.applications.cancel');
    Route::get('/my-match-requests', [MatchRequestController::class, 'myRequests'])->name('match-requests.my-requests');
    Route::get('/my-match-applications', [MatchRequestController::class, 'myApplications'])->name('match-requests.my-applications');

    // Match result routes
    Route::get('/matches/{match}/results', [MatchResultController::class, 'show'])->name('matches.results.show');
    Route::post('/matches/{match}/results', [MatchResultController::class, 'store'])->name('matches.results.store');
    Route::post('/matches/{match}/results/confirm', [MatchResultController::class, 'confirm'])->name('matches.results.confirm');
    Route::post('/matches/{match}/results/dispute', [MatchResultController::class, 'dispute'])->name('matches.results.dispute');
    Route::post('/matches/{match}/results/edit', [MatchResultController::class, 'edit'])->name('matches.results.edit');
});
