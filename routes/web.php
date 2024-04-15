<?php

use App\Http\Controllers\answerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;

Route::get('/', function(){
    if (Auth::check()) {
        return redirect()->route('page.project');
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
    Route::post('/user/search',[UserController::class,'search'])->name('user.search');
    Route::delete('/user/delete',[UserController::class,'delete'])->name('user.delete');
    
    //project
    Route::get('/project',[ProjectController::class,'index'])->name('page.project');
    Route::post('/project/add',[ProjectController::class,'store'])->name('project.add');
    Route::get('/project/detail/{id}',[ProjectController::class,'projectDetail'])->name('page.projectDetail');
    Route::post('/project/group/{id}',[ProjectController::class,'addGroup'])->name('project.addGroup');
    Route::post('/project/admin/{id}',[ProjectController::class,'addAdmin'])->name('project.addAdmin');
    Route::delete('/project/delete',[ProjectController::class,'delete'])->name('project.delete');
    Route::delete('/project/removeGroup/{id}',[ProjectController::class,'removeGroup'])->name('project.removeGroup');
    Route::get('/project/test/{id}',[ProjectController::class,'testPage'])->name('page.test');
    Route::delete('/project/removeAdmin/{id}',[ProjectController::class,'removeAdmin'])->name('project.removeAdmin
    ');

    //group 
    Route::get('/group',[GroupController::class,'index'])->name('page.group');
    Route::post('/group/add',[GroupController::class,'store'])->name('group.add');
    Route::get('/group/detail/{id}',[GroupController::class,'groupDetail'])->name('page.groupDetail');
    Route::post('/group/user/{id}',[GroupController::class,'addUser'])->name('group.addUser');
    Route::post('/group/createUser/{id}',[GroupController::class,'createUser'])->name('group.createUser');
    Route::delete('/group/delete',[GroupController::class,'delete'])->name('group.delete');
    Route::delete('/group/removeUser/{id}',[GroupController::class,'removeUser'])->name('group.removeUser');
    
    //question
    Route::post('/question/add',[QuestionController::class,'store'])->name('question.add');
    Route::delete('/question/delete',[QuestionController::class,'delete'])->name('question.delete');

    //answer
    Route::post('/answer/add',[answerController::class,'store'])->name('answer.add');
    Route::post('/answer/update/{id}', [answerController::class, 'update'])->name('answer.update');
    Route::get('/answer/export/{id}', [answerController::class, 'export'])->name('answer.export');

    //progress
    Route::get('/group/progress/{group_id}/{project_id}',[GroupController::class,'progress'])->name('page.progress');
    Route::get('/group/progress_detail/{user_id}/{project_id}',[GroupController::class,'progressDetail'])->name('page.progress_detail');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
