<?php

use App\Airport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAirportsTable extends Migration {

	public function up()
	{
		Schema::create('airports', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code', Airport::CODE_LENGTH)->unique();
			$table->string('name')->nullable();
			$table->string('location', Airport::LOCATION_LENGTH);
			$table->tinyInteger('timezoneOffset');
		});
	}

	public function down()
	{
		Schema::drop('airports');
	}
}