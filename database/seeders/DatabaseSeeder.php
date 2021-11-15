<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use database\factories\LoanFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Loan::factory(500)->create();
        DB::table('users')->insert([[
            'name' => "admin",
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ],
        [
            'name' => "Tung Pham",
            'email' => 'tung.42@gmail.com',
            'password' => bcrypt('12345'),
        ]]);
    }
}