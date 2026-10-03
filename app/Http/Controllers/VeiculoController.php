<?php

namespace App\Http\Controllers;

use App\Http\Requests\VeiculoRequest;
use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VeiculoController extends Controller
{
    /**
     * List the vehicles in stock.
     */
    public function index(Request $request): Response
    {
        $filtros = $request->only(['busca', 'status', 'categoria']);

        return Inertia::render('veiculos/index', [
            'veiculos' => Veiculo::query()
                ->filtrar($filtros)
                ->latest()
                ->paginate(9)
                ->withQueryString(),
            'filtros' => $filtros,
            'opcoes' => $this->opcoes(),
        ]);
    }

    /**
     * Show the form for registering a new vehicle.
     */
    public function create(): Response
    {
        return Inertia::render('veiculos/create', [
            'opcoes' => $this->opcoes(),
        ]);
    }

    /**
     * Store a newly registered vehicle.
     */
    public function store(VeiculoRequest $request): RedirectResponse
    {
        $veiculo = Veiculo::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$veiculo->marca} {$veiculo->modelo} cadastrado com sucesso."]);

        return to_route('veiculos.show', $veiculo);
    }

    /**
     * Show the vehicle details.
     */
    public function show(Veiculo $veiculo): Response
    {
        return Inertia::render('veiculos/show', [
            'veiculo' => $veiculo,
            'opcoes' => $this->opcoes(),
        ]);
    }

    /**
     * Show the form for editing the vehicle.
     */
    public function edit(Veiculo $veiculo): Response
    {
        return Inertia::render('veiculos/edit', [
            'veiculo' => $veiculo,
            'opcoes' => $this->opcoes(),
        ]);
    }

    /**
     * Update the vehicle.
     */
    public function update(VeiculoRequest $request, Veiculo $veiculo): RedirectResponse
    {
        $veiculo->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Veículo atualizado com sucesso.']);

        return to_route('veiculos.show', $veiculo);
    }

    /**
     * Remove the vehicle from stock.
     */
    public function destroy(Veiculo $veiculo): RedirectResponse
    {
        $veiculo->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$veiculo->marca} {$veiculo->modelo} removido do estoque."]);

        return to_route('veiculos.index');
    }

    /**
     * The select options shared by the vehicle pages.
     *
     * @return array<string, mixed>
     */
    private function opcoes(): array
    {
        return [
            'categorias' => Veiculo::CATEGORIAS,
            'combustiveis' => Veiculo::COMBUSTIVEIS,
            'cambios' => Veiculo::CAMBIOS,
            'status' => Veiculo::STATUS,
        ];
    }
}
