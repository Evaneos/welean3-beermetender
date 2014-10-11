<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestSessionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rest_sessions', function(Blueprint $table)
		{
			$table->increments('id');
		    $table->integer('user_id')->unique();
		    $table->string('token')->unique();
			$table->string('facebook_token')->unique();
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
		Schema::table('rest_sessions', function(Blueprint $table)
		{
			//
		});
	}

}
