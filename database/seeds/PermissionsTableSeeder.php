<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
    	$dt = new DateTime();
	    $dt = $dt->format('Y-m-d H:i:s');
        
        DB::table('permissions')->delete();
        DB::table('permissions')->insert([
        	['id' => 1, 'user_role_id' => 1, 'permission_group_id' => 1, 'name' => 'Permission 1', 'description' => 'Permission 1', 'type' => '', 'is_blocked' => 0, 'created_at' => $dt, 'updated_at' => $dt],
        	['id' => 2, 'user_role_id' => 2, 'permission_group_id' => 2, 'name' => 'Permission 2', 'description' => 'Permission 2', 'type' => '', 'is_blocked' => 0, 'created_at' => $dt, 'updated_at' => $dt],
        	['id' => 3, 'user_role_id' => 3, 'permission_group_id' => 3, 'name' => 'Permission 3', 'description' => 'Permission 3', 'type' => '', 'is_blocked' => 0, 'created_at' => $dt, 'updated_at' => $dt]
        ]);
    }
}
