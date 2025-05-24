<?php
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->group( function () {

    Route::get('invitations/datatable'	,'InvitationController@datatable')
        ->name('invitations.datatable');

    Route::get('invitations/rejected_invitations'	,'InvitationController@rejected_invitations')
        ->name('invitations.rejected');

    Route::get('invitations/attended_invitations'	,'InvitationController@attended_invitations')
        ->name('invitations.attended');

    Route::get('invitations/pending_invitations'	,'InvitationController@pending_invitations')
        ->name('invitations.pending');

    Route::get('invitations/active_invitations'	,'InvitationController@active_invitations')
        ->name('invitations.active');

    Route::get('invitations/{id}/copy'	,'InvitationController@copy')
        ->name('invitations.copy');

    Route::get('invitations/deletes'	,'InvitationController@deletes')
        ->name('invitations.deletes');

    Route::post('invitations/deleteMediaFiles','InvitationController@deleteMediaFiles')->name('invitations.deleteMediaFiles');


    Route::resource('invitations','InvitationController')->names('invitations');
});
