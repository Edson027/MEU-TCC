<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class FornecedoresSeeder extends Seeder
{
  
  public function run(): void
    {
        $cidadesAngola = [
            'Luanda', 'Huambo', 'Lobito', 'Benguela', 'Lubango', 'Malanje', 
            'Namibe', 'Soyo', 'Cabinda', 'Uíge', 'Sumbe', 'Menongue', 
            'Caxito', 'Ndalatando', 'Ondjiva', 'Mbanza Kongo', 'Saurimo',
            'Lucapa', 'Dundo', 'Nova Lisboa'
        ];
        
        $descricoes = [
            'Distribuidor de medicamentos e produtos farmacêuticos',
            'Fornecedor de produtos médicos e hospitalares',
            'Importador de medicamentos e equipamentos de saúde',
            'Grossista de produtos farmacêuticos',
            'Distribuidor autorizado de medicamentos',
            'Fornecedor de matérias-primas farmacêuticas',
            'Laboratório farmacêutico',
            'Representante de marcas farmacêuticas internacionais',
            'Distribuidor de produtos de saúde e bem-estar',
            'Fornecedor de equipamentos médicos e medicamentos'
        ];
        
        $fornecedores = [];
        $now = Carbon::now();
        
        for ($i = 1; $i <= 100; $i++) {
            $cidade = $cidadesAngola[array_rand($cidadesAngola)];
            
            $fornecedores[] = [
                'nome' => $this->generateSupplierName($i, $cidade),
                'descricao' => $descricoes[array_rand($descricoes)],
                'localizacao' => $cidade . ', Angola',
                'nif' => $this->generateNIF($i),
                'telefone' => $this->generateAngolanPhoneNumber(),
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        DB::table('fornecedors')->insert($fornecedores);
        
        $this->command->info('100 fornecedores angolanos criados com sucesso!');
    }
    
    /**
     * Gera um nome de fornecedor
     */
    private function generateSupplierName(int $index, string $cidade): string
    {
        $prefixos = ['Farmácia', 'Laboratório', 'Distribuidora', 'Drogaria', 'Centro Médico', 'Clínica'];
        $nomes = ['Nacional', 'Central', 'Premium', 'Especializada', 'Qualidade', 'Super', 'Maxi', 'Mega'];
        $sufixos = ['de Angola', 'de ' . $cidade, 'Lda', 'Limitada', 'SA', 'e Filhos'];
        
        $nome = $prefixos[array_rand($prefixos)] . ' ' . 
                $nomes[array_rand($nomes)] . ' ' . 
                $sufixos[array_rand($sufixos)];
        
        // Garantir que o nome seja único adicionando um número se necessário
        return $nome . ' ' . $index;
    }
    
    /**
     * Gera um NIF no formato angolano
     */
    private function generateNIF(int $index): string
    {
        // Formato: 004617743BE049 (exemplo)
        $numero = str_pad($index, 9, '0', STR_PAD_LEFT);
        $letras = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);
        $sufixo = substr(str_shuffle('0123456789ABCDEF'), 0, 3);
        
        return $numero . $letras . $sufixo;
    }
    
    /**
     * Gera um número de telefone angolano válido
     */
    private function generateAngolanPhoneNumber(): int
    {
        // Prefixos de operadoras em Angola
        $operadoras = [
            '923', '924', '925', // Unitel
            '928', '929', // Movicel
            '921', '922', // Angola Telecom
            '931', '932', '933' // Outras operadoras
        ];
        
        $operadora = $operadoras[array_rand($operadoras)];
        $numero = rand(100000, 999999);
        
        return (int) ($operadora . $numero);
    }

}
