<?php

Route::get('airports', 'Api\AirportController@createAirport');
Route::get('transporters', 'Api\TransporterController@getTransporters');
Route::get('flights', 'Api\FlightController@getFlights');

Route::post('airport', 'Api\AirportController@createAirport');
Route::post('transporter', 'Api\TransporterController@createTransporter');
Route::post('flight', 'Api\FlightController@createFlight');

Route::put('airport', 'Api\AirportController@updateAirport');
Route::put('transporter', 'Api\TransporterController@updateTransporter');
Route::put('flight', 'Api\FlightController@updateFlight');

Route::delete('airport', 'Api\AirportController@deleteAirport');
Route::delete('transporter', 'Api\TransporterController@deleteTransporter');
Route::delete('flight', 'Api\FlightController@deleteFlight');

