<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    protected function withBasicAuth($username = 'tung.42@gmail.com', $password = '12345'): self
    {
        return $this->withHeaders([
            'Authorization' => 'Basic '. base64_encode("{$username}:{$password}")
        ]);
    }

    public function testGetAllLoans()
    {
        $response = $this->withBasicAuth('admin@example.com', 'password')->get('/api/loans');
        $response->assertStatus(200);
    }

    public function testGetSpecificLoan()
    {
        $response = $this->withBasicAuth()->get('/api/loans/24');
        $response->assertStatus(200);
    }

    public function testCreateNewLoan()
    {
        $response = $this->withBasicAuth('admin@example.com', 'password')->post('/api/loans', ['amount' => '5200000', 'term' => '52']);
        $response->assertStatus(201);
    }

    public function testUpdateSpecificLoan()
    {
        $response = $this->withBasicAuth('admin@example.com', 'password')->patch('/api/loans/24', ['amount' => '2500000', 'term' => '25']);
        $response->assertStatus(200);
    }

    public function testDeleteSpecificLoan()
    {
        $response = $this->withBasicAuth('admin@example.com', 'password')->delete('/api/loans/500');
        $response->assertStatus(200);
    }

    public function testRepaySpecificLoan()
    {
        $response = $this->withBasicAuth()->get('/api/loans/24/repay');
        $response->assertStatus(200);
    }
}
