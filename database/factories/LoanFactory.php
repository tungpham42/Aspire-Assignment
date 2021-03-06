<?php

namespace Database\Factories;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    // The name of the factory's corresponding model.
    // @var string

    protected $model = Loan::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(50000, 500000000),
            'term' => $this->faker->numberBetween(1, 52),
        ];
    }
}