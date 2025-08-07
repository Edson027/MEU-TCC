
    public function run(): void
    {
        /*
          $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'description' => 'Analgésico e antipirético para alívio de dores e febre',
                'batch' => 'LOTEA02',
                'expiration_date' => Carbon::now()->addYears(2)->format('Y-m-d'),
                'stock' => 150,
                'price' => 5.99,
                'category' => 'Analgésico',
                'minimum_stock' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ibuprofeno 400mg',
                'description' => 'Anti-inflamatório não esteroide para dores e inflamações',
                'batch' => 'LOTEB01',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(6)->format('Y-m-d'),
                'stock' => 85,
                'price' => 7.50,
                'category' => 'Anti-inflamatório',
                'minimum_stock' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amoxicilina 500mg',
                'description' => 'Antibiótico de amplo espectro para infecções bacterianas',
                'batch' => 'LOT20221201',
                'expiration_date' => Carbon::now()->addMonths(8)->format('Y-m-d'),
                'stock' => 42,
                'price' => 12.80,
                'category' => 'Antibiótico',
                'minimum_stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Omeprazol 20mg',
                'description' => 'Inibidor da bomba de prótons para azia e refluxo',
                'batch' => 'LOTEB02',
                'expiration_date' => Carbon::now()->addYears(3)->format('Y-m-d'),
                'stock' => 120,
                'price' => 9.25,
                'category' => 'Gastrointestinal',
                'minimum_stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dipirona Sódica 500mg',
                'description' => 'Analgésico e antitérmico para dores moderadas',
                'batch' => 'LOTEC01',
                'expiration_date' => Carbon::now()->addMonths(18)->format('Y-m-d'),
                'stock' => 65,
                'price' => 4.75,
                'category' => 'Analgésico',
                'minimum_stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soro Fisiológico 0,9% 500ml',
                'description' => 'Solução para limpeza e hidratação',
                'batch' => 'LOTEC02',
                'expiration_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
                'stock' => 200,
                'price' => 3.20,
                'category' => 'Solução',
                'minimum_stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Losartana Potássica 50mg',
                'description' => 'Anti-hipertensivo para controle da pressão arterial',
                'batch' => 'LOTEA01',
                'expiration_date' => Carbon::now()->addYears(2)->addMonths(3)->format('Y-m-d'),
                'stock' => 38,
                'price' => 15.40,
                'category' => 'Cardiovascular',
                'minimum_stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dexametasona 4mg',
                'description' => 'Corticosteroide para inflamações e alergias',
                'batch' => 'LOTEB01',
                'expiration_date' => Carbon::now()->addMonths(10)->format('Y-m-d'),
                'stock' => 280,
                'price' => 8.90,
                'category' => 'Corticosteroide',
                'minimum_stock' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hidroclorotiazida 25mg',
                'description' => 'Diurético para tratamento de hipertensão',
                'batch' => 'LOTEC02',
                'expiration_date' => Carbon::now()->addYears(1)->format('Y-m-d'),
                'stock' => 50,
                'price' => 6.30,
                'category' => 'Diurético',
                'minimum_stock' => 51,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ácido Acetilsalicílico 100mg',
                'description' => 'Antiagregante plaquetário para prevenção cardiovascular',
                'batch' => 'LOTEA01',
                'expiration_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
                'stock' => 180,
                'price' => 2.99,
                'category' => 'Cardiovascular',
                'minimum_stock' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medicines')->insert($medicines);*/
/*

         $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'description' => 'Analgésico e antitérmico',
                'batch' => 'PC202301',
                'expiration_date' => Carbon::now()->addYears(2),
                'stock' => 5000,
                'category' => 'Analgésico',
                'minimum_stock' => 500,
            ],
            [
                'name' => 'Ibuprofeno 400mg',
                'description' => 'Anti-inflamatório não esteroidal',
                'batch' => 'IB202302',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(6),
                'stock' => 3000,
                'category' => 'Anti-inflamatório',
                'minimum_stock' => 150,
            ],
            [
                'name' => 'Amoxicilina 500mg',
                'description' => 'Antibiótico de amplo espectro',
                'batch' => 'AM202303',
                'expiration_date' => Carbon::now()->addYears(1),
                'stock' => 888, // Estoque abaixo do mínimo
                'category' => 'Antibiótico',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Dipirona 500mg',
                'description' => 'Analgésico e antitérmico',
                'batch' => 'DP202304',
                'expiration_date' => Carbon::now()->addYears(3),
                'stock' => 250,
                'category' => 'Analgésico',
                'minimum_stock' => 100,
            ],
            [
                'name' => 'Omeprazol 20mg',
                'description' => 'Inibidor de bomba de prótons',
                'batch' => 'OM202305',
                'expiration_date' => Carbon::now()->addMonths(1),
                'stock' => 500, // Estoque abaixo do mínimo
                'category' => 'Gastrointestinal',
                'minimum_stock' => 800,
            ],
            [
                'name' => 'Losartana 50mg',
                'description' => 'Anti-hipertensivo',
                'batch' => 'LO202306',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(9),
                'stock' => 120,
                'category' => 'Cardiovascular',
                'minimum_stock' => 100,
            ],
            [
                'name' => 'Sinvastatina 20mg',
                'description' => 'Redutor de colesterol',
                'batch' => 'SI202307',
                'expiration_date' => Carbon::now()->addYears(2),
                'stock' => 1800,
                'category' => 'Cardiovascular',
                'minimum_stock' => 1500,
            ],
            [
                'name' => 'Metformina 850mg',
                'description' => 'Hipoglicemiante oral',
                'batch' => 'ME202308',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(6),
                'stock' => 700, // Estoque abaixo do mínimo
                'category' => 'Diabetes',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Dexametasona 4mg',
                'description' => 'Corticosteroide',
                'batch' => 'DE202309',
                'expiration_date' => Carbon::now()->addYears(1),
                'stock' => 220,
                'category' => 'Corticosteroide',
                'minimum_stock' => 100,
            ],
            [
                'name' => 'Hidroclorotiazida 25mg',
                'description' => 'Diurético',
                'batch' => 'HI202310',
                'expiration_date' => Carbon::now()->addYears(2),
                'stock' => 900, // Estoque abaixo do mínimo
                'category' => 'Diurético',
                'minimum_stock' => 1200,
            ],
            [
                'name' => 'Atenolol 50mg',
                'description' => 'Betabloqueador',
                'batch' => 'AT202311',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(8),
                'stock' => 1400,
                'category' => 'Cardiovascular',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Captopril 25mg',
                'description' => 'Anti-hipertensivo',
                'batch' => 'CA202312',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(4),
                'stock' => 600, // Estoque abaixo do mínimo
                'category' => 'Cardiovascular',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Diclofenaco 50mg',
                'description' => 'Anti-inflamatório não esteroidal',
                'batch' => 'DI202313',
                'expiration_date' => Carbon::now()->addYears(1),
                'stock' => 25,
                'category' => 'Anti-inflamatório',
                'minimum_stock' => 150,
            ],
            [
                'name' => 'Ranitidina 150mg',
                'description' => 'Antiácido',
                'batch' => 'RA202314',
                'expiration_date' => Carbon::now()->addYears(2),
                'stock' => 300,
                'category' => 'Gastrointestinal',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Fluoxetina 20mg',
                'description' => 'Antidepressivo',
                'batch' => 'FL202315',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(6),
                'stock' => 800, // Estoque abaixo do mínimo
                'category' => 'Psiquiátrico',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Ciprofloxacino 500mg',
                'description' => 'Antibiótico',
                'batch' => 'CI202316',
                'expiration_date' => Carbon::now()->addYears(1),
                'stock' => 12,
                'category' => 'Antibiótico',
                'minimum_stock' => 100,
            ],
            [
                'name' => 'Loratadina 10mg',
                'description' => 'Anti-histamínico',
                'batch' => 'LO202317',
                'expiration_date' => Carbon::now()->addYears(2),
                'stock' => 500, // Estoque abaixo do mínimo
                'category' => 'Antialérgico',
                'minimum_stock' => 800,
            ],
            [
                'name' => 'Glibenclamida 5mg',
                'description' => 'Hipoglicemiante oral',
                'batch' => 'GL202318',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(3),
                'stock' => 900, // Estoque abaixo do mínimo
                'category' => 'Diabetes',
                'minimum_stock' => 1000,
            ],
            [
                'name' => 'Sertralina 50mg',
                'description' => 'Antidepressivo',
                'batch' => 'SE202319',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(9),
                'stock' => 15,
                'category' => 'Psiquiátrico',
                'minimum_stock' => 100,
            ],
            [
                'name' => 'Warfarina 5mg',
                'description' => 'Anticoagulante',
                'batch' => 'WA202320',
                'expiration_date' => Carbon::now()->addYears(1)->addMonths(6),
                'stock' => 7, // Estoque abaixo do mínimo
                'category' => 'Cardiovascular',
                'minimum_stock' => 10,
            ],
        ];

        foreach ($medicines as $medicineData) {
            $medicine = Medicine::create($medicineData);

            // Criar movimentos de entrada inicial
            Movement::create([
                'medicine_id' => $medicine->id,
                'user_id' => $user->id,
                'type' => 'entrada',
                'quantity' => $medicine->stock + rand(5, 20), // Entrada maior que o estoque atual
                'reason' => 'Entrada inicial de estoque',
                'movement_date' => Carbon::now()->subMonths(rand(1, 6)),
            ]);

            // Criar alguns movimentos de saída para medicamentos com estoque baixo
            if ($medicine->stock < $medicine->minimum_stock) {
                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' => $user->id,
                    'type' => 'saida',
                    'quantity' => rand(5, 10),
                    'reason' => 'Saída para atendimento',
                    'movement_date' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }

            // Criar alguns movimentos aleatórios adicionais
            $randomMovements = rand(1, 3);
            for ($i = 0; $i < $randomMovements; $i++) {
                $type = rand(0, 1) ? 'entrada' : 'saida';
                $quantity = rand(1, 10);
                
                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' => $user->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'reason' => $type === 'entrada' ? 'Reposição de estoque' : 'Uso em atendimento',
                    'movement_date' => Carbon::now()->subDays(rand(1, 90)),
                ]);
            }
        }  
            
            $this->command->info('Medicamentos e movimentos criados com sucesso!');*/


               // Create 30 medicines with stock below minimum (0-9)
        Medicine::factory()
            ->count(30)
            ->create([
                'stock' => fn() => rand(0, 9),
                'minimum_stock' => 10,
                'expiration_date' => Carbon::now()->addDays(rand(60, 365)), // Not expiring soon
            ])
            ->each(function ($medicine) {
                $this->createMovements($medicine, $medicine->stock);
            });

        // Create 20 medicines expiring in the next 25 days
        Medicine::factory()
            ->count(20)
            ->create([
                'expiration_date' => Carbon::now()->addDays(rand(1, 25)),
                'stock' => fn() => rand(10, 100), // At or above minimum
                'minimum_stock' => 10,
            ])
            ->each(function ($medicine) {
                $this->createMovements($medicine, $medicine->stock);
            });

        // Create 50 medicines with stock 10x or more above minimum (100+)
        Medicine::factory()
            ->count(50)
            ->create([
                'stock' => fn() => rand(100, 1000), // Large stock numbers
                'minimum_stock' => 10,
                'expiration_date' => Carbon::now()->addDays(rand(60, 365)), // Not expiring soon
            ])
            ->each(function ($medicine) {
                $this->createMovements($medicine, $medicine->stock);
            });
    }
