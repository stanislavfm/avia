<?php

return [

    'one_query_parameter' => 'It needs to specify one query parameter.',
    'no_airports_found' => 'No airports found.',
    'no_token_found' => 'No token found.',
    'no_flights_found' => 'No flights found.',
    'no_transporters_found' => 'No transporters found.',
    'departure_time_without' => 'Invalid request parameters. Departure time should be specified without departure time from or departure time to fields.',
    'arrival_time_without' => 'Invalid request parameters. Arrival time should be specified without arrival time from or arrival time to fields.',
    'both_departure' => 'Invalid request parameters. It should be specified both departure time from and departure time to fields.',
    'both_arrival' => 'Invalid request parameters. It should be specified both arrival time from and arrival time to fields.',
    'departure_time_less' => 'Departure time must be less than arrival time.',
    'departure_arrival_different' => 'Departure airport and arrival airport must be different.',
    'departure_time_less_current' => 'Departure time must be less than current arrival time.',
    'arrival_time_greater_current' => 'Arrival time must be greater than current departure time.',
    'departure_is_arrival' => 'Given departure airport is current arrival airport.',
    'arrival_is_departure' => 'Given arrival airport is current departure airport.',
    'token_not_exists' => 'Given token is not exists.',
    'token_is_expired' => 'Given token is expired.',
    'token_no_permissions' => 'Given token does not have permission to this method.'
];