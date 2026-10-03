<?php

namespace App\Models;

use Database\Factories\VeiculoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $marca
 * @property string $modelo
 * @property string|null $versao
 * @property string $categoria
 * @property int $ano_fabricacao
 * @property int $ano_modelo
 * @property string $cor
 * @property string $combustivel
 * @property string $cambio
 * @property int $quilometragem
 * @property string|null $placa
 * @property string $preco
 * @property string $status
 * @property string|null $foto_url
 * @property string|null $descricao
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'marca',
    'modelo',
    'versao',
    'categoria',
    'ano_fabricacao',
    'ano_modelo',
    'cor',
    'combustivel',
    'cambio',
    'quilometragem',
    'placa',
    'preco',
    'status',
    'foto_url',
    'descricao',
])]
class Veiculo extends Model
{
    /** @use HasFactory<VeiculoFactory> */
    use HasFactory;

    public const array CATEGORIAS = ['Hatch', 'Sedan', 'SUV', 'Picape', 'Esportivo', 'Utilitário'];

    public const array COMBUSTIVEIS = ['Flex', 'Gasolina', 'Etanol', 'Diesel', 'Híbrido', 'Elétrico'];

    public const array CAMBIOS = ['Manual', 'Automático', 'CVT'];

    public const array STATUS = [
        'disponivel' => 'Disponível',
        'reservado' => 'Reservado',
        'vendido' => 'Vendido',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ano_fabricacao' => 'integer',
            'ano_modelo' => 'integer',
            'quilometragem' => 'integer',
            'preco' => 'decimal:2',
        ];
    }

    /**
     * Filter vehicles by a free-text search and the listing filters.
     *
     * @param  Builder<Veiculo>  $query
     * @param  array{busca?: string|null, status?: string|null, categoria?: string|null}  $filtros
     */
    public function scopeFiltrar(Builder $query, array $filtros): void
    {
        $query
            ->when($filtros['busca'] ?? null, function (Builder $query, string $busca) {
                $termo = '%'.mb_strtolower($busca).'%';

                $query->where(function (Builder $query) use ($termo) {
                    $query->whereRaw('LOWER(marca) LIKE ?', [$termo])
                        ->orWhereRaw('LOWER(modelo) LIKE ?', [$termo])
                        ->orWhereRaw('LOWER(versao) LIKE ?', [$termo])
                        ->orWhereRaw('LOWER(placa) LIKE ?', [$termo]);
                });
            })
            ->when($filtros['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filtros['categoria'] ?? null, fn (Builder $query, string $categoria) => $query->where('categoria', $categoria));
    }
}
