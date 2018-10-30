<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFlightsTable extends Migration {

	public function up()
	{
		Schema::create('flights', function(Blueprint $table) {
			$table->increments('id');
			$table->string('number')->unique();
            $table->unsignedInteger('transporterId');
            $table->unsignedInteger('departureAirportId');
            $table->unsignedInteger('arrivalAirportId');
			$table->datetime('departureTime');
			$table->datetime('arrivalTime');
		});
	}

	public function down()
	{
		Schema::drop('flights');
	}
}