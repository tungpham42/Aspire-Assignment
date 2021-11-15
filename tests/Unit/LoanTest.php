<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class LoanTest extends TestCase
{
    protected function withBasicAuth($username = 'tung.42@gmail.com', $password = '12345'): self
    {
        return $this->withHeaders([
            'Authorization' => 'Basic '. base64_encode("{$username}:{$password}")
        ]);
    }

    public function test_get_all_loans()
    {
        $response = $this->withBasicAuth()->get('/api/loans');
        $response->assertStatus(200);
    }

    public function test_get_specific_loan()
    {
        $response = $this->withBasicAuth()->get('/api/loans/24');
        $response->assertStatus(200);
    }

    public function test_create_new_loan()
    {
        $response = $this->withBasicAuth()->post('/api/loans', ['amount' => '5200000', 'term' => '52']);
        $response->assertStatus(201);
    }

    public function test_update_specific_loan()
    {
        $response = $this->withBasicAuth()->patch('/api/loans/24', ['amount' => '2500000', 'term' => '25']);
        $response->assertStatus(200);
    }

    public function test_delete_specific_loan()
    {
        $response = $this->withBasicAuth()->delete('/api/loans/501');
        $response->assertStatus(200);
    }

    public function test_repay_specific_loan()
    {
        $response = $this->withBasicAuth()->get('/api/loans/24/repay');
        $response->assertStatus(200);
    }
}
