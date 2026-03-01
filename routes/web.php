<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyBuilderController;
use App\Http\Controllers\HouseDescriptionController;
use App\Http\Controllers\HouseMemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResponseController;



Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, array_values(config('app.available_locales')))) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/survey-report', [DashboardController::class, 'surveyReport'])->middleware(['auth', 'verified'])->name('dashboard.survey-report');
Route::post('/dashboard/survey-report/pin', [DashboardController::class, 'pinChart'])->middleware(['auth', 'verified'])->name('dashboard.survey-report.pin');
Route::get('/dashboard/members', [DashboardController::class, 'members'])->middleware(['auth', 'verified'])->name('dashboard.members');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('surveyform', SurveyBuilderController::class);
    Route::resource('house-description', HouseDescriptionController::class);
    Route::resource('house-member', HouseMemberController::class);
    Route::post('/house-member/{id}/mark-demise', [HouseMemberController::class, 'markDemise'])->name('house-member.mark-demise');
    Route::get('/survey/ward/{ward}/lookup-data', [HouseDescriptionController::class, 'getLookupData']);

    
    Route::post('/survey-sections/reorder', [SurveyBuilderController::class, 'reorder'])->name('survey.sections.reorder');

    Route::get('/survey/ward/{ward}/sections', [HouseDescriptionController::class, 'getSectionsForWard'])
        ->name('survey.sections');

      
    Route::resource('survey-responses', ResponseController::class);   
    Route::get('/toles-by-ward', [ResponseController::class, 'getTolesByWard'])->name('toles.by.ward');

    Route::resource('users', \App\Http\Controllers\UserController::class);
});


require __DIR__.'/auth.php';