<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class MedicineAndMovementSeeder extends Seeder
{
   
      public function run(): void
    { 
        // Primeiro, verificar se existem fornecedores
        $supplierCount = DB::table('fornecedors')->count();
        
        if ($supplierCount === 0) {
            $this->command->error('Não existem fornecedores na tabela fornecedores. Execute primeiro o seeder de fornecedores.');
            return;
        }

        // Obter IDs de fornecedores e usuários
        $supplierIds = DB::table('fornecedors')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();
        
        if (empty($userIds)) {
            // Criar um usuário padrão se não existir
            $defaultUserId = DB::table('users')->insertGetId([
                'name' => 'Administrador',
                'email' => 'admin@farmácia.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $userIds = [$defaultUserId];
        }

        // Dados de exemplo para medicamentos
        $medicinesData = [];
        $now = now();
        
        // Medicamentos com estoque normal (75)
        for ($i = 1; $i <= 75; $i++) {
            $medicinesData[] = $this->generateMedicineData(
                $i, 
                $supplierIds, 
                'normal', 
                $now
            );
        }
        
        // Medicamentos com estoque abaixo do mínimo (50)
        for ($i = 76; $i <= 125; $i++) {
            $medicinesData[] = $this->generateMedicineData(
                $i, 
                $supplierIds, 
                'below_minimum', 
                $now
            );
        }
        
        // Medicamentos com estoque esgotado (25)
        for ($i = 126; $i <= 150; $i++) {
            $medicinesData[] = $this->generateMedicineData(
                $i, 
                $supplierIds, 
                'out_of_stock', 
                $now
            );
        }
        
        // Medicamentos com validade próxima (25 com 30 dias, 25 com 15 dias)
        for ($i = 151; $i <= 175; $i++) {
            $medicinesData[] = $this->generateMedicineData(
                $i, 
                $supplierIds, 
                'normal', 
                $now,
                true, // expiring
                30    // days to expire
            );
        }
        
        for ($i = 176; $i <= 200; $i++) {
            $medicinesData[] = $this->generateMedicineData(
                $i, 
                $supplierIds, 
                'normal', 
                $now,
                true, // expiring
                15    // days to expire
            );
        }

        // Inserir medicamentos
        DB::table('medicines')->insert($medicinesData);
        
        // Obter IDs dos medicamentos inseridos
        $medicineIds = DB::table('medicines')->pluck('id')->toArray();
        
        // Criar movimentos (100 no total)
        $movementsData = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $medicineId = $medicineIds[array_rand($medicineIds)];
            $userId = $userIds[array_rand($userIds)];
            
            $movementsData[] = [
                'medicine_id' => $medicineId,
                'user_id' => $userId,
                'type' => rand(0, 1) ? 'entrada' : 'saida',
                'quantity' => rand(1, 100),
                'reason' => $this->getRandomReason(),
                'movement_date' => Carbon::now()->subDays(rand(0, 90))->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        // Inserir movimentos
        DB::table('movements')->insert($movementsData);
        
        $this->command->info('Seeder executado com sucesso!');
        $this->command->info('200 medicamentos criados:');
        $this->command->info('- 75 com estoque normal');
        $this->command->info('- 50 com estoque abaixo do mínimo');
        $this->command->info('- 25 com estoque esgotado');
        $this->command->info('- 25 com validade em 30 dias');
        $this->command->info('- 25 com validade em 15 dias');
        $this->command->info('100 movimentos criados.');
    }
    
    /**
     * Gera dados para um medicamento
     */
    private function generateMedicineData(
        int $index, 
        array $supplierIds, 
        string $stockStatus, 
        Carbon $now,
        bool $expiring = false,
        int $daysToExpire = null
    ): array {
        $categories = ['Analgésico', 'Antibiótico', 'Anti-inflamatório', 'Antidepressivo', 'Antialérgico', 'Vitaminas', 'Cardiovascular', 'Dermatológico'];
        $minStock = 10;
        
        // Definir estoque baseado no status
        switch ($stockStatus) {
            case 'below_minimum':
                $stock = rand(1, $minStock - 1);
                break;
            case 'out_of_stock':
                $stock = 0;
                break;
            case 'normal':
            default:
                $stock = rand($minStock, 200);
                break;
        }
        
        // Definir data de validade
        if ($expiring && $daysToExpire) {
            $expirationDate = Carbon::now()->addDays($daysToExpire);
        } else {
            // Validade entre 6 meses e 3 anos
            $expirationDate = Carbon::now()->addMonths(rand(6, 36));
        }
        
        return [
            'name' => 'Medicamento ' . $index . ' - ' . Str::random(5),
            'description' => 'Descrição do medicamento ' . $index . '. ' . $this->getRandomDescription(),
            'fornecedor_id' => $supplierIds[array_rand($supplierIds)],
            'batch' => 'LOTE' . strtoupper(Str::random(6)),
            'expiration_date' => $expirationDate->format('Y-m-d'),
            'stock' => $stock,
            'price' => rand(500, 10000) / 100, // Preço entre 5.00 e 100.00
            'category' => $categories[array_rand($categories)],
            'minimum_stock' => $minStock,
            'created_at' => $now,
            'updated_at' => $now,
            'last_alerted_at' => $stock < $minStock ? $now : null,
        ];
    }
    
    /**
     * Retorna uma descrição aleatória
     */
    private function getRandomDescription(): string
    {
        $descriptions = [
            'Usado para tratamento de dores moderadas a intensas.',
            'Indicado para infecções bacterianas.',
            'Ajuda a reduzir inflamações e aliviar a dor.',
            'Utilizado no tratamento de condições cardíacas.',
            'Suplemento vitamínico para fortalecimento do sistema imunológico.',
            'Medicamento de uso tópico para condições dermatológicas.',
            'Antidepressivo para tratamento de depressão maior.',
            'Antialérgico para alívio de sintomas de alergia.',
        ];
        
        return $descriptions[array_rand($descriptions)];
    }
    
    /**
     * Retorna uma razão aleatória para movimento
     */
    private function getRandomReason(): string
    {
        $reasons = [
            'Compra de fornecedor',
            'Venda para cliente',
            'Ajuste de inventário',
            'Doação para instituição',
            'Uso interno',
            'Devolução de cliente',
            'Perda por validade',
            'Transferência entre filiais',
        ];
        
        return $reasons[array_rand($reasons)];
    }

}
