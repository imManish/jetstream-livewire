<?php

use App\Http\Livewire\Frontpage;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::group(['middleware' => [
        'auth:sanctum',
        'verified',
        'accessrole',
    ]], function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pages', function () {
        return view('admin.pages');
    })->name('pages');

    Route::get('/navigation-menus', function () {
        return view('admin.navigation-menus');
    })->name('navigation-menus');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/user-permissions', function () {
        return view('admin.user-permissions');
    })->name('user-permissions');

    Route::get('/organizations', function () {
        return view('admin.organizations');
    })->name('organizations');

    Route::get('/projects', function () {
        return view('manager.projects');
    })->name('projects');

    Route::get('/items', function () {
        return view('manager.items');
    })->name('items');

    Route::get('/sub-items', function () {
        return view('manager.sub-items');
    })->name('sub-items');
});

Route::get('/{urlslug}', Frontpage::class);
Route::get('/', function() {
    return redirect()->route('login');
});
