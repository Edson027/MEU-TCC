<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Medicine;
use App\Models\Movement;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
class MedicineSeeder extends Seeder
{
    
    /*
  public function run()
    {
        // Create 30 medicines below minimum stock (0-9 items)
        for ($i = 0; $i < 30; $i++) {
            $medicine = Medicine::create([
                'name' => 'Medicine Below Stock ' . ($i + 1),
                'description' => 'Description for medicine below minimum stock',
                'batch' => 'BATCH-' . rand(1000, 9999),
                'expiration_date' => Carbon::now()->addDays(rand(60, 365)), // Normal expiration
                'stock' => rand(0, 9),
                'price' => rand(100, 1000) / 10,
                'category' => ['Analgesic', 'Antibiotic', 'Antihistamine'][rand(0, 2)],
                'minimum_stock' => 10,
            ]);

            $this->createMovements($medicine);
        }

        // Create 20 medicines expiring in the next 25 days
        for ($i = 0; $i < 20; $i++) {
            $medicine = Medicine::create([
                'name' => 'Medicine Expiring Soon ' . ($i + 1),
                'description' => 'Description for medicine expiring soon',
                'batch' => 'BATCH-' . rand(1000, 9999),
                'expiration_date' => Carbon::now()->addDays(rand(1, 25)), // Expiring soon
                'stock' => rand(50, 200),
                'price' => rand(100, 1000) / 10,
                'category' => ['Analgesic', 'Antibiotic', 'Antihistamine'][rand(0, 2)],
                'minimum_stock' => 10,
            ]);

            $this->createMovements($medicine);
        }

        // Create 50 medicines with stock 10x or more above minimum (100+ items)
        for ($i = 0; $i < 50; $i++) {
            $medicine = Medicine::create([
                'name' => 'Medicine High Stock ' . ($i + 1),
                'description' => 'Description for medicine with high stock',
                'batch' => 'BATCH-' . rand(1000, 9999),
                'expiration_date' => Carbon::now()->addDays(rand(366, 730)), // Far expiration
                'stock' => rand(100, 1000), // Large stock numbers
                'price' => rand(100, 1000) / 10,
                'category' => ['Analgesic', 'Antibiotic', 'Antihistamine'][rand(0, 2)],
                'minimum_stock' => 10,
            ]);

            $this->createMovements($medicine);
        }
    }
 
    protected function createMovements(Medicine $medicine)
    {
       $admin = User::create([
            'name' => 'Administrador Carlos',
            'email' => 'admin@farmacia.com',
            'password' => Hash::make('senha123'), // Lembre de alterar para uma senha segura
            'painel' => 'administrador', // Assumindo que seu sistema tem um campo 'role'
        ]);
        $initialStock = 0;
        
        // Create initial entry movement to set up stock
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => $user->id,
            'type' => 'entrada',
            'quantity' => $medicine->stock,
            'reason' => 'Initial stock entry',
            'movement_date' => Carbon::now()->subDays(rand(30, 90)),
        ]);

        // Create some random movements (70% chance of entry, 30% chance of exit)
        $movementCount = rand(3, 10);
        $currentStock = $medicine->stock;
        
        for ($i = 0; $i < $movementCount; $i++) {
            $isEntry = rand(1, 10) <= 7;
            $quantity = $isEntry ? rand(10, 100) : rand(1, min(50, $currentStock));
            
            if (!$isEntry && $quantity > $currentStock) {
                $quantity = $currentStock;
            }
            
            $currentStock += $isEntry ? $quantity : -$quantity;
            
            Movement::create([
                'medicine_id' => $medicine->id,
                'user_id' => $user->id,
                'type' => $isEntry ? 'entrada' : 'saida',
                'quantity' => $quantity,
                'reason' => $isEntry ? 'Restock' : 'Saída para atendimento de paciente',
                'movement_date' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
        
        // Update medicine stock to reflect all movements
        $medicine->stock = $currentStock;
        $medicine->save();
          $this->command->info('Medicamentos e movimentos criados com sucesso!');
    }

*/

