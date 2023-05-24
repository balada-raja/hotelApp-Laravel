<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tipeKamarController;
use App\Http\Controllers\kamarController;
use App\Http\Controllers\pemesananController;
use App\Http\Controllers\resepsionisController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/login', [AuthController::class, 'login'])->name('login');
//Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::prefix('admin')->controller(AuthController::class)->group(function () {

    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout');
        Route::group(['middleware' => ['api.admin']], function () {
            //semua yang bisa diakses oleh resepsionis dan tamu bisa diakses oleh admin
            Route::controller(tipeKamarController::class)->group(function () {
                Route::post('/tipe', 'store');
                Route::put('/tipe/{id}', 'update');
                Route::delete('/tipe/{id}', 'delete');
            });
            Route::controller(kamarController::class)->group(function () {
                Route::post('/kamar', 'store');
                Route::put('/kamar/{id}', 'update');
                Route::delete('/kamar/{id}', 'delete');
            });
    
        });
    
        Route::group(['middleware' => ['api.resepsionis']], function () {
            //semua yang bisa diakses oleh tamu akan otomatis bisa diakses oleh resepsionis
            Route::controller(pemesananController::class)->group(function () {
                //pemesanan
                //Route::post('/pemesanan', 'store');
                Route::put('/pemesanan/{id}', 'update');
            
                //detail pemesanan
                //Route::post('/detail', 'storeDetail');
                Route::put('/detail/{id}', 'updateDetail');
            });
            Route::controller(kamarController::class)->group(function(){
                Route::get('/kamar', 'show');
                Route::get('/kamar/{id}', 'detail');
            });
    
        });
        
        Route::group(['middleware' => ['api.tamu']], function(){

            Route::controller(pemesananController::class)->group(function () {
                //pemesanan
                Route::post('/pemesanan', 'store');
                Route::get('/pemesanan', 'show');
                Route::get('/pemesanan/{id}', 'detail');
                
                //detail pemesanan
                Route::get('/detail', 'showDetail');
                Route::get('/detail/{id}', 'detailDetail');
            });

        });

        Route::controller(tipeKamarController::class)->group(function () {
            Route::get('/tipe', 'show');
            Route::get('/tipe/{id}', 'detail');
        });
        
    //Route::post('me', 'me');
    });

});

