<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
    	$dt = new DateTime();
	    $dt = $dt->format('Y-m-d H:i:s');
        
        DB::table('users')->delete();
        DB::table('users')->insert([
        	['id' => 1, 'user_role_id' => 1, 'slug' => 'akash', 'fname' => 'Akash', 'lname' => 'Yadav', 'email' => 'akash.axovel@gmail.com', 'phone' => '9675382098', 'class' => '', 'address' => '', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'pin_code' => '110058', 'password' => Hash::make('admin123'), 'image' => '', 'is_blocked' => 0, 'created_at' => $dt, 'updated_at' => $dt],
        	['id' => 2, 'user_role_id' => 2, 'slug' => 'abhi', 'fname' => 'Abhijeet', 'lname' => 'Sharma', 'email' => 'abhijeet.axovel@gmail.com', 'phone' => '9999999999', 'class' => '', 'address' => '', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'pin_code' => '110058', 'password' => Hash::make('admin123'), 'image' => '', 'is_blocked' => 0, 'created_at' => $dt, 'updated_at' => $dt]
        ]);
    }
}
