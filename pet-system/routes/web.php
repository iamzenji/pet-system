<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TypeController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\RoleController;


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

// Roles
Route::group(['middleware' => ['auth', 'role:superadministrator|administrator|user|reader']], function () {
    // Route::get('/account', [UserController::class, 'account'])->name('account');
    // Route::get('/accounts/data', [UserController::class, 'getUsersData'])->name('accounts.data');
    // Route::post('/accounts/update/{id}', [UserController::class, 'updateUser'])->name('accounts.update');
    // Route::delete('/accounts/delete/{id}', [UserController::class, 'deleteUser'])->name('accounts.delete');
    // Route::get('/roles', [RoleController::class, 'getRoles']);
    // Route::post('/assign-role', [UserController::class, 'createRoles'])->name('assign.role');
    // Route::post('/create-account', [UserController::class, 'createAccount'])->name('create.account');

    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/types/fetch', [TypeController::class, 'fetchTypes'])->name('types.fetch');
    Route::get('/types/fetch-breeds', [TypeController::class, 'fetchBreeds'])->name('types.fetchBreeds');
    Route::put('/pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');
    Route::get('/pets/manage', [TypeController::class, 'index'])->name('pets.manage');
    Route::get('/types/list', [TypeController::class, 'list']);
    Route::post('/types', [TypeController::class, 'store']);
    Route::delete('/types/{id}', [TypeController::class, 'destroy']);
    Route::put('/types/{id}', [TypeController::class, 'update']);

    Route::get('/pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/pet-list', [PetController::class, 'getPet'])->name('pets.getPet');
});



Auth::routes(['verify' => true]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
