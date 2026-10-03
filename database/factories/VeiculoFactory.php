<?php

namespace Database\Factories;

use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Veiculo>
 */
class VeiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ano = fake()->numberBetween(2015, (int) date('Y'));

        return [
            'marca' => fake()->randomElement(['Toyota', 'Honda', 'Volkswagen', 'Chevrolet', 'Fiat', 'Hyundai', 'Jeep']),
            'modelo' => fake()->randomElement(['Corolla', 'Civic', 'T-Cross', 'Onix', 'Pulse', 'HB20', 'Compass']),
            'versao' => fake()->optional()->randomElement(['1.0 Turbo', '2.0 XEi', 'Highline', 'Premier', 'Limited']),
            'categoria' => fake()->randomElement(Veiculo::CATEGORIAS),
            'ano_fabricacao' => $ano,
            'ano_modelo' => $ano,
            'cor' => fake()->randomElement(['Branco', 'Preto', 'Prata', 'Cinza', 'Vermelho', 'Azul']),
            'combustivel' => fake()->randomElement(Veiculo::COMBUSTIVEIS),
            'cambio' => fake()->randomElement(Veiculo::CAMBIOS),
            'quilometragem' => fake()->numberBetween(0, 120000),
            'placa' => strtoupper(fake()->unique()->bothify('???#?##')),
            'preco' => fake()->randomFloat(2, 45000, 350000),
            'status' => fake()->randomElement(array_keys(Veiculo::STATUS)),
            'foto_url' => null,
            'descricao' => fake()->optional()->sentence(12),
        ];
    }
}
