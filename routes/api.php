<?php

Route::get('token', 'Api\AuthController@getToken');
Route::post('token', 'Api\AuthController@createToken');
Route::put('token', 'Api\AuthController@updateToken');
Route::delete('token', 'Api\AuthController@deleteToken');

Route::group(['middleware' => ['api.auth']], function () {

    Route::get('airports', 'Api\AirportController@getAirports');
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

});

