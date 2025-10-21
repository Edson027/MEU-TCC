<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fornecedor>
 */
class FornecedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          // Gerar dados baseados na lei angolana
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        
        // Gerar NIF angolano (14 dígitos)
        $nif = $this->generateAngolanNIF();
        
        return [
            'nome' => $firstName . ' ' . $lastName . ' Lda',
            'descricao' => $this->faker->company() . ' - Distribuidora de Medicamentos',
            'localizacao' => $this->faker->city() . ', ' . $this->faker->state() . ', Angola',
            'nif' => $nif,
            'telefone' => (int) '9' . $this->faker->numerify('########'), // Número angolano começando com 9
        ];
    }
    
    private function generateAngolanNIF()
    {
        // NIF angolano tem 14 dígitos
        return $this->faker->numerify('##############');
    }
}
