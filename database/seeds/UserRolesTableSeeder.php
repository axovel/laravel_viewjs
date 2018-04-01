<?php

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->delete();
    	$dt = new DateTime();
	    $dt = $dt->format('Y-m-d H:i:s');
        
        DB::table('user_roles')->delete();
        DB::table('user_roles')->insert([
            ['id' => 1, 'name' => 'SuperAdmin', 'display_name' => 'Super Admin', 'description' => 'Super Admin', 'menu' => '', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 2, 'name' => 'Admin', 'display_name' => 'Admin', 'description' => 'Admin', 'menu' => '', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 3, 'name' => 'SubAdmin', 'display_name' => 'Sub Admin', 'description' => 'Sub Admin', 'menu' => '', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 4, 'name' => 'Teacher', 'display_name' => 'Teacher', 'description' => 'Teacher', 'menu' => '', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 5, 'name' => 'Student', 'display_name' => 'Student', 'description' => 'Student', 'menu' => '', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
