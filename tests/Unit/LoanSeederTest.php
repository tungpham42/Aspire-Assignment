<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class LoanSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new order.
     *
     * @return void
     */
    public function testSeedAllLoans()
    {
        // Run the DatabaseSeeder...
        Artisan::call('migrate:fresh --seed');
        $this->assertDatabaseHas('users', [
            'email' => 'tung.42@gmail.com',
        ]);
    }
}
