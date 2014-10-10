<?php
class BeerTableSeeder extends Seeder {
 
  public function run()
  {
      DB::table('beers')->delete();
 
      Beer::create(array(
          'id' => 1,
          'user_from_id' => 1,
          'user_to_id' => 2,
          'number' => 3
      ));

      Beer::create(array(
          'id' => 2,
          'user_from_id' => 2,
          'user_to_id' => 1,
          'number' => 1
      ));
  }
 
}