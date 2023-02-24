<?php

use App\User;
use Illuminate\Database\Seeder;

class UserApproveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::query()->update(['isApprove' => true]);
    }
}
