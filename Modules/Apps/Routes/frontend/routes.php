<?php

//Route::get('/',function (){
//    return redirect()->route('dashboard.login');
//})->name('frontend.home');
Route::get('/about-us', 'AppsController@about_us')->name('frontend.about_us');
Route::get('/terms', 'AppsController@terms')->name('frontend.terms');
Route::get('/faq', 'AppsController@faq')->name('frontend.faq');
Route::get('/contact-us', 'AppsController@contact_us')->name('frontend.contact_us');
Route::post('/contact-us', 'AppsController@post_contact_us')->name('frontend.post_contact_us');
Route::get('/coming-soon', 'AppsController@coming_soon')->name('frontend.coming_soon');

Route::get('invitations/redeem/{code}'	,[\Modules\Party\Http\Controllers\Dashboard\InvitationController::class,'redeem'])
    ->name('frontend.invitations.redeem');

Route::get('/parties/{party_id}/invitations/actions/{contact_id}'	,[\Modules\Party\Http\Controllers\Dashboard\PartyController::class,'actions'])
    ->name('frontend.parties.actions');

Route::post('/parties/{party_id}/invitations/actions/{contact_id}'	,[\Modules\Party\Http\Controllers\Dashboard\PartyController::class,'postActions'])
    ->name('frontend.parties.postActions');

Route::get('/parties/{party_id}/invitations/actions/{contact_id}/download'	,[\Modules\Party\Http\Controllers\Dashboard\PartyController::class,'download'])
    ->name('frontend.parties.download');
