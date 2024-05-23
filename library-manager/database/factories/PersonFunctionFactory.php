<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonFunction>
 */
class PersonFunctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exited' => false,
            'address_list' => false,
            'email_list' => false,
            'personal_login' => false,
            'impersonal_login' => false,
        ];
    }
}
