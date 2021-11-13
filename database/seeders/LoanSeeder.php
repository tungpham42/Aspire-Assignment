<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Loan;
use database\factories\LoanFactory;

class LoanSeeder extends Seeder{

    // Run the database seeds.   
    @return void

    public function run() {
        Loan::factory()->times(42)->create();//we will generate 42 records
    }
}