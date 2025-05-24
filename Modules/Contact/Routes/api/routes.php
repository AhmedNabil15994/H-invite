<?php


Route::group(['prefix' => '/contacts' ], function () {
    Route::get('/', 'ContactController@index')->name('api.contacts.index');
    Route::get('/{id}', 'ContactController@show')->name('api.contacts.show');
    Route::post('/store', 'ContactController@store')->name('api.contacts.store');
    Route::post('/attachRelated', 'ContactController@attachRelated')->name('api.contacts.attachRelated');
    Route::post('/removeInvitation', 'ContactController@removeInvitation')->name('api.contacts.removeInvitation');
    Route::post('/uploadExcel', 'ContactController@uploadExcel')->name('api.contacts.uploadExcel');
    Route::post('/syncPhoneContacts', 'ContactController@syncPhoneContacts')->name('api.contacts.syncPhoneContacts');
});

