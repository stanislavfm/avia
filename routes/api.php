<?php

Route::get('airports', 'Api\GetController@airports');
Route::get('transporters', 'Api\GetController@transporters');
Route::get('flights', 'Api\GetController@flights');

Route::post('airport', 'Api\PostController@airport');
Route::post('transporter', 'Api\PostController@transporter');
Route::post('flight', 'Api\PostController@flight');

Route::put('airport', 'Api\PutController@airport');
Route::put('transporter', 'Api\PutController@transporter');
Route::put('flight', 'Api\PutController@airport');

Route::delete('airport', 'Api\DeleteController@airport');
Route::delete('transporter', 'Api\DeleteController@transporter');
Route::delete('flight', 'Api\DeleteController@flight');