/*
    protected function createMovements($medicine, $finalStock)
    {
        $userIds = range(1, 10); // Assuming you have at least 10 users
        $movementTypes = ['entrada', 'saida'];
        
        // Start with 0 stock
        $currentStock = 0;
        
        // Create initial entrada movement to build up stock
        $initialQuantity = $finalStock + rand(0, 50); // Sometimes more than final stock
        Movement::create([
            'medicine_id' => $medicine->id,
            'user_id' => $userIds[array_rand($userIds)],
            'type' => 'entrada',
            'quantity' => $initialQuantity,
            'reason' => 'Initial stock',
            'movement_date' => Carbon::now()->subDays(rand(1, 30)),
        ]);
        
        $currentStock += $initialQuantity;
        
        // If we have more than final stock, create some saida movements
        if ($currentStock > $finalStock) {
            $toRemove = $currentStock - $finalStock;
            $chunks = rand(1, 5); // Split into 1-5 movements
            
            for ($i = 0; $i < $chunks; $i++) {
                $quantity = $i === $chunks - 1 
                    ? $toRemove 
                    : rand(1, $toRemove);
                
                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' => $userIds[array_rand($userIds)],
                    'type' => 'saida',
                    'quantity' => $quantity,
                    'reason' => 'Consumption/usage',
                    'movement_date' => Carbon::now()->subDays(rand(1, 29)),
                ]);
                
                $toRemove -= $quantity;
                $currentStock -= $quantity;
            }
        }
        
        // Occasionally add some additional movements
        if (rand(0, 1)) {
            $extraMovements = rand(1, 3);
            
            for ($i = 0; $i < $extraMovements; $i++) {
                $type = $movementTypes[array_rand($movementTypes)];
                $quantity = rand(1, 50);
                
                if ($type === 'saida' && $quantity > $currentStock) {
                    $quantity = $currentStock;
                }
                
                Movement::create([
                    'medicine_id' => $medicine->id,
                    'user_id' => $userIds[array_rand($userIds)],
                    'type' => $type,
                    'quantity' => $quantity,
                    'reason' => $type === 'entrada' ? 'Restock' : 'Usage',
                    'movement_date' => Carbon::now()->subDays(rand(1, 28)),
                ]);
                
                if ($type === 'entrada') {
                    $currentStock += $quantity;
                } else {
                    $currentStock -= $quantity;
                }
            }
        }
    }*/
