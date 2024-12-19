<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn () => auth()->user());

    Route::group(['prefix' => 'organization'], function() {
        Route::get('/', fn () => auth()->organization());
        Route::post('/change', [OrganizationController::class, 'change']);
    });

    Route::group(['prefix' => 'organizations'], function() {
        Route::get('/', [OrganizationController::class, 'index']);
        Route::post('/', [OrganizationController::class, 'store']);

        Route::group(['prefix' => '{organization}'], function() {
            Route::get('/', [OrganizationController::class, 'show']);
            Route::put('/', [OrganizationController::class, 'update']);
            Route::delete('/', [OrganizationController::class, 'destroy']);

            Route::group(['prefix' => 'members'], function() {
                Route::get('/', [OrganizationUserController::class, 'index']);
                Route::post('/', [OrganizationUserController::class, 'store']);

                Route::group(['prefix' => '{member}'], function() {
                    Route::get('/', [OrganizationUserController::class, 'show']);
                    Route::put('/', [OrganizationUserController::class, 'update']);
                    Route::delete('/', [OrganizationUserController::class, 'destroy']);
                });
            });
        });
    });

    Route::group(['prefix' => 'projects'], function() {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);

        Route::group(['prefix' => '{project}'], function() {
            Route::get('/', [ProjectController::class, 'show']);
            Route::put('/', [ProjectController::class, 'update']);
            Route::delete('/', [ProjectController::class, 'destroy']);
        });
    });
});
