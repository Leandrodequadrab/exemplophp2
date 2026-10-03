import { Link } from '@inertiajs/react';
import { Calendar, Cog, Fuel, Gauge, Pencil, Trash2 } from 'lucide-react';
import ExcluirVeiculoDialog from '@/components/excluir-veiculo-dialog';
import { Button } from '@/components/ui/button';
import VeiculoFoto from '@/components/veiculo-foto';
import VeiculoStatusBadge from '@/components/veiculo-status-badge';
import { formatarKm, formatarPreco } from '@/lib/format';
import { edit, show } from '@/routes/veiculos';
import type { Veiculo } from '@/types/veiculo';

export default function VeiculoCard({ veiculo }: { veiculo: Veiculo }) {
    const specs = [
        {
            icone: Calendar,
            valor: `${veiculo.ano_fabricacao}/${veiculo.ano_modelo}`,
        },
        { icone: Gauge, valor: formatarKm(veiculo.quilometragem) },
        { icone: Fuel, valor: veiculo.combustivel },
        { icone: Cog, valor: veiculo.cambio },
    ];

    return (
        <article className="group flex flex-col overflow-hidden rounded-2xl border bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10">
            <Link
                href={show(veiculo.id)}
                prefetch
                className="relative block aspect-[16/10] overflow-hidden"
            >
                <VeiculoFoto
                    veiculo={veiculo}
                    className="transition-transform duration-500 group-hover:scale-105"
                />
                <div className="absolute inset-x-0 top-0 flex items-start justify-between p-3">
                    <span className="rounded-full bg-black/50 px-2.5 py-0.5 text-xs font-medium text-white backdrop-blur-sm">
                        {veiculo.categoria}
                    </span>
                    <VeiculoStatusBadge
                        status={veiculo.status}
                        className="bg-white/90 dark:bg-black/60"
                    />
                </div>
            </Link>

            <div className="flex flex-1 flex-col gap-4 p-5">
                <div>
                    <p className="text-xs font-semibold tracking-wider text-primary uppercase">
                        {veiculo.marca}
                    </p>
                    <Link
                        href={show(veiculo.id)}
                        className="line-clamp-1 text-lg font-bold hover:text-primary"
                    >
                        {veiculo.modelo}
                    </Link>
                    <p className="line-clamp-1 text-sm text-muted-foreground">
                        {veiculo.versao || ' '}
                    </p>
                </div>

                <dl className="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                    {specs.map(({ icone: Icone, valor }) => (
                        <div
                            key={valor}
                            className="flex items-center gap-2 text-muted-foreground"
                        >
                            <Icone className="size-4 shrink-0" />
                            <dd className="truncate">{valor}</dd>
                        </div>
                    ))}
                </dl>

                <div className="mt-auto flex items-end justify-between gap-2 border-t pt-4">
                    <div>
                        <p className="text-xs text-muted-foreground">Preço</p>
                        <p className="text-xl font-extrabold tracking-tight">
                            {formatarPreco(veiculo.preco)}
                        </p>
                    </div>
                    <div className="flex gap-1">
                        <Button
                            variant="ghost"
                            size="icon"
                            asChild
                            title="Editar"
                        >
                            <Link href={edit(veiculo.id)}>
                                <Pencil className="size-4" />
                                <span className="sr-only">Editar</span>
                            </Link>
                        </Button>
                        <ExcluirVeiculoDialog veiculo={veiculo}>
                            <Button
                                variant="ghost"
                                size="icon"
                                title="Excluir"
                                className="text-red-600 hover:bg-red-500/10 hover:text-red-600 dark:text-red-400"
                            >
                                <Trash2 className="size-4" />
                                <span className="sr-only">Excluir</span>
                            </Button>
                        </ExcluirVeiculoDialog>
                    </div>
                </div>
            </div>
        </article>
    );
}
