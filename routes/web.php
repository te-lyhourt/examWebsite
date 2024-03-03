<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function(){
    if (Auth::check()) {
        return redirect()->route('page.group');
    }
    else{
        return Inertia::render('Auth/Login');
    }
})->name('login');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //user
    Route::get('/user',[UserController::class,'all_user'])->name('page.user');
    Route::post('/user/add',[UserController::class,'add_user'])->name('user.add');

    //project
    Route::get('/project',[ProjectController::class,'index'])->name('page.project');
    //group 
    Route::get('/group',[GroupController::class,'index'])->name('page.group');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
