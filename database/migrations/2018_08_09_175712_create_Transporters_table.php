<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransportersTable extends Migration {

	public function up()
	{
		Schema::create('Transporters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code');
			$table->string('name')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('Transporters');
	}
}