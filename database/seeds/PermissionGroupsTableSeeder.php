<?php

use Illuminate\Database\Seeder;

class PermissionGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_groups')->delete();
    	$dt = new DateTime();
	    $dt = $dt->format('Y-m-d H:i:s');
        
        DB::table('permission_groups')->delete();
        DB::table('permission_groups')->insert([
            ['id' => 1, 'name' => 'Permission Group 1', 'description' => 'Permission Group 1', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 2, 'name' => 'Permission Group 2', 'description' => 'Permission Group 2', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 3, 'name' => 'Permission Group 3', 'description' => 'Permission Group 3', 'created_at' => $dt, 'updated_at' => $dt],
            ['id' => 4, 'name' => 'Permission Group 4', 'description' => 'Permission Group 4', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
