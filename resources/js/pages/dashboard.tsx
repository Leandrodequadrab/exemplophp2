import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BadgeDollarSign,
    CarFront,
    CircleCheck,
    Clock,
    HandCoins,
    Plus,
    Warehouse,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import { Button } from '@/components/ui/button';
import VeiculoFoto from '@/components/veiculo-foto';
import VeiculoStatusBadge from '@/components/veiculo-status-badge';
import { formatarKm, formatarPreco } from '@/lib/format';
import { dashboard } from '@/routes';
import { create, index, show } from '@/routes/veiculos';
import type { Veiculo } from '@/types/veiculo';

type Props = {
    estatisticas: {
        total: number;
        disponiveis: number;
        reservados: number;
        vendidos: number;
        valor_estoque: number;
        valor_vendido: number;
    };
    por_categoria: { categoria: string; total: number }[];
    recentes: Veiculo[];
};

function Kpi({
    titulo,
    valor,
    detalhe,
    icone: Icone,
    corIcone,
}: {
    titulo: string;
    valor: number;
    detalhe: string;
    icone: LucideIcon;
    corIcone: string;
}) {
    return (
        <div className="rounded-2xl border bg-card p-5 shadow-sm">
            <div className="flex items-center justify-between">
                <p className="text-sm font-medium text-muted-foreground">
                    {titulo}
                </p>
                <div
                    className={`flex size-9 items-center justify-center rounded-lg ${corIcone}`}
                >
                    <Icone className="size-5" />
                </div>
            </div>
            <p className="mt-3 text-3xl font-bold tracking-tight">{valor}</p>
            <p className="mt-1 text-xs text-muted-foreground">{detalhe}</p>
        </div>
    );
}

