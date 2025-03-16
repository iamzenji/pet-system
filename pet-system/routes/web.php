<?php
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\PetAnalyticsController;
use App\Http\Controllers\AdoptionController;
use Illuminate\Support\Facades\Auth;



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
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
});

// ADMIN
Route::group(['middleware' => ['auth', 'role:superadministrator|administrator']], function () {
    Route::get('/account', [UserController::class, 'account'])->name('account');
    Route::get('/accounts/data', [UserController::class, 'getUsersData'])->name('accounts.data');
    Route::post('/accounts/update/{id}', [UserController::class, 'updateUser'])->name('accounts.update');
    Route::delete('/accounts/delete/{id}', [UserController::class, 'deleteUser'])->name('accounts.delete');
    Route::get('/roles', [UserController::class, 'getRoles']);
    Route::post('/assign-role', [UserController::class, 'createRoles'])->name('assign.role');
    Route::post('/accounts/register', [UserController::class, 'store'])->name('accounts.register');

    // ROLES MANAGEMENT
    Route::get('/role-management', [UserController::class, 'roles'])->name('roles.display');
    Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/role-manage', [RoleController::class, 'index'])->name('index');
    Route::get('/data', [RoleController::class, 'fetchRoles'])->name('data');
    Route::post('/role-data', [RoleController::class, 'store'])->name('store');
    Route::put('/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('delete');
    });

});

// USER AND READER
Route::group(['middleware' => ['auth', 'role:superadministrator|administrator|user|reader']], function () {

    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets/store', [PetController::class, 'store'])->name('pets.store');
    Route::get('/types/fetch', [TypeController::class, 'fetchTypes'])->name('types.fetch');
    Route::get('/types/fetch-breeds', [TypeController::class, 'fetchBreeds'])->name('types.fetchBreeds');
    Route::put('/pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');
    Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/pet-list', [PetController::class, 'getPet'])->name('pets.getPet');

    // TYPE
    Route::get('/pets/manage', [TypeController::class, 'index'])->name('pets.manage');
    Route::get('/types/list', [TypeController::class, 'list']);
    Route::post('/types', [TypeController::class, 'store']);
    Route::delete('/types/{id}', [TypeController::class, 'destroy']);
    Route::put('/types/{id}', [TypeController::class, 'update']);

    // ANALYTICS
    Route::get('/pets/analytics', [PetAnalyticsController::class, 'index'])->name('pets.analytics');
    Route::get('/pets/analytics', [PetAnalyticsController::class, 'index'])->name('pets.analytics');
    Route::get('/pets/analytics/data', [PetAnalyticsController::class, 'getAnalyticsData'])->name('pets.analytics.data');
    Route::get('/pets/analytics/breeds/{type}', [PetAnalyticsController::class, 'getBreedAnalyticsData']);
    Route::get('/users/analytics/data', [PetAnalyticsController::class, 'getUserAnalytics'])->name('users.analytics.data');

    // EMAIL INQUIRY
    Route::get('/adoptions', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::get('/adoptions/list', [AdoptionController::class, 'list'])->name('adoptions.list');
    Route::delete('/adoptions/{id}', [AdoptionController::class, 'destroy'])->name('adoptions.destroy');
    Route::patch('/adoptions/{id}/status', [AdoptionController::class, 'updateStatus']);

    // FILTER
    Route::get('/filter', [PetController::class, 'filter'])->name('filter.display');
    Route::get('/pet-types/data', [PetController::class, 'getTypes'])->name('pet.types.data');
    Route::post('/pet-types/store', [PetController::class, 'store_pets'])->name('pet.types.store');
    Route::delete('/pet-types/delete/{id}', [PetController::class, 'destroy_types'])->name('pet.types.delete');
    Route::get('/pet-types/{id}/edit', [PetController::class, 'edit_types'])->name('pet.types.edit');
    Route::put('/pet-types/update/{id}', [PetController::class, 'update_types'])->name('pet.types.update');





});

// PUBLIC VIEW

Route::post('/adoption/store', [AdoptionController::class, 'store'])->name('adoption.store');
Route::get('/adoption/pets', [AdoptionController::class, 'showAvailablePets'])->name('adoption.pets');

Auth::routes(['verify' => true]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
