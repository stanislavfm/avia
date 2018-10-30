<?php

use App\Transporter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransportersTable extends Migration {

	public function up()
	{
		Schema::create('transporters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code', Transporter::CODE_LENGTH)->unique();
			$table->string('name')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('transporters');
	}
}