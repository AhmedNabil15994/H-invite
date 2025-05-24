<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/invitations' ], function () {
    Route::get('/statistics', 'InvitationController@statistics')->name('api.invitations.statistics');
    Route::get('/accepted', 'InvitationController@accepted')->name('api.invitations.accepted');
    Route::get('/rejected', 'InvitationController@rejected')->name('api.invitations.rejected');
    Route::get('/attended', 'InvitationController@attended')->name('api.invitations.attended');
    Route::get('/pending', 'InvitationController@pending')->name('api.invitations.pending');
    Route::get('/active', 'InvitationController@active')->name('api.invitations.active');
    Route::post('/send_message', 'InvitationController@send_message')->name('api.invitations.send_message');

});
