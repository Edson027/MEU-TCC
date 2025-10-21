<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $categories = ['Analgésico', 'Antibiótico', 'Anti-inflamatório', 'Antidepressivo', 
                      'Antialérgico', 'Cardiovascular', 'Diabetes', 'Vitaminas', 
                      'Dermatológico', 'Gastrointestinal', 'Respiratório'];
        
        return [
            'name' => $this->generateMedicineName(),
            'description' => $this->faker->sentence(10),
            'fornecedor_id' => Fornecedor::factory(),
            'batch' => 'LOTE-' . $this->faker->bothify('??##'),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
            'stock' => $this->faker->numberBetween(0, 500),
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'category' => $this->faker->randomElement($categories),
            'minimum_stock' => 10,
        ];
    }
    
    private function generateMedicineName()
    {
        $prefixes = ['Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Omeprazol', 'Losartana', 
                    'Metformina', 'Atorvastatina', 'Sinvastatina', 'Ampicilina', 'Diazepam',
                    'Loratadina', 'Cetirizina', 'Azitromicina', 'Ciprofloxacino', 'Insulina',
                    'Vitamina C', 'Vitamina D', 'Complexo B', 'Dipirona', 'Captopril'];
        
        $suffixes = [' 500mg', ' 250mg', ' 100mg', ' 50mg', ' 20mg', ' 10mg', ' Comprimidos',
                    ' Cápsulas', ' Xarope', ' Gotas', ' Pomada', ' Creme', ' Injetável'];
        
        return $this->faker->randomElement($prefixes) . $this->faker->randomElement($suffixes);
    }
}
