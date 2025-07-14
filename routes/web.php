<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CohortsController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilityController;
use App\Models\Filter;
use App\Models\FilterValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
  return Auth::check()
    ? redirect()->route('dashboard') // dashboard
    : redirect()->route('auth-login-basic'); // login
})->name('root');

// Main Page Route
Route::get('/dashboard', [HomePage::class, 'index'])->name('dashboard');

// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/auth-login-post', [LoginBasic::class, 'login'])->name('auth-login-post');
Route::get('/auth-logout', [LoginBasic::class, 'logout'])->name('auth-logout');

Route::prefix('users')->group(function () {
  Route::get('/', [UserController::class, 'index'])->name('users.index');
  Route::post('/', [UserController::class, 'store'])->name('users.store');
  Route::get('/archive/{id}', [UserController::class, 'archive'])->name('users.archive');
  Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
});

Route::prefix('campaign')->group(function () {
  Route::get('/', [CampaignController::class, 'index'])->name('campaign.index');
  Route::get('/add', [CampaignController::class, 'add'])->name('campaign.add');
  Route::get('/getarchive', [CampaignController::class, 'getarchive'])->name('campaign.getarchive');
  Route::post('/', [CampaignController::class, 'store'])->name('campaign.store');
  Route::get('/archive/{id}', [CampaignController::class, 'archive'])->name('campaign.archive');
  Route::get('/delete/{id}', [CampaignController::class, 'destroy'])->name('campaign.delete');
  Route::get('getTargetAudience', [CampaignController::class, 'getTargetAudience'])->name('getTargetAudience');
});

// Utilities Routes
Route::prefix('utilities')
  ->name('utilities.')
  ->group(function () {
    Route::get('/demographics', [UtilityController::class, 'demographics'])->name('demographics');
    Route::post('/addCountry', [UtilityController::class, 'addCountry'])->name('addCountry');
    Route::post('/deleteCountry/{id}', [UtilityController::class, 'deleteCountry'])->name('deleteCountry');
    Route::post('/updateCountry/{id}', [UtilityController::class, 'updateCountry'])->name('updateCountry');
    Route::get('/leftPercentage/{id}', [UtilityController::class, 'leftPercentage'])->name('leftPercentage');

    Route::get('/addstate/{mode}', [UtilityController::class, 'addstate'])->name('states.add');
    Route::post('/createstate', [UtilityController::class, 'createstate'])->name('states.create');
    Route::post('/updatestate/{state}', [UtilityController::class, 'updatestate'])->name('states.update');
    Route::get('/get-states/{countryId}', [UtilityController::class, 'getStates']);

    Route::get('/divisions', [UtilityController::class, 'division'])->name('division');

    Route::post('/age-ranges', [UtilityController::class, 'storeAgeRange'])->name('storeAgeRange');
    Route::delete('/age-ranges/{id}', [UtilityController::class, 'deleteAgeRange'])->name('deleteAgeRange');

    Route::post('/devices', [UtilityController::class, 'storeDevice'])->name('storeDevice');
    Route::delete('/devices/{id}', [UtilityController::class, 'deleteDevice'])->name('deleteDevice');

    Route::post('/genders', [UtilityController::class, 'storeGender'])->name('storeGender');
    Route::delete('/genders/{id}', [UtilityController::class, 'deleteGender'])->name('deleteGender');

    //filter crud
    Route::post('/filters/store', [FilterController::class, 'store'])->name('filters.store');
    Route::post('/filter_values/store', [FilterController::class, 'storeFilterVal'])->name('filter_values.store');
    Route::get('/filter_values/delete/{id}', [FilterController::class, 'deleteFilterVal'])->name(
      'filter_values.delete'
    );
    Route::post('/filter_values/update/{id}', [FilterController::class, 'updateFilterVal'])->name(
      'filter_values.update'
    );
    Route::post('/filters/update/{id}', [FilterController::class, 'update'])->name('filters.update');
    Route::get('/filters/delete/{id}', [FilterController::class, 'delete'])->name('filters.delete');
    Route::get('/filters/changeStatus/{id}', [FilterController::class, 'changeStatus'])->name('filters.changeStatus');
    Route::get('/filters/{parent_id?}', [FilterController::class, 'index'])->name('filters');
    Route::get('/getfiltervalues/{id}', [FilterController::class, 'getfiltervalues'])->name('getfiltervalues');
    Route::get('/getChildFilters/{parentId}', [FilterController::class, 'getChildFilters']);
    Route::get('/getDivisionChildren/{divisionId}', [FilterController::class, 'getDivisionChildren']);
    Route::post('/setOrder/{id}', [FilterController::class, 'setOrder'])->name('setOrder');

    //location crud
    Route::get('/locations/view/{parent_id?}', [LocationController::class, 'view'])->name('locations.view');
    Route::get('/locations/create/{parent_id?}', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations/store', [LocationController::class, 'store'])->name('locations.store');
    Route::post('/locations/storefiltervalues', [LocationController::class, 'storefiltervalues'])->name(
      'locations.storefiltervalues'
    );
    Route::get('/locations/details/{id}/{gender_id}', [LocationController::class, 'addFilterDetails'])->name(
      'locations.addFilterDetails'
    );
    Route::get('/locations/details/{id}', [LocationController::class, 'addDetails'])->name('locations.addDetails');
    Route::post('/locations/updateDetails/{id}', [LocationController::class, 'updateDetails'])->name(
      'locations.updateDetails'
    );
    Route::post('/locations/update/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::get('/locations/delete/{id}', [LocationController::class, 'destroy'])->name('locations.delete');
    Route::get('/locations/{parent_id?}', [LocationController::class, 'index'])->name('locations');
    Route::post('/getchildlocations', [LocationController::class, 'getChildLocations']);

    Route::get('/getfilterdetails', [LocationController::class, 'getfilterdetails'])->name('getfilterdetails');
  });

Route::prefix('analytics')->group(function () {
  Route::get('/', [AnalyticsController::class, 'index'])->name('analytics.index');
  Route::get('/testcron', [AnalyticsController::class, 'testcron'])->name('analytics.testcron');
});

Route::get('/filtervaluesall',function () {
  return FilterValue::get();
});

Route::get('/filtersall',function () {
  return Filter::get();
});

Route::get('/truncate-filters', function () {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('filters')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return 'filters table truncated successfully.';
});

Route::get('/truncate-filters', function () {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('filter_values')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return 'filter values table truncated successfully.';
});
