<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultUser = User::create([
            'name' => 'admin',
            'email' => 'admin@mailinator.com',
            'password' => bcrypt('password'),
        ]);
    
        Product::create([
            'name' => 'Default Product',
            'price' => 10.00,
            'status' => 'active',
            'type' => 'item',
            'user_id' => $defaultUser->id
        ]);
    }
}
