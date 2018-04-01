<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB:: table('user_roles')->insert(array(
        array('name' => 'admin', 'display_name' => 'Admin', 'description' => 'This is admin'),
        array('name' => 'student', 'display_name' => 'Student', 'description' => 'This is student'),
        array('name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'This is teacher')
      ))
    }
}
