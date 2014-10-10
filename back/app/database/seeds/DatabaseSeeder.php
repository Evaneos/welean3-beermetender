<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
 
	    // Ajoutez ou décommentez cette ligne
	    $this->call('UserTableSeeder');
	}

}
