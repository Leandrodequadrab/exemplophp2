<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Show the public showroom page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('welcome', [
            'destaques' => Veiculo::query()
                ->where('status', 'disponivel')
                ->latest()
                ->limit(6)
                ->get(),
            'totalDisponiveis' => Veiculo::query()->where('status', 'disponivel')->count(),
        ]);
    }
}
