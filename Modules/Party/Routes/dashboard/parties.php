<?php
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->group( function () {

    Route::get('parties/datatable'	,'PartyController@datatable')
        ->name('parties.datatable');

    Route::get('parties/{id}/copy'	,'PartyController@copy')
        ->name('parties.copy');

    Route::get('parties/{id}/print'	,'PartyController@print')
        ->name('parties.print');

    Route::get('parties/{id}/sendStatistics'	,'PartyController@sendStatistics')
        ->name('parties.sendStatistics');

    Route::get('parties/deletes'	,'PartyController@deletes')
        ->name('parties.deletes');

    Route::get('parties/getContacts'	,'PartyController@getContacts')
        ->name('parties.getContacts');

    Route::get('parties/getByInvitee'	,'PartyController@getByInvitee')
        ->name('parties.getByInvitee');

    Route::post('parties/deleteMediaFiles','PartyController@deleteMediaFiles')->name('parties.deleteMediaFiles');


    Route::resource('parties','PartyController')->names('parties');
});
