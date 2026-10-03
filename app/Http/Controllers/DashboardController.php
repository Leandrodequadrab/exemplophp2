<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the dealership overview.
     */
    public function __invoke(): Response
    {
        $porStatus = Veiculo::query()
            ->selectRaw('status, COUNT(*) as total, COALESCE(SUM(preco), 0) as valor')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $total = fn (string $status): int => (int) ($porStatus[$status]->total ?? 0);
        $valor = fn (string $status): float => (float) ($porStatus[$status]->valor ?? 0);

        return Inertia::render('dashboard', [
            'estatisticas' => [
                'total' => (int) $porStatus->sum('total'),
                'disponiveis' => $total('disponivel'),
                'reservados' => $total('reservado'),
                'vendidos' => $total('vendido'),
                'valor_estoque' => $valor('disponivel') + $valor('reservado'),
                'valor_vendido' => $valor('vendido'),
            ],
            'por_categoria' => Veiculo::query()
                ->selectRaw('categoria, COUNT(*) as total')
                ->groupBy('categoria')
                ->orderByDesc('total')
                ->get()
                ->map(fn (Veiculo $linha) => [
                    'categoria' => $linha->categoria,
                    'total' => (int) $linha->getAttribute('total'),
                ]),
            'recentes' => Veiculo::query()->latest()->limit(5)->get(),
        ]);
    }
}
