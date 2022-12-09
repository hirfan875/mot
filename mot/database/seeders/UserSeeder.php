<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\UserService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request = [
            'name' => 'Admin',
            'email' => 'info@mot.com',
            'password' => 'admin125'
        ];

        $userService = new UserService();
        $userService->create($request);
    }
}
