<?php

Route::group(['prefix' => 'api/admin'], function () {
    Route::post('login', '\Cmsify\Cmsify\Http\Controllers\LoginController@login');

    Route::group(['middleware' => 'admin-auth'], function () {

        /**
         * Auth
         */
        Route::post('refresh-token', '\Cmsify\Cmsify\Http\Controllers\LoginController@refresh');
        Route::post('logout', '\Cmsify\Cmsify\Http\Controllers\LoginController@logout');
        Route::get('profile', '\Cmsify\Cmsify\Http\Controllers\LoginController@profile');

        /**
         * Resources
         */
        Route::get('resource-routes', '\Cmsify\Cmsify\Http\Controllers\ResourceController@index');
        Route::get('articles/data-table', '\App\Http\Controllers\BlogController@dataTable');
        Route::resource('articles', '\App\Http\Controllers\BlogController');

    });
});

