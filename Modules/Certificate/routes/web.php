<?php

use Illuminate\Support\Facades\Route;
use Modules\Certificate\app\Http\Controllers\CertificateController;

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

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('certificate/get-courses-by-user/{user}', [CertificateController::class, 'getCoursesByUser'])->name('certificate.getCoursesByUser');
    Route::resource('certificate', CertificateController::class)->names('certificate');
});