export default function Dashboard({
    estatisticas,
    por_categoria,
    recentes,
}: Props) {
    const { auth } = usePage().props;
    const maiorCategoria = Math.max(1, ...por_categoria.map((c) => c.total));
    const primeiroNome = auth.user?.name.split(' ')[0];

    return (
        <>
            <Head title="Painel" />
            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <section className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 p-6 text-white shadow-lg md:p-8">
                    <div className="absolute -top-16 -right-16 size-64 rounded-full bg-blue-500/30 blur-3xl" />
                    <CarFront
                        className="absolute -right-6 -bottom-10 size-56 text-white/5"
                        strokeWidth={1}
                    />
                    <div className="relative flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <div className="space-y-2">
                            <p className="text-sm font-medium text-blue-200">
                                Bem-vindo de volta
                                {primeiroNome ? `, ${primeiroNome}` : ''}
                            </p>
                            <h1 className="text-2xl font-bold tracking-tight md:text-3xl">
                                Painel da concessionária
                            </h1>
                            <p className="max-w-lg text-sm text-blue-100/80">
                                Acompanhe o estoque, as reservas e as vendas em
                                tempo real.
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            <Button
                                asChild
                                size="lg"
                                className="bg-white text-slate-900 hover:bg-blue-50"
                            >
                                <Link href={create()}>
                                    <Plus />
                                    Cadastrar veículo
                                </Link>
                            </Button>
                            <Button
                                asChild
                                size="lg"
                                variant="outline"
                                className="border-white/30 bg-white/10 text-white hover:bg-white/20 hover:text-white"
                            >
                                <Link href={index()}>Ver estoque</Link>
                            </Button>
                        </div>
                    </div>
                </section>

                <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <Kpi
                        titulo="Total cadastrado"
                        valor={estatisticas.total}
                        detalhe="Veículos no sistema"
                        icone={Warehouse}
                        corIcone="bg-primary/10 text-primary"
                    />
                    <Kpi
                        titulo="Disponíveis"
                        valor={estatisticas.disponiveis}
                        detalhe="Prontos para venda"
                        icone={CircleCheck}
                        corIcone="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
                    />
                    <Kpi
                        titulo="Reservados"
                        valor={estatisticas.reservados}
                        detalhe="Aguardando negociação"
                        icone={Clock}
                        corIcone="bg-amber-500/10 text-amber-600 dark:text-amber-400"
                    />
                    <Kpi
                        titulo="Vendidos"
                        valor={estatisticas.vendidos}
                        detalhe="Negócios fechados"
                        icone={HandCoins}
                        corIcone="bg-slate-500/10 text-slate-600 dark:text-slate-300"
                    />
                </section>

                <section className="grid gap-4 lg:grid-cols-3">
                    <div className="flex flex-col gap-4">
                        <div className="flex-1 rounded-2xl border bg-card p-5 shadow-sm">
                            <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <BadgeDollarSign className="size-4" />
                                Valor em estoque
                            </div>
                            <p className="mt-3 text-3xl font-bold tracking-tight">
                                {formatarPreco(estatisticas.valor_estoque)}
                            </p>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Soma dos veículos disponíveis e reservados
                            </p>
                        </div>
                        <div className="flex-1 rounded-2xl border bg-card p-5 shadow-sm">
                            <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <HandCoins className="size-4" />
                                Faturamento em vendas
                            </div>
                            <p className="mt-3 text-3xl font-bold tracking-tight">
                                {formatarPreco(estatisticas.valor_vendido)}
                            </p>
                            <p className="mt-1 text-xs text-muted-foreground">
                                Soma dos veículos vendidos
                            </p>
                        </div>
                    </div>

                    <div className="rounded-2xl border bg-card p-5 shadow-sm lg:col-span-2">
                        <h2 className="font-semibold">
                            Veículos por categoria
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            Quantidade de veículos cadastrados em cada categoria
                        </p>
                        {por_categoria.length > 0 ? (
                            <ul className="mt-5 space-y-3">
                                {por_categoria.map(({ categoria, total }) => (
                                    <li
                                        key={categoria}
                                        title={`${categoria}: ${total} veículo(s)`}
                                        className="group grid grid-cols-[88px_1fr_32px] items-center gap-3 text-sm"
                                    >
                                        <span className="truncate text-muted-foreground group-hover:text-foreground">
                                            {categoria}
                                        </span>
                                        <div className="h-3 rounded-r-sm bg-muted/60">
                                            <div
                                                className="h-full rounded-r-[4px] bg-primary transition-opacity group-hover:opacity-80"
                                                style={{
                                                    width: `${(total / maiorCategoria) * 100}%`,
                                                }}
                                            />
                                        </div>
                                        <span className="text-right font-semibold tabular-nums">
                                            {total}
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p className="mt-6 text-sm text-muted-foreground">
                                Nenhum veículo cadastrado ainda.
                            </p>
                        )}
                    </div>
                </section>

                <section className="rounded-2xl border bg-card shadow-sm">
                    <div className="flex items-center justify-between border-b p-5">
                        <div>
                            <h2 className="font-semibold">
                                Últimos cadastrados
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Veículos adicionados recentemente ao estoque
                            </p>
                        </div>
                        <Button variant="ghost" size="sm" asChild>
                            <Link href={index()}>
                                Ver todos
                                <ArrowRight />
                            </Link>
                        </Button>
                    </div>
                    <ul className="divide-y">
                        {recentes.map((veiculo) => (
                            <li key={veiculo.id}>
                                <Link
                                    href={show(veiculo.id)}
                                    className="flex items-center gap-4 p-4 transition-colors hover:bg-muted/50"
                                >
                                    <div className="h-12 w-20 shrink-0 overflow-hidden rounded-lg">
                                        <VeiculoFoto
                                            veiculo={veiculo}
                                            iconClassName="size-6"
                                        />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <p className="truncate font-semibold">
                                            {veiculo.marca} {veiculo.modelo}
                                        </p>
                                        <p className="truncate text-sm text-muted-foreground">
                                            {veiculo.ano_fabricacao}/
                                            {veiculo.ano_modelo} ·{' '}
                                            {formatarKm(veiculo.quilometragem)}{' '}
                                            · {veiculo.combustivel}
                                        </p>
                                    </div>
                                    <VeiculoStatusBadge
                                        status={veiculo.status}
                                        className="hidden sm:inline-flex"
                                    />
                                    <p className="w-32 text-right font-bold tabular-nums">
                                        {formatarPreco(veiculo.preco)}
                                    </p>
                                </Link>
                            </li>
                        ))}
                        {recentes.length === 0 && (
                            <li className="p-8 text-center text-sm text-muted-foreground">
                                Nenhum veículo cadastrado ainda.
                            </li>
                        )}
                    </ul>
                </section>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Painel',
            href: dashboard(),
        },
    ],
};
