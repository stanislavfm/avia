<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAirportsTable extends Migration {

	public function up()
	{
		Schema::create('airports', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code', 3)->unique();
			$table->string('name')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('airports');
	}
}