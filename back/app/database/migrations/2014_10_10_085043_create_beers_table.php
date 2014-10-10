<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beers', function(Blueprint $table)
		{
			$table->increments('id');
	        $table->integer('user_from_id');
	        $table->integer('user_to_id');
	        $table->integer('number');
	        $table->string('what');
	        $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('beers', function(Blueprint $table)
		{
			//
		});
	}

}
