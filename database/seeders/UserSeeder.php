<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::factory(5)->create();

        //World Vision
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'company_id' => $companies->first()->id
        ])->assignRole('admin');


        //ATI
        User::create([
            'name' => 'Admin ATI',
            'email' => 'admin_ati@admin.com',
            'password' => Hash::make('password'),
            'company_id' => $companies->first()->id+1
        ])->assignRole('admin');
    }
}
