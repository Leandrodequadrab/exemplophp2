<?php

namespace Database\Seeders;

use App\Models\Veiculo;
use Illuminate\Database\Seeder;

class VeiculoSeeder extends Seeder
{
    /**
     * Seed the dealership stock with a realistic set of vehicles.
     */
    public function run(): void
    {
        $veiculos = [
            ['Toyota', 'Corolla', '2.0 XEi Dynamic Force', 'Sedan', 2023, 2024, 'Prata', 'Flex', 'CVT', 18500, 'RTA2F34', 149900, 'disponivel'],
            ['Honda', 'Civic', '2.0 Touring Hybrid', 'Sedan', 2024, 2024, 'Preto', 'Híbrido', 'Automático', 6200, 'QHC5B21', 259900, 'disponivel'],
            ['Jeep', 'Compass', '1.3 T270 Longitude', 'SUV', 2022, 2023, 'Branco', 'Flex', 'Automático', 34100, 'PJE7C88', 162500, 'disponivel'],
            ['Volkswagen', 'T-Cross', '1.4 250 TSI Highline', 'SUV', 2023, 2023, 'Cinza', 'Flex', 'Automático', 21800, 'SVW1D45', 139990, 'reservado'],
            ['Chevrolet', 'Onix', '1.0 Turbo Premier', 'Hatch', 2024, 2025, 'Vermelho', 'Flex', 'Automático', 3200, 'TCH4E12', 104900, 'disponivel'],
            ['Hyundai', 'HB20', '1.0 Platinum Plus', 'Hatch', 2023, 2024, 'Azul', 'Flex', 'Automático', 15400, 'RHY9A76', 96900, 'vendido'],
            ['Toyota', 'Hilux', '2.8 SRX Plus 4x4', 'Picape', 2023, 2024, 'Branco', 'Diesel', 'Automático', 27900, 'QTY3G55', 329900, 'disponivel'],
            ['Fiat', 'Toro', '2.2 Volcano 4x4', 'Picape', 2022, 2022, 'Cinza', 'Diesel', 'Automático', 48700, 'PFT6H09', 179900, 'disponivel'],
            ['BMW', '320i', '2.0 M Sport', 'Sedan', 2023, 2023, 'Azul', 'Gasolina', 'Automático', 12300, 'SBM8J31', 349900, 'disponivel'],
            ['Porsche', '911', 'Carrera S 3.0', 'Esportivo', 2022, 2022, 'Amarelo', 'Gasolina', 'Automático', 8900, 'RPO1K11', 1189000, 'reservado'],
            ['BYD', 'Dolphin', 'EV Plus', 'Hatch', 2025, 2025, 'Branco', 'Elétrico', 'Automático', 0, null, 159800, 'disponivel'],
            ['Fiat', 'Fiorino', '1.4 Endurance', 'Utilitário', 2021, 2022, 'Branco', 'Flex', 'Manual', 61200, 'QFI2L64', 89900, 'vendido'],
            ['Volkswagen', 'Nivus', '1.0 200 TSI Highline', 'SUV', 2024, 2024, 'Preto', 'Flex', 'Automático', 9800, 'TVW5M27', 132900, 'disponivel'],
            ['Ford', 'Mustang', '5.0 V8 GT Performance', 'Esportivo', 2021, 2022, 'Vermelho', 'Gasolina', 'Automático', 15600, 'QFM0N50', 449900, 'vendido'],
        ];

        foreach ($veiculos as [$marca, $modelo, $versao, $categoria, $anoFab, $anoMod, $cor, $combustivel, $cambio, $km, $placa, $preco, $status]) {
            Veiculo::updateOrCreate(
                ['marca' => $marca, 'modelo' => $modelo, 'versao' => $versao],
                [
                    'categoria' => $categoria,
                    'ano_fabricacao' => $anoFab,
                    'ano_modelo' => $anoMod,
                    'cor' => $cor,
                    'combustivel' => $combustivel,
                    'cambio' => $cambio,
                    'quilometragem' => $km,
                    'placa' => $placa,
                    'preco' => $preco,
                    'status' => $status,
                    'descricao' => "{$marca} {$modelo} {$versao} em excelente estado, revisado e com garantia da concessionária.",
                ],
            );
        }
    }
}
