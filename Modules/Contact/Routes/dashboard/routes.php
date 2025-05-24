<?php

Route::group(['prefix' => 'contacts'], function () {
    Route::get('/', 'ContactController@index')
    ->name('dashboard.contacts.index');

    Route::get('datatable', 'ContactController@datatable')
    ->name('dashboard.contacts.datatable');

    Route::get('create', 'ContactController@create')
    ->name('dashboard.contacts.create');

    Route::get('import', 'ContactController@import')
        ->name('dashboard.contacts.import');

    Route::get('inviteesContacts', 'ContactController@inviteesContacts')
        ->name('dashboard.contacts.inviteesContacts');

    Route::post('/', 'ContactController@store')
        ->name('dashboard.contacts.store');

    Route::post('/import_file', 'ContactController@import_file')
        ->name('dashboard.contacts.import_file');

    Route::get('{id}/edit', 'ContactController@edit')
    ->name('dashboard.contacts.edit');

    Route::put('{id}', 'ContactController@update')
    ->name('dashboard.contacts.update');

    Route::delete('{id}', 'ContactController@destroy')
    ->name('dashboard.contacts.destroy');

    Route::get('deletes', 'ContactController@deletes')
    ->name('dashboard.contacts.deletes');

    Route::get('{id}', 'ContactController@show')
    ->name('dashboard.contacts.show');


});


