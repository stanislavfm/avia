<?php

Route::get('airport', 'Api\GetController@airport');
Route::get('airports', 'Api\GetController@airports');

Route::get('transporter', 'Api\GetController@transporter');
Route::get('transporters', 'Api\GetController@transporters');

Route::get('flight', 'Api\GetController@flight');
Route::get('flightsSearch', 'Api\GetController@flightsSearch');

Route::post('airport', 'Api\PostController@airport');
Route::post('transporter', 'Api\PostController@transporter');
Route::post('flight', 'Api\PostController@transporter');
