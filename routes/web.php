<?php

use App\Http\Livewire\Pegawai;
use App\Http\Livewire\KelolaCuti;
use App\Http\Livewire\KelolaPresensi;
use App\Http\Livewire\MapLocation;
use Spatie\Permission\Models\Role;
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

Route::get('/', function () {
    return view('welcome');
    // $role = Role::first();
    // $role->givePermissionTo('kelola cuti', 'kelola lokasi', 'kelola pegawai', 'kelola presensi');
    // dd($role);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('role:admin')->get('/map', MapLocation::class)->name('lokasi');
Route::middleware('role:admin')->get('/pegawai', Pegawai::class)->name('pegawai');
Route::middleware('role:admin')->get('/cuti', KelolaCuti::class)->name('cuti');
Route::middleware('role:admin')->get('/presensi', KelolaPresensi::class)->name('presensi');