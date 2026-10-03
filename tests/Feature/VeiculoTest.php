<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VeiculoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function dadosValidos(array $sobrescrever = []): array
    {
        return [
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'versao' => '2.0 XEi',
            'categoria' => 'Sedan',
            'ano_fabricacao' => 2023,
            'ano_modelo' => 2024,
            'cor' => 'Prata',
            'combustivel' => 'Flex',
            'cambio' => 'CVT',
            'quilometragem' => 15000,
            'placa' => 'abc-1d23',
            'preco' => 149900.50,
            'status' => 'disponivel',
            'foto_url' => null,
            'descricao' => 'Único dono.',
            ...$sobrescrever,
        ];
    }

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get(route('veiculos.index'))->assertRedirect(route('login'));
    }

    public function test_vehicles_can_be_listed_and_filtered()
    {
        Veiculo::factory()->create(['marca' => 'Honda', 'modelo' => 'Civic', 'status' => 'disponivel']);
        Veiculo::factory()->create(['marca' => 'Fiat', 'modelo' => 'Toro', 'status' => 'vendido']);

        $this->actingAs(User::factory()->create())
            ->get(route('veiculos.index', ['busca' => 'civic']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('veiculos/index')
                ->has('veiculos.data', 1)
                ->where('veiculos.data.0.modelo', 'Civic'),
            );
    }

    public function test_a_vehicle_can_be_created()
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('veiculos.store'), $this->dadosValidos());

        $veiculo = Veiculo::firstOrFail();

        $response->assertRedirect(route('veiculos.show', $veiculo));
        $this->assertSame('ABC1D23', $veiculo->placa);
        $this->assertSame('149900.50', $veiculo->preco);
    }

    public function test_invalid_data_is_rejected()
    {
        $this->actingAs(User::factory()->create())
            ->post(route('veiculos.store'), $this->dadosValidos([
                'marca' => '',
                'categoria' => 'Foguete',
                'ano_modelo' => 2020,
                'placa' => '123',
            ]))
            ->assertSessionHasErrors(['marca', 'categoria', 'ano_modelo', 'placa']);

        $this->assertDatabaseCount('veiculos', 0);
    }

    public function test_a_vehicle_can_be_viewed_and_edited()
    {
        $veiculo = Veiculo::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('veiculos.show', $veiculo))->assertOk();
        $this->actingAs($user)->get(route('veiculos.edit', $veiculo))->assertOk();

        $this->actingAs($user)
            ->put(route('veiculos.update', $veiculo), $this->dadosValidos([
                'placa' => $veiculo->placa,
                'status' => 'vendido',
            ]))
            ->assertRedirect(route('veiculos.show', $veiculo));

        $this->assertSame('vendido', $veiculo->refresh()->status);
    }

    public function test_a_vehicle_can_be_deleted()
    {
        $veiculo = Veiculo::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('veiculos.destroy', $veiculo))
            ->assertRedirect(route('veiculos.index'));

        $this->assertModelMissing($veiculo);
    }

    public function test_the_dashboard_shows_stock_statistics()
    {
        Veiculo::factory()->create(['status' => 'disponivel', 'preco' => 100000]);
        Veiculo::factory()->create(['status' => 'vendido', 'preco' => 50000]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('dashboard')
                ->where('estatisticas.total', 2)
                ->where('estatisticas.disponiveis', 1)
                ->where('estatisticas.vendidos', 1),
            );
    }
}
