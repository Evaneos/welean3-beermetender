<?php
class RestSessionTableSeeder extends Seeder {
 
  public function run()
  {
      DB::table('rest_sessions')->delete();
 
      RestSession::create(array(
          'id' => 1,
          'user_id' => 1,
          'token' => '456789',
          'facebook_token' => '12345'
      ));
  }
 
}