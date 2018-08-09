<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('Flights', function(Blueprint $table) {
			$table->foreign('departureAirport')->references('id')->on('Airports')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('Flights', function(Blueprint $table) {
			$table->foreign('arrivalAirport')->references('id')->on('Airports')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('Flights', function(Blueprint $table) {
			$table->foreign('transporter')->references('id')->on('Transporters')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('Flights', function(Blueprint $table) {
			$table->dropForeign('Flights_departureAirport_foreign');
		});
		Schema::table('Flights', function(Blueprint $table) {
			$table->dropForeign('Flights_arrivalAirport_foreign');
		});
		Schema::table('Flights', function(Blueprint $table) {
			$table->dropForeign('Flights_transporter_foreign');
		});
	}
}