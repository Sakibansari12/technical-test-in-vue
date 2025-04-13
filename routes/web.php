<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;


Route::get('/', [IndexController::class, 'index'])->name('index');




Route::get('/config/clear', function () {
    Artisan::call("config:clear");
    Artisan::call("cache:clear");
    Artisan::call("route:clear");
    dd('Config cleared successfully.');
});

Route::get('/update/ru/price', function () {
    Artisan::call("urpp:job");
    dd('Config cleared successfully.');
});

Route::get('/db/migrate', function () {
    Artisan::call("migrate");
    dd('Migration Completed successfully.');
});


Route::get('/{any}', function () {
    return view('welcome');
})->where("any",".*");



// ========================================= Routes =======================================================//




