<?php

namespace Database\Seeders;

use App\Models\Fornecedor;
use App\Models\Movement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Medicine extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     // Verificar se existem fornecedores e usuários
        if (Fornecedor::count() === 0) {
            $this->call(FornecedorSeeder::class);
        }

        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }

        // Obter fornecedores e usuários
        $fornecedores =Fornecedor::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        // Lista de categorias de medicamentos
        $categories = [
            'Analgésicos', 'Antibióticos', 'Anti-inflamatórios', 'Antidepressivos',
            'Antialérgicos', 'Vitaminas', 'Cardiovasculares', 'Digestivos',
            'Dermatológicos', 'Respiratórios', 'Hormonais', 'Oncológicos'
        ];

        // Array para armazenar medicamentos criados
        $medicines = [];

        // Criar 1000 medicamentos
        for ($i = 1; $i <= 1000; $i++) {
            $stock = 0;
            $minimum_stock = rand(5, 50);
            $expiration_date = null;
            $category = $categories[array_rand($categories)];

            // Definir estoque e data de expiração baseado na distribuição solicitada
            if ($i <= 550) {
                // 550 medicamentos acima do estoque mínimo
                $stock = $minimum_stock + rand(10, 100);
                $expiration_date = Carbon::now()->addDays(rand(100, 1000));
            } elseif ($i <= 750) {
                // 200 com expiração nos próximos 45 dias
                $stock = rand(0, $minimum_stock - 1);
                $expiration_date = Carbon::now()->addDays(rand(1, 45));
            } elseif ($i <= 800) {
                // 50 já caducados
                $stock = rand(0, $minimum_stock - 1);
                $expiration_date = Carbon::now()->subDays(rand(1, 365));
            } else {
                // 200 abaixo do estoque mínimo
                $stock = rand(0, $minimum_stock - 1);
                $expiration_date = Carbon::now()->addDays(rand(46, 365));
            }

            $medicine = Medicine::create([
                'name' => 'Medicamento ' . $i . ' - ' . $this->generateMedicineName(),
                'description' => $this->generatePortugueseDescription($category),
                'fornecedor_id' => $fornecedores[array_rand($fornecedores)],
                'batch' => 'LOTE' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'expiration_date' => $expiration_date,
                'stock' => $stock,
                'price' => rand(500, 50000) / 100, // Preço entre 5.00 e 500.00
                'category' => $category,
                'minimum_stock' => $minimum_stock,
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now(),
                'last_alerted_at' => null,
            ]);

            $medicines[] = $medicine;
        }

        // Criar 500 movimentos (60% entrada, 40% saída)
        for ($i = 1; $i <= 500; $i++) {
            $medicine = $medicines[array_rand($medicines)];
            $type = $i <= 300 ? 'entrada' : 'saida'; // 60% entrada, 40% saída
            $quantity = $type === 'entrada' ? rand(10, 100) : rand(1, 20);
            
            $reasons = [
                'entrada' => [
                    'Compra de stock',
                    'Recebimento de fornecedor',
                    'Ajuste de inventário positivo',
                    'Doação recebida',
                    'Transferência entre farmácias'
                ],
                'saida' => [
                    'Venda ao público',
                    'Ajuste de inventário negativo',
                    'Uso interno',
                    'Doação realizada',
                    'Medicamento danificado'
                ]
            ];

            Movement::create([
                'medicine_id' => $medicine->id,
                'user_id' => $users[array_rand($users)],
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reasons[$type][array_rand($reasons[$type])],
                'movement_date' => Carbon::now()->subDays(rand(1, 365)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Seeder executado com sucesso!');
        $this->command->info('1000 medicamentos criados:');
        $this->command->info('- 550 acima do estoque mínimo');
        $this->command->info('- 200 com expiração nos próximos 45 dias');
        $this->command->info('- 50 já caducados');
        $this->command->info('- 200 abaixo do estoque mínimo');
        $this->command->info('500 movimentos criados (60% entrada, 40% saída)');
    }

    private function generateMedicineName()
    {
        $prefixes = ['Paracetamol', 'Ibuprofeno', 'Omeprazol', 'Amoxicilina', 'Dipirona',
            'Losartana', 'Sinvastatina', 'Metformina', 'AAS', 'Dexametasona',
            'Ciprofloxacino', 'Diclofenaco', 'Cetirizina', 'Loratadina', 'Pantoprazol',
            'Atorvastatina', 'Clonazepam', 'Sertralina', 'Fluoxetina', 'Tramadol',
            'Hidroclorotiazida', 'Propranolol', 'Amlodipina', 'Enalapril', 'Captopril',
            'Warfarina', 'Furosemida', 'Espironolactona', 'Levotiroxina', 'Insulina',
            'Prednisona', 'Diazepam', 'Clorfeniramina', 'Ranitidina', 'Metronidazol',
            'Azitromicina', 'Cefalexina', 'Amoxicilina+Clavulanato', 'Doxiciclina', 'Nitrofurantoína',
            'Fluconazol', 'Nistatina', 'Clotrimazol', 'Hidrocortisona', 'Betametasona',
            'Miconazol', 'Terbinafina', 'Aciclovir', 'Valaciclovir', 'Oseltamivir',
            'Salbutamol', 'Budesonida', 'Formoterol', 'Salmeterol', 'Montelucaste',
            'Codeína', 'Morfina', 'Fentanil', 'Metadona', 'Naloxona',
            'Vitamina C', 'Vitamina D', 'Vitamina B12', 'Ferro', 'Ácido Fólico',
            'Cálcio', 'Magnésio', 'Zinco', 'Potássio', 'Ômega 3',
            'Probioticos', 'Colágeno', 'Melatonina', 'Ginkgo Biloba', 'Valeriana',
            'Hioscina', 'Dimenidrinato', 'Ondansetrona', 'Metoclopramida', 'Domperidona',
            'Lactulose', 'Bisacodil', 'Sene', 'Hidróxido de Alumínio', 'Omeprazol',
            'Lansoprazol', 'Rabeprazol', 'Esomeprazol', 'Simeticona', 'Carvedilol',
            'Bisoprolol', 'Nebivolol', 'Valsartana', 'Telmisartana', 'Candesartana',
            'Levodopa', 'Ropinirol', 'Pramipexol', 'Donepezila', 'Memantina'
];
        $suffixes = ['  ', '  ', ' ', '  ', '  ', ' ', '  ', '  ', '', ' '];
        
        return $prefixes[array_rand($prefixes)] . ' ' . rand(100, 1000) . 'mg';
    }

    private function generatePortugueseDescription($category)
    {
        $descriptions = [
            'Analgésicos' => 'Medicamento utilizado para alívio da dor. Deve ser administrado conforme orientação médica.',
            'Antibióticos' => 'Antibiótico de amplo espectro para tratamento de infecções bacterianas.',
            'Anti-inflamatórios' => 'Reduz inflamações e alivia dores musculares e articulares.',
            'Antidepressivos' => 'Utilizado no tratamento da depressão e transtornos de ansiedade.',
            'Antialérgicos' => 'Alivia sintomas de alergias como espirros, coceira e coriza.',
            'Vitaminas' => 'Suplemento vitamínico para complementação nutricional.',
            'Cardiovasculares' => 'Medicamento para tratamento de condições cardíacas e pressão arterial.',
            'Digestivos' => 'Auxilia no tratamento de problemas digestivos e gastrointestinais.',
            'Dermatológicos' => 'Para tratamento tópico de condições da pele.',
            'Respiratórios' => 'Medicamento para tratamento de doenças respiratórias.',
            'Hormonais' => 'Regula funções hormonais do organismo.',
            'Oncológicos' => 'Medicamento utilizado em tratamentos oncológicos.'
        ];

        return $descriptions[$category] ?? 'Medicamento para uso terapêutico conforme prescrição médica.';
    }
}
