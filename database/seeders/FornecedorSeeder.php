<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class FornecedorSeeder extends Seeder
{
  private $cidadesAngola = [
        'Luanda', 'Huambo', 'Lobito', 'Benguela', 'Lubango', 
        'Malanje', 'Namibe', 'Soyo', 'Cabinda', 'Uíge',
        'Sumbe', 'Menongue', 'Caxito', 'Ondjiva', 'Ndalatando',
        'Dundo', 'Mbanza Kongo', 'Saurimo', 'Luena', 'Cuito'
    ];

    // Prefixos telefónicos de Angola
    private $prefixosTelefone = [
        '222', '231', '232', '233', '234', '235', '236', '241', '242', '244',
        '251', '252', '253', '254', '255', '261', '262', '272', '281', '291'
    ];

    // Nomes de empresas farmacêuticas
    private $nomesFornecedores = [
        'Farmacom', 'MediAngola', 'FarmaPro', 'SaúdeTotal', 'MediPlus',
        'FarmaQuality', 'MediCare', 'FarmaExpress', 'SaúdeFarma', 'MediGroup',
        'FarmaVida', 'MediSolution', 'FarmaTech', 'SaúdePrime', 'MediExpertg',
        'FarmaGlobal', 'MediPremium', 'FarmaStar', 'SaúdeMax', 'MediElite'
    ];

    public function run(): void
    {
           $fornecedores = [];

        for ($i = 1; $i <= 100; $i++) {
            $nome = $this->gerarNomeFornecedor($i);
            $nif = $this->gerarNIF($i);
            $telefone = $this->gerarTelefone();

            $fornecedores[] = [
                'nome' => $nome,
                'descricao' => $this->gerarDescricao($nome),
                'localizacao' => $this->cidadesAngola[array_rand($this->cidadesAngola)],
                'nif' => $nif,
                'telefone' => $telefone,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Inserir em lotes para melhor performance
        foreach (array_chunk($fornecedores, 25) as $lote) {
            DB::table('fornecedors')->insert($lote);
        }
    }

      private function gerarNomeFornecedor($index)
    {
        $nomeBase = $this->nomesFornecedores[array_rand($this->nomesFornecedores)];
        $sufixo = $index > 20 ? ' ' . Str::random(3) . rand(1, 99) : '';
        
        return $nomeBase . $sufixo;
    }

    private function gerarNIF($index)
    {
        // Formato: 00 + 4 dígitos + 3 letras + 5 caracteres (letras/números)
        $numeros = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $letras = strtoupper(Str::random(3));
        $final = strtoupper(Str::random(5));
        
        return "00{$numeros}{$letras}{$final}";
    }

    private function gerarTelefone()
    {
        $prefixo = $this->prefixosTelefone[array_rand($this->prefixosTelefone)];
        $numero = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        return (int) ($prefixo . $numero);
    }

    private function gerarDescricao($nome)
    {
        $descricoes = [
            "{$nome} - Fornecedor líder em medicamentos essenciais e equipamentos médicos",
            "{$nome} - Especializada em distribuição de fármacos e produtos farmacêuticos",
            "{$nome} - Fornecedor certificado de medicamentos genéricos e de marca",
            "{$nome} - Distribuidor autorizado de produtos farmacêuticos em Angola",
            "{$nome} - Especialistas em importação e comercialização de medicamentos",
            "{$nome} - Fornecedor de confiança para hospitais e clínicas",
            "{$nome} - Distribuição nacional de produtos farmacêuticos de qualidade",
            "{$nome} - Comprometida com o acesso a medicamentos essenciais",
        ];

        return $descricoes[array_rand($descricoes)];
    }
}
