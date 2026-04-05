<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyBuilderController;
use App\Http\Controllers\HouseDescriptionController;
use App\Http\Controllers\HouseMemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\ResourceMappingController;
use App\Http\Controllers\ImportantSiteController;


Route::get('/', [WelcomeController::class , 'index'])->name('welcome');

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, array_values(config('app.available_locales')))) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');

Route::middleware('auth')->prefix('admin')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class , 'index'])->middleware(['verified'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class , 'exportCsv'])->middleware(['verified'])->name('dashboard.export');
    Route::get('/dashboard/survey-report', [DashboardController::class , 'surveyReport'])->middleware(['verified'])->name('dashboard.survey-report');
    Route::post('/dashboard/survey-report/pin', [DashboardController::class , 'pinChart'])->middleware(['verified'])->name('dashboard.survey-report.pin');
    Route::get('/dashboard/members', [DashboardController::class , 'members'])->middleware(['verified'])->name('dashboard.members');

    // Profile Routes
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // Survey & Householder Management
    Route::resource('surveyform', SurveyBuilderController::class);
    Route::get('/house-description/create/{ward}', [HouseDescriptionController::class , 'createWithWard'])->name('house-description.create-with-ward');
    Route::resource('house-description', HouseDescriptionController::class);
    Route::resource('house-member', HouseMemberController::class);
    Route::post('/house-member/{id}/mark-demise', [HouseMemberController::class , 'markDemise'])->name('house-member.mark-demise');
    Route::get('/survey/ward/{ward}/lookup-data', [HouseDescriptionController::class , 'getLookupData']);
    Route::post('/survey-sections/reorder', [SurveyBuilderController::class , 'reorder'])->name('survey.sections.reorder');
    Route::get('/survey/ward/{ward}/sections', [HouseDescriptionController::class , 'getSectionsForWard'])->name('survey.sections');

    // Responses & Users
    Route::resource('survey-responses', ResponseController::class);
    Route::get('/toles-by-ward', [ResponseController::class , 'getTolesByWard'])->name('toles.by.ward');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Palika & Ward Routes
    Route::get('/palika', [AdministrationController::class , 'index'])->name('palika.index');
    Route::get('/palika/admin/create', [AdministrationController::class , 'createAdmin'])->name('palika.admin.create');
    Route::post('/palika/admin/store', [AdministrationController::class , 'storeAdmin'])->name('palika.admin.store');
    Route::get('/palika/admin/{admin}/edit', [AdministrationController::class, 'editAdmin'])->name('palika.admin.edit');
    Route::put('/palika/admin/{admin}', [AdministrationController::class, 'updateAdmin'])->name('palika.admin.update');
    Route::resource('wards', \App\Http\Controllers\WardController::class);
    Route::resource('resource-mapping', ResourceMappingController::class);


    Route::resource('important-site', ImportantSiteController::class);
    Route::delete('/important-site/{importantSite}/photo', [ImportantSiteController::class, 'deletePhoto'])->name('important-site.deletePhoto');
});


require __DIR__ . '/auth.php';