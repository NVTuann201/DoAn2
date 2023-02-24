<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSpecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sysadmin = Role::create(array(                
            'name' => 'Người dùng',
            'description'    => 'Người dùng',
            'code' => 'ND2019',
            'color' => 'danger'           
        ));
    }
}
