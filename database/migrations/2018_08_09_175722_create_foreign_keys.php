<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('flights', function(Blueprint $table) {
			$table->foreign('departureAirportId')->references('id')->on('airports')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('flights', function(Blueprint $table) {
			$table->foreign('arrivalAirportId')->references('id')->on('airports')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('flights', function(Blueprint $table) {
			$table->foreign('transporterId')->references('id')->on('transporters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('flights', function(Blueprint $table) {
			$table->dropForeign('flight_departureAirport_foreign');
		});
		Schema::table('flights', function(Blueprint $table) {
			$table->dropForeign('flight_arrivalAirport_foreign');
		});
		Schema::table('flight', function(Blueprint $table) {
			$table->dropForeign('flight_transporter_foreign');
		});
	}
}