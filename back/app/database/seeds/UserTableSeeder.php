<?php
class UserTableSeeder extends Seeder {
 
  public function run()
  {
      DB::table('users')->delete();
 
      User::create(array(
          'id' => 1,
          'username' => 'firstuser',
          'password' => Hash::make('first_password')
      ));
 
      User::create(array(
          'id' => 2,
          'username' => 'seconduser',
          'password' => Hash::make('second_password')
      ));
  }
 
}