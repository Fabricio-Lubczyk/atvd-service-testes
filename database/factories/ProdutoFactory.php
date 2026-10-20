<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->word(),
            'preco_custo' => 100,
            'preco' => 110,
            'quantidade' => 0,
        ];
    }
}
