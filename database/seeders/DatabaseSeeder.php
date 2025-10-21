<?php

namespace Database\Seeders;

use App\Models\Fornecedor;
use App\Models\Medicine;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
          $this->call([
            
            UserSeeder::class,
//            FornecedoresSeeder::class,
  //          MedicineSeeder::class,
            
          ]);

             // Criar 500 fornecedores
 
             // Criar 500 fornecedores
 /*
             $fornecedores = [];
        $usedNifs = [];
        $usedTelefones = [];
        
        for ($i = 1; $i <= 500; $i++) {
            $nif = $this->generateUniqueNif($usedNifs);
            $telefone = $this->generateUniqueTelefone($usedTelefones);
            
            $fornecedores[] = [
                'nome' => 'Fornecedor ' . $i . ' ' . $this->generateRandomCompanySuffix(),
                'descricao' => 'Descrição do fornecedor ' . $i . ' - ' . $this->generateRandomDescription(),
                'localizacao' => $this->generateRandomLocation(),
                'nif' => $nif,
                'telefone' => $telefone,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $usedNifs[] = $nif;
            $usedTelefones[] = $telefone;
        }
        
        DB::table('fornecedors')->insert($fornecedores);
        
        // Criar 1000 medicamentos
        $medicines = [];
        $medicineNames = $this->generateMedicineNames(1000); // Garantir 1000 nomes
        $fornecedorIds = DB::table('fornecedors')->pluck('id')->toArray();
        
        // Configurações dos medicamentos
        $stockConfig = [
            'above_min' => 550,    // Estoque acima do mínimo
            'min_stock' => 450,    // Estoque no mínimo
        ];
        
        $expiryConfig = [
            'near_expiry' => 200,  // Vencimento próximo (45 dias)
        ];
        
        $counterAboveMin = 0;
        $counterNearExpiry = 0;
        
        for ($i = 0; $i < 1000; $i++) {
            $fornecedorId = $fornecedorIds[array_rand($fornecedorIds)];
            $stockMinimo = rand(5, 20);
            
            // Definir estoque
            if ($counterAboveMin < $stockConfig['above_min']) {
                $stock = rand($stockMinimo + 1, $stockMinimo + 100);
                $counterAboveMin++;
            } else {
                $stock = rand(0, $stockMinimo);
            }
            
            // Definir data de expiração
            if ($counterNearExpiry < $expiryConfig['near_expiry']) {
                $expirationDate = Carbon::now()->addDays(rand(1, 45));
                $counterNearExpiry++;
            } else {
                $expirationDate = Carbon::now()->addDays(rand(46, 365 * 3));
            }
            
            $medicines[] = [
                'name' => $medicineNames[$i],
                'description' => $this->generateMedicineDescription($medicineNames[$i]),
                'fornecedor_id' => $fornecedorId,
                'batch' => 'LOTE' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'expiration_date' => $expirationDate,
                'stock' => $stock,
                'price' => rand(500, 50000) / 100, // Preço entre 5.00 e 500.00
                'category' => $this->generateRandomCategory(),
                'minimum_stock' => $stockMinimo,
                'last_alerted_at' => $stock <= $stockMinimo ? now() : null,
                'created_at' => Carbon::now()->subDays(rand(0, 365)),
                'updated_at' => now(),
            ];
        }
        
        DB::table('medicines')->insert($medicines);
        
        // Criar 700 movimentos
        $movements = [];
        $medicineIds = DB::table('medicines')->pluck('id')->toArray();
        $userIds = [1]; // Assumindo que existe pelo menos o usuário ID 1
        
        $entradaCount = (int)(700 * 0.7); // 70% entradas (490)
        $saidaCount = 700 - $entradaCount; // 30% saídas (210)
        
        for ($i = 0; $i < 700; $i++) {
            $medicineId = $medicineIds[array_rand($medicineIds)];
            $type = $i < $entradaCount ? 'entrada' : 'saida';
            $quantity = $type === 'entrada' ? rand(10, 100) : rand(1, 20);
            
            $movements[] = [
                'medicine_id' => $medicineId,
                'user_id' => $userIds[array_rand($userIds)],
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $this->generateMovementReason($type),
                'movement_date' => Carbon::now()->subDays(rand(0, 90)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('movements')->insert($movements);
    }
    
    private function generateUniqueNif(&$usedNifs)
    {
        do {
            $nif = rand(100000000, 999999999);
        } while (in_array($nif, $usedNifs));
        
        return $nif;
    }
    
    private function generateUniqueTelefone(&$usedTelefones)
    {
        do {
            $telefone = rand(900000000, 999999999);
        } while (in_array($telefone, $usedTelefones));
        
        return $telefone;
    }
    
    private function generateRandomCompanySuffix()
    {
        $suffixes = ['Ltda', 'S.A.', 'EIRELI', 'Comércio', 'Distribuidora', 'Farmácias', 'Importadora'];
        return $suffixes[array_rand($suffixes)];
    }
    
    private function generateRandomDescription()
    {
        $descriptions = [
            'Especializado em medicamentos controlados',
            'Distribuidor autorizado ANVISA',
            'Atuando no mercado há mais de 10 anos',
            'Foco em medicamentos de alto custo',
            'Atendimento nacional',
            'Especialista em genéricos',
            'Fornecedor oficial do SUS'
        ];
        return $descriptions[array_rand($descriptions)];
    }
    
    private function generateRandomLocation()
    {
        $cidades = [
            'São Paulo/SP', 'Rio de Janeiro/RJ', 'Belo Horizonte/MG', 
            'Porto Alegre/RS', 'Curitiba/PR', 'Salvador/BA', 'Fortaleza/CE',
            'Brasília/DF', 'Manaus/AM', 'Recife/PE'
        ];
        return $cidades[array_rand($cidades)];
    }
    
    private function generateMedicineNames($quantity)
    {
        $baseNames = [
            'Paracetamol', 'Dipirona', 'Omeprazol', 'Amoxicilina', 'Losartana',
            'Atorvastatina', 'Metformina', 'AAS', 'Sinvastatina', 'Clonazepam',
            'Diazepam', 'Loratadina', 'Cetirizina', 'Captopril', 'Hidroclorotiazida',
            'Propranolol', 'Atenolol', 'Warfarina', 'Insulina', 'Levotiroxina',
            'Prednisona', 'Dexametasona', 'Ibuprofeno', 'Naproxeno', 'Tramadol',
            'Codeína', 'Morfina', 'Fentanil', 'Midazolam', 'Propofol',
            'Cefalexina', 'Azitromicina', 'Ciprofloxacino', 'Doxiciclina', 'Metronidazol',
            'Fluconazol', 'Aciclovir', 'Valaciclovir', 'Oseltamivir', 'Ritonavir',
            'Salbutamol', 'Budesonida', 'Montelucaste', 'Teofilina', 'Fenitoína',
            'Carbamazepina', 'Ácido Valproico', 'Risperidona', 'Olanzapina', 'Sertralina',
            'Fluoxetina', 'Venlafaxina', 'Duloxetina', 'Bupropiona', 'Trazodona',
            'Clorpromazina', 'Haloperidol', 'Quetiapina', 'Aripiprazol', 'Zolpidem',
            'Alprazolam', 'Bromazepam', 'Clonazepam', 'Rivotril', 'Frontal',
            'Lexotan', 'Dorflex', 'Torsilax', 'Miosan', 'Cataflan',
            'Voltaren', 'Nimesulida', 'Celecoxibe', 'Etoricoxibe', 'Pregabalina',
            'Gabapentina', 'Carbolitium', 'Lítio', 'Lamotrigina', 'Topiramato',
            'Levetiracetam', 'Oxcarbazepina', 'Fenobarbital', 'Primidona', 'Vigabatrina',
            'Tiagabina', 'Zonisamida', 'Perampanel', 'Brivaracetam', 'Eslicarbazepina',
            // Adicionar mais nomes para garantir variedade
            'Amiodarona', 'Digoxina', 'Carvedilol', 'Valsartana', 'Enalapril',
            'Ramipril', 'Furosemida', 'Espironolactona', 'Hidralazina', 'Minoxidil',
            'Nitroglicerina', 'Isossorbida', 'Verapamil', 'Diltiazem', 'Nifedipina',
            'Amlodipina', 'Lercanidipina', 'Nimodipina', 'Felodipina', 'Nicardipina'
        ];
        
        $names = [];
        $attempts = 0;
        $maxAttempts = $quantity * 2;
        
        while (count($names) < $quantity && $attempts < $maxAttempts) {
            $baseName = $baseNames[array_rand($baseNames)];
            $suffix = rand(1, 100) > 30 ? ' ' . rand(100, 1000) . 'mg' : '';
            $form = $this->getRandomForm();
            
            // Adicionar variações para garantir unicidade
            $variation = '';
            if (rand(1, 100) > 80) {
                $variations = ['Plus', 'Forte', 'Retard', 'XL', 'SR', 'CR'];
                $variation = ' ' . $variations[array_rand($variations)];
            }
            
            $fullName = $baseName . $suffix . $variation . ' ' . $form;
            
            if (!in_array($fullName, $names)) {
                $names[] = $fullName;
            }
            
            $attempts++;
        }
        
        // Se ainda não tiver nomes suficientes, completar com números sequenciais
        while (count($names) < $quantity) {
            $baseName = $baseNames[array_rand($baseNames)];
            $sequentialNumber = count($names) + 1;
            $names[] = $baseName . ' ' . $sequentialNumber . 'mg ' . $this->getRandomForm();
        }
        
        return array_slice($names, 0, $quantity);
    }
    
    private function getRandomForm()
    {
        $forms = [
            'Comprimido', 'Cápsula', 'Drágea', 'Comprimido Revestido', 'Comprimido Mastigável',
            'Comprimido Liberação Prolongada', 'Cápsula Mole', 'Cápsula Dura', 'Solução Oral',
            'Xarope', 'Suspensão', 'Gotas', 'Spray', 'Pó para Solução', 'Granulado',
            'Pomada', 'Creme', 'Gel', 'Loção', 'Spray Nasal', 'Colírio', 'Pomada Oftálmica',
            'Supositório', 'Óvulo', 'Injetável', 'Pó para Injeção', 'Implante', 'Adesivo'
        ];
        return $forms[array_rand($forms)];
    }
    
    private function generateMedicineDescription($medicineName)
    {
        $descriptions = [
            "Medicamento de uso contínuo para tratamento de diversas condições",
            "Indicado para o controle de sintomas agudos e crônicos",
            "Medicamento de referência com alta eficácia comprovada",
            "Genérico de qualidade equivalente ao medicamento de referência",
            "Uso sob prescrição médica - manter fora do alcance de crianças",
            "Armazenar em local fresco e seco, protegido da luz",
            "Medicamento essencial conforme lista da OMS",
            "Tratamento de primeira linha para a condição indicada",
            "Uso hospitalar e ambulatorial com acompanhamento médico"
        ];
        
        return "{$medicineName}. " . $descriptions[array_rand($descriptions)];
    }
    
    private function generateRandomCategory()
    {
        $categories = [
            'Analgésico', 'Antitérmico', 'Anti-inflamatório', 'Antibiótico', 'Antiviral',
            'Antifúngico', 'Antidepressivo', 'Ansiolítico', 'Antipsicótico', 'Anticonvulsivante',
            'Anti-hipertensivo', 'Antidiabético', 'Anticoagulante', 'Broncodilatador', 'Corticosteroide',
            'Hormônio', 'Vitaminas', 'Mineral', 'Quimioterápico', 'Imunossupressor',
            'Vacina', 'Soro', 'Contraste', 'Nutrição Parenteral', 'Fitoterápico'
        ];
        return $categories[array_rand($categories)];
    }
    
    private function generateMovementReason($type)
    {
        $entradaReasons = [
            'Compra regular do fornecedor',
            'Reabastecimento de estoque',
            'Doação do laboratório',
            'Entrada por transferência entre unidades',
            'Ajuste de inventário positivo',
            'Devolução de paciente',
            'Entrada emergencial'
        ];
        
        $saidaReasons = [
            'Dispensação para paciente',
            'Transferência para outra unidade',
            'Ajuste de inventário negativo',
            'Medicamento vencido - descarte',
            'Amostra grátis para paciente',
            'Uso em procedimento médico',
            'Doação para instituição carente'
        ];
        
        return $type === 'entrada' 
            ? $entradaReasons[array_rand($entradaReasons)]
            : $saidaReasons[array_rand($saidaReasons)];
        /*
          // Criar um usuário admin para os movimentos
        $user = User::factory()->create([
            'name' => 'Admin Farmacêutico',
            'email' => 'admin@farmacia.co.ao',
            'password' => bcrypt('password123'),
            'paniel'=>'administrador',
        ]);

        // 1. Criar 300 fornecedores
        $fornecedores = Fornecedor::factory()->count(300)->create();
        
        // 2. Criar 500 medicamentos
        $medicines = Medicine::factory()->count(500)->create();
        
        // 3. Ajustar medicamentos conforme especificado
        $this->adjustMedicines($medicines);
        
        // 4. Criar 250 movimentos (60% entrada, 40% saída)
        $this->createMovements($medicines, $user);
    }
    
    private function adjustMedicines($medicines)
    {
        // 200 medicamentos abaixo do estoque mínimo
        $lowStockMedicines = $medicines->take(200);
        foreach ($lowStockMedicines as $medicine) {
            $medicine->update([
                'stock' => rand(0, 9), // Abaixo do mínimo (10)
                'last_alerted_at' => now()
            ]);
        }
        
        // 150 medicamentos com validade nos próximos 45 dias
        $expiringMedicines = $medicines->slice(200, 150);
        foreach ($expiringMedicines as $medicine) {
            $medicine->update([
                'expiration_date' => Carbon::now()->addDays(rand(1, 45))->format('Y-m-d'),
                'stock' => rand(20, 100) // Estoque normal
            ]);
        }
        
        // Os restantes 150 medicamentos acima do estoque mínimo
        $normalStockMedicines = $medicines->slice(350, 150);
        foreach ($normalStockMedicines as $medicine) {
            $medicine->update([
                'stock' => rand(15, 500) // Acima do mínimo
            ]);
        }
    }
    
    private function createMovements($medicines, $user)
    {
        $movements = [];
        
        // 150 movimentos de entrada (60%)
        for ($i = 0; $i < 150; $i++) {
            $movements[] = [
                'medicine_id' => $medicines->random()->id,
                'user_id' => $user->id,
                'type' => 'entrada',
                'quantity' => rand(10, 100),
                'reason' => $this->getMovementReason('entrada'),
                'movement_date' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // 100 movimentos de saída (40%)
        for ($i = 0; $i < 100; $i++) {
            $movements[] = [
                'medicine_id' => $medicines->random()->id,
                'user_id' => $user->id,
                'type' => 'saida',
                'quantity' => rand(1, 50),
                'reason' => $this->getMovementReason('saida'),
                'movement_date' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Embaralhar e inserir
        shuffle($movements);
        DB::table('movements')->insert($movements);
    }
    
    private function getMovementReason($type)
    {
        $reasons = [
            'entrada' => ['Abastecimento regular', 'Doação', 'Transferência entre farmácias', 'Stock de segurança'],
            'saida' => ['Atendimento a paciente', 'Uso interno', 'Danificado', 'Vencimento', 'Doação']
        ];
        
        return $reasons[$type][array_rand($reasons[$type])];*/
 $faker = Faker::create('pt_PT');

        // Criar 500 fornecedores com dados únicos
        $fornecedores = [];
        $nomesUsados = [];
        $nifsUsados = [];
        $telefonesUsados = [];

        for ($i = 0; $i < 500; $i++) {
            // Gerar nome único
            do {
                $nome = $faker->company;
            } while (in_array($nome, $nomesUsados));
            
            // Gerar NIF único
            do {
                $nif = $faker->unique()->numerify('#########');
            } while (in_array($nif, $nifsUsados));
            
            // Gerar telefone único
            do {
                $telefone = $faker->unique()->numerify('9#######');
            } while (in_array($telefone, $telefonesUsados));

            $nomesUsados[] = $nome;
            $nifsUsados[] = $nif;
            $telefonesUsados[] = $telefone;

            $fornecedores[] = [
                'nome' => $nome,
                'descricao' => $faker->text(100),
                'localizacao' => $faker->city,
                'nif' => $nif,
                'telefone' => (int)$telefone,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Inserir em lotes para melhor performance
            if (count($fornecedores) >= 100) {
                DB::table('fornecedors')->insert($fornecedores);
                $fornecedores = [];
            }
        }

        // Inserir os restantes
        if (!empty($fornecedores)) {
            DB::table('fornecedors')->insert($fornecedores);
        }

        // Buscar IDs dos fornecedores criados
        $fornecedorIds = DB::table('fornecedors')->pluck('id')->toArray();

        // Lista de 1000 nomes de medicamentos únicos
        $nomesMedicamentos = $this->gerarNomesMedicamentos(1000);

        // Criar 1000 medicamentos
        $medicines = [];
        
        for ($i = 0; $i < 1000; $i++) {
            $stock = 0;
            $minimum_stock = $faker->numberBetween(5, 20);
            $last_alerted_at = null;

            // Distribuição conforme especificado
            if ($i < 550) {
                // 550 com estoque acima do mínimo
                $stock = $faker->numberBetween($minimum_stock + 1, 100);
            } elseif ($i < 750) {
                // 200 com validade nos próximos 45 dias
                $stock = $faker->numberBetween(0, $minimum_stock + 10);
            } else {
                // 250 restantes com estoque mínimo ou abaixo
                $stock = $faker->numberBetween(0, $minimum_stock);
                if ($stock <= $minimum_stock) {
                    $last_alerted_at = $faker->dateTimeBetween('-30 days', 'now');
                }
            }

            // Data de expiração
            if ($i >= 550 && $i < 750) {
                // 200 com validade nos próximos 45 dias
                $expiration_date = $faker->dateTimeBetween('now', '+45 days');
            } else {
                $expiration_date = $faker->dateTimeBetween('+3 months', '+3 years');
            }

            $medicines[] = [
                'name' => $nomesMedicamentos[$i],
                'description' => $faker->text(200),
                'fornecedor_id' => $faker->randomElement($fornecedorIds),
                'batch' => 'LOTE' . $faker->unique()->numberBetween(10000, 99999),
                'expiration_date' => $expiration_date,
                'stock' => $stock,
                'price' => $faker->randomFloat(2, 0.5, 100),
                'category' => $faker->randomElement(['Analgésico', 'Antibiótico', 'Anti-inflamatório', 'Antidepressivo', 'Antihistamínico', 'Vitaminas', 'Cardiovascular', 'Diabetes', 'Dermatológico', 'Oftalmológico']),
                'minimum_stock' => $minimum_stock,
                'last_alerted_at' => $last_alerted_at,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Inserir em lotes
            if (count($medicines) >= 100) {
                DB::table('medicines')->insert($medicines);
                $medicines = [];
            }
        }

        // Inserir os restantes
        if (!empty($medicines)) {
            DB::table('medicines')->insert($medicines);
        }

        // Buscar IDs dos medicamentos
        $medicineIds = DB::table('medicines')->pluck('id')->toArray();
        
        // Verificar se existe usuário, se não, criar
        $userId = DB::table('users')->value('id');
        
        if (!$userId) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Administrador',
                'email' => 'admin@farmacia.pt',
                'password' => bcrypt('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Criar 700 movimentos
        $movements = [];
        
        for ($i = 0; $i < 700; $i++) {
            $medicineId = $faker->randomElement($medicineIds);
            $type = $i < 490 ? 'entrada' : 'saida'; // 70% entrada
            $quantity = $type === 'entrada' ? 
                $faker->numberBetween(10, 100) : 
                $faker->numberBetween(1, 30);

            $movements[] = [
                'medicine_id' => $medicineId,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $faker->text(50),
                'movement_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Inserir em lotes
            if (count($movements) >= 100) {
                DB::table('movements')->insert($movements);
                $movements = [];
            }
        }

        // Inserir os restantes
        if (!empty($movements)) {
            DB::table('movements')->insert($movements);
        }

        $this->command->info('Seeder executado com sucesso!');
        $this->command->info('Fornecedores criados: ' . DB::table('fornecedors')->count());
        $this->command->info('Medicamentos criados: ' . DB::table('medicines')->count());
        $this->command->info('Movimentos criados: ' . DB::table('movements')->count());
    }

    private function gerarNomesMedicamentos($quantidade)
    {
        $prefixos = ['Paracet', 'Ibupro', 'Dipro', 'Cata', 'Vita', 'Omepra', 'Losa', 'Sinta', 'Meta', 'Dexa', 'Cipro', 'Amoxi', 'Azitro', 'Clari', 'Levo', 'Moxi', 'Cefalo', 'Penicil', 'Tetracic', 'Amino'];
        $sufixos = ['amol', 'fen', 'zol', 'dip', 'max', 'forte', 'plus', 'din', 'cin', 'micina', 'xina', 'vir', 'stat', 'pril', 'sartan', 'vastatina', 'prazol', 'tidine', 'pine', 'lam'];
        $formas = ['Comprimido', 'Cápsula', 'Xarope', 'Pomada', 'Gotas', 'Injetável', 'Supositório', 'Spray', 'Creme', 'Gel'];
        $dosagens = ['100mg', '250mg', '500mg', '750mg', '1000mg', '50mg', '200mg', '300mg', '400mg', '600mg'];

        $nomes = [];
        $tentativas = 0;
        $maxTentativas = $quantidade * 2;
        
        while (count($nomes) < $quantidade && $tentativas < $maxTentativas) {
            $prefixo = $prefixos[array_rand($prefixos)];
            $sufixo = $sufixos[array_rand($sufixos)];
            $forma = $formas[array_rand($formas)];
            $dosagem = $dosagens[array_rand($dosagens)];
            
            $nomeCompleto = $prefixo . $sufixo . ' ' . $dosagem . ' ' . $forma;
            
            if (!in_array($nomeCompleto, $nomes)) {
                $nomes[] = $nomeCompleto;
            }
            
            $tentativas++;
        }

        // Se não conseguiu gerar nomes suficientes, completar com números
        while (count($nomes) < $quantidade) {
            $nomes[] = 'Medicamento ' . (count($nomes) + 1) . ' ' . $formas[array_rand($formas)] . ' ' . $dosagens[array_rand($dosagens)];
        }

        return $nomes;
    }
    
     }

    