 public function run(): void
    {

       $admin = User::create([
            'name' => 'Edson Carlos Chissaluqia',
            'email' => 'admin@farmacia.com',
            'password' => Hash::make('senha123'), // Lembre de alterar para uma senha segura
            'painel' => 'administrador', // Assumindo que seu sistema tem um campo 'role'
        ]);

        // Portuguese medicine names
        $medicineNames = [
            'Paracetamol', 'Ibuprofeno', 'Omeprazol', 'Amoxicilina', 'Dipirona',
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

        // Portuguese categories
        $categories = [
            'Analgésico', 'Anti-inflamatório', 'Antibiótico', 'Antialérgico', 'Antidepressivo',
            'Antihipertensivo', 'Antidiabético', 'Anticoagulante', 'Anticonvulsivante', 'Antitérmico',
            'Antiviral', 'Antifúngico', 'Broncodilatador', 'Corticosteroide', 'Diurético',
            'Hormônio', 'Relaxante Muscular', 'Suplemento', 'Vitaminas', 'Probiótico',
            'Antiácido', 'Laxante', 'Antiemético', 'Antipsicótico', 'Ansiolítico'
        ];

        // Reasons for movements
        $reasons = [
            'Abastecimento de estoque',
            'Ajuste de estoque',
            'Doação recebida',
            'Transferência entre unidades',
            'Devolução de  de paciente',
            'Atendimento  para paciente',
            'Perda por validade',
            'Danificação de produto',
            'Uso interno',
            'Amostra grátis'
        ];

        // Create 100 medicines
        for ($i = 0; $i < 100; $i++) {
            $name = $medicineNames[array_rand($medicineNames)] . ' ' . rand(100, 999) . 'mg';
            $category = $categories[array_rand($categories)];
            $batch = 'LOTE' . strtoupper(substr(md5(rand()), 0, 6));
            $expirationDate = Carbon::now()->addDays(rand(30, 720))->format('Y-m-d');
            
            // Determine stock conditions based on requirements
            if ($i < 30) {
                // 30 medicines below minimum stock (0-9)
                $stock = rand(0, 9);
                $minimumStock = rand(10, 20);
            } elseif ($i < 50) {
                // 20 medicines expiring soon (next 25 days)
                $expirationDate = Carbon::now()->addDays(rand(1, 25))->format('Y-m-d');
                $stock = rand(50, 200);
                $minimumStock = rand(10, 30);
            } else {
                // 50 medicines with stock 10x or more above minimum
                $minimumStock = rand(10, 50);
                $stock = rand($minimumStock * 10, $minimumStock * 50);
            }

            $medicine = Medicine::create([
                'name' => $name,
                'description' => "Medicamento para tratamento de diversas condições de saúde. Categoria: $category.",
                'batch' => $batch,
                'expiration_date' => $expirationDate,
                'stock' => $stock,
                'price' => rand(500, 50000) / 100, // Prices between 5.00 and 500.00
                'category' => $category,
                'minimum_stock' => $minimumStock,
            ]);

            // Create initial movement for the stock
            Movement::create([
                'medicine_id' => $medicine->id,
                'user_id' => $admin->id, // Assuming user with ID 1 exists
                'type' => 'entrada',
                'quantity' => $stock,
                'reason' => 'Estoque inicial',
                'movement_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
            ]);

            // Create additional random movements (1-5 per medicine)
            $movementCount = rand(1, 5);
            for ($j = 0; $j < $movementCount; $j++) {
                $type = rand(0, 1) ? 'entrada' : 'saida';
                $quantity = $type === 'entrada' ? rand(10, 100) : rand(1, 50);
                $reason = $reasons[array_rand($reasons)];
                
                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' =>$admin->id, // Assuming users with IDs 1-5 exist
                    'type' => $type,
                    'quantity' => $quantity,
                    'reason' => $reason,
                    'movement_date' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                ]);
            }
        }
    }
        
  
}

