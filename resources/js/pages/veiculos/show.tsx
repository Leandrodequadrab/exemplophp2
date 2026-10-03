import { Head, Link } from '@inertiajs/react';
import {
    ArrowLeft,
    Calendar,
    CalendarClock,
    CarFront,
    Cog,
    Fuel,
    Gauge,
    Palette,
    Pencil,
    RectangleHorizontal,
    ShieldCheck,
    Trash2,
} from 'lucide-react';
import ExcluirVeiculoDialog from '@/components/excluir-veiculo-dialog';
import { Button } from '@/components/ui/button';
import VeiculoFoto from '@/components/veiculo-foto';
import VeiculoStatusBadge from '@/components/veiculo-status-badge';
import {
    formatarData,
    formatarKm,
    formatarPlaca,
    formatarPreco,
} from '@/lib/format';
import { edit, index } from '@/routes/veiculos';
import type { Veiculo } from '@/types/veiculo';

export default function VeiculosShow({ veiculo }: { veiculo: Veiculo }) {
    const nome = `${veiculo.marca} ${veiculo.modelo}`;

    const especificacoes = [
        {
            icone: Calendar,
            label: 'Ano',
            valor: `${veiculo.ano_fabricacao}/${veiculo.ano_modelo}`,
        },
        {
            icone: Gauge,
            label: 'Quilometragem',
            valor: formatarKm(veiculo.quilometragem),
        },
        { icone: Fuel, label: 'Combustível', valor: veiculo.combustivel },
        { icone: Cog, label: 'Câmbio', valor: veiculo.cambio },
        { icone: Palette, label: 'Cor', valor: veiculo.cor },
        { icone: CarFront, label: 'Categoria', valor: veiculo.categoria },
        {
            icone: RectangleHorizontal,
            label: 'Placa',
            valor: formatarPlaca(veiculo.placa),
        },
        {
            icone: CalendarClock,
            label: 'Cadastrado em',
            valor: formatarData(veiculo.created_at),
        },
    ];

    return (
        <>
            <Head title={nome} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <div>
                    <Button variant="ghost" size="sm" asChild className="-ml-2">
                        <Link href={index()}>
                            <ArrowLeft />
                            Voltar ao estoque
                        </Link>
                    </Button>
                </div>

                <div className="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                    <div className="relative aspect-[16/10] overflow-hidden rounded-2xl border shadow-sm">
                        <VeiculoFoto
                            veiculo={veiculo}
                            iconClassName="size-32"
                            mostrarMarca
                        />
                    </div>

                    <div className="flex flex-col gap-6 rounded-2xl border bg-card p-6 shadow-sm">
                        <div className="space-y-3">
                            <VeiculoStatusBadge status={veiculo.status} />
                            <div>
                                <p className="text-sm font-semibold tracking-wider text-primary uppercase">
                                    {veiculo.marca}
                                </p>
                                <h1 className="text-3xl font-bold tracking-tight">
                                    {veiculo.modelo}
                                </h1>
                                {veiculo.versao && (
                                    <p className="text-muted-foreground">
                                        {veiculo.versao}
                                    </p>
                                )}
                            </div>
                        </div>

                        <div className="rounded-xl bg-gradient-to-br from-primary to-indigo-700 p-5 text-primary-foreground shadow-lg shadow-primary/20">
                            <p className="text-sm opacity-80">Preço de venda</p>
                            <p className="text-4xl font-extrabold tracking-tight">
                                {formatarPreco(veiculo.preco)}
                            </p>
                        </div>

                        <div className="flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4 text-sm">
                            <ShieldCheck className="size-5 shrink-0 text-emerald-600 dark:text-emerald-400" />
                            <span className="text-muted-foreground">
                                Veículo revisado com{' '}
                                <strong className="text-foreground">
                                    garantia de 3 meses
                                </strong>{' '}
                                da concessionária.
                            </span>
                        </div>

                        <div className="mt-auto grid grid-cols-2 gap-2">
                            <Button asChild size="lg">
                                <Link href={edit(veiculo.id)}>
                                    <Pencil />
                                    Editar
                                </Link>
                            </Button>
                            <ExcluirVeiculoDialog veiculo={veiculo}>
                                <Button
                                    variant="outline"
                                    size="lg"
                                    className="text-red-600 hover:bg-red-500/10 hover:text-red-600 dark:text-red-400"
                                >
                                    <Trash2 />
                                    Excluir
                                </Button>
                            </ExcluirVeiculoDialog>
                        </div>
                    </div>
                </div>

                <div className="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                    <section className="rounded-2xl border bg-card p-6 shadow-sm">
                        <h2 className="mb-5 text-lg font-semibold">
                            Ficha técnica
                        </h2>
                        <dl className="grid gap-4 sm:grid-cols-2">
                            {especificacoes.map(
                                ({ icone: Icone, label, valor }) => (
                                    <div
                                        key={label}
                                        className="flex items-center gap-3 rounded-xl bg-muted/50 p-3"
                                    >
                                        <div className="flex size-10 shrink-0 items-center justify-center rounded-lg bg-background text-primary shadow-xs">
                                            <Icone className="size-5" />
                                        </div>
                                        <div className="min-w-0">
                                            <dt className="text-xs text-muted-foreground">
                                                {label}
                                            </dt>
                                            <dd className="truncate font-semibold">
                                                {valor}
                                            </dd>
                                        </div>
                                    </div>
                                ),
                            )}
                        </dl>
                    </section>

                    <section className="rounded-2xl border bg-card p-6 shadow-sm">
                        <h2 className="mb-3 text-lg font-semibold">
                            Descrição
                        </h2>
                        <p className="leading-relaxed whitespace-pre-line text-muted-foreground">
                            {veiculo.descricao ||
                                'Nenhuma descrição informada para este veículo.'}
                        </p>
                    </section>
                </div>
            </div>
        </>
    );
}

VeiculosShow.layout = {
    breadcrumbs: [
        { title: 'Estoque de veículos', href: index() },
        { title: 'Detalhes', href: index() },
    ],
};
