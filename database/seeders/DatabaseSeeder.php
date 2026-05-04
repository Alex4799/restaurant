<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Payment;
use App\Models\Category;
use App\Models\WebsiteInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Developer',
            'email' => 'mr.alex4799@gmail.com',
            'phone'=>'09980730638',
            'address'=>'Yangon',
            'password'=>Hash::make('alex@0912'),
            'role'=>'admin',
            'position'=>'developer',
        ]);

        WebsiteInfo::create([
            'name'=>'Restaurants',
            'contact'=>'Phone - 09123123123'
        ]);

        Category::crate([
            'name'=>'Other',
        ]);

        Payment::create([
            'name'=>'Cash',
            'active'=>1,
        ]);
    }
}
