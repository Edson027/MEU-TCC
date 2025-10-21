<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movement>
 */
class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['entrada', 'saida'];
        $reasons = [
            'entrada' => ['Abastecimento regular', 'Doação', 'Transferência entre farmácias', 'Stock de segurança'],
            'saida' => ['Atendimento a paciente', 'Uso interno', 'Danificado', 'Vencimento', 'Doação']
        ];
        
        $type = $this->faker->randomElement($types);
        
        return [
            'medicine_id' => Medicine::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'quantity' => $this->faker->numberBetween(1, 100),
            'reason' => $this->faker->randomElement($reasons[$type]),
            'movement_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
