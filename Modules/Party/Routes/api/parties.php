<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/parties' ], function () {
    Route::get('/', 'PartyController@index')->name('api.parties.index');
    Route::get('/{id}', 'PartyController@show')->name('api.parties.show');

});
