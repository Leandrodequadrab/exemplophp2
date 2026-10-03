import { Head, Link, router } from '@inertiajs/react';
import {
    CarFront,
    ChevronLeft,
    ChevronRight,
    Plus,
    Search,
    X,
} from 'lucide-react';
import { useEffect, useState } from 'react';
import PageHeader from '@/components/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import VeiculoCard from '@/components/veiculo-card';
import { cn } from '@/lib/utils';
import { create, index } from '@/routes/veiculos';
import type { OpcoesVeiculo, Paginado, Veiculo } from '@/types/veiculo';

type Filtros = {
    busca?: string;
    status?: string;
    categoria?: string;
};

type Props = {
    veiculos: Paginado<Veiculo>;
    filtros: Filtros;
    opcoes: OpcoesVeiculo;
};

function filtrar(filtros: Filtros) {
    const params = Object.fromEntries(
        Object.entries(filtros).filter(([, valor]) => valor),
    );

    router.get(index().url, params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

export default function VeiculosIndex({ veiculos, filtros, opcoes }: Props) {
    const [busca, setBusca] = useState(filtros.busca ?? '');

    useEffect(() => {
        if (busca === (filtros.busca ?? '')) {
            return;
        }

        const timer = setTimeout(() => filtrar({ ...filtros, busca }), 350);

        return () => clearTimeout(timer);
    }, [busca, filtros]);

    const temFiltro = Boolean(
        filtros.busca || filtros.status || filtros.categoria,
    );

    return (
        <>
            <Head title="Estoque de veículos" />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    titulo="Estoque de veículos"
                    descricao={`${veiculos.total} ${veiculos.total === 1 ? 'veículo encontrado' : 'veículos encontrados'}`}
                >
                    <Button asChild size="lg">
                        <Link href={create()}>
                            <Plus />
                            Cadastrar veículo
                        </Link>
                    </Button>
                </PageHeader>

                <div className="flex flex-col gap-4 rounded-2xl border bg-card p-4 shadow-sm">
                    <div className="flex flex-col gap-3 md:flex-row">
                        <div className="relative flex-1">
                            <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                value={busca}
                                onChange={(e) => setBusca(e.target.value)}
                                placeholder="Buscar por marca, modelo, versão ou placa..."
                                className="h-10 pl-9"
                            />
                        </div>
                        <Select
                            value={filtros.status ?? 'todos'}
                            onValueChange={(status) =>
                                filtrar({
                                    ...filtros,
                                    busca,
                                    status:
                                        status === 'todos' ? undefined : status,
                                })
                            }
                        >
                            <SelectTrigger className="h-10! w-full md:w-48">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="todos">
                                    Todos os status
                                </SelectItem>
                                {Object.entries(opcoes.status).map(
                                    ([valor, label]) => (
                                        <SelectItem key={valor} value={valor}>
                                            {label}
                                        </SelectItem>
                                    ),
                                )}
                            </SelectContent>
                        </Select>
                        {temFiltro && (
                            <Button
                                variant="ghost"
                                className="h-10"
                                onClick={() => {
                                    setBusca('');
                                    filtrar({});
                                }}
                            >
                                <X />
                                Limpar filtros
                            </Button>
                        )}
                    </div>

                    <div className="flex flex-wrap gap-2">
                        {['', ...opcoes.categorias].map((categoria) => {
                            const ativo =
                                (filtros.categoria ?? '') === categoria;

                            return (
                                <button
                                    key={categoria || 'todas'}
                                    type="button"
                                    onClick={() =>
                                        filtrar({
                                            ...filtros,
                                            busca,
                                            categoria: categoria || undefined,
                                        })
                                    }
                                    className={cn(
                                        'rounded-full border px-4 py-1.5 text-sm font-medium transition-colors',
                                        ativo
                                            ? 'border-primary bg-primary text-primary-foreground shadow-sm shadow-primary/30'
                                            : 'bg-background text-muted-foreground hover:border-primary/50 hover:text-foreground',
                                    )}
                                >
                                    {categoria || 'Todas'}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {veiculos.data.length > 0 ? (
                    <div className="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        {veiculos.data.map((veiculo) => (
                            <VeiculoCard key={veiculo.id} veiculo={veiculo} />
                        ))}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center rounded-2xl border border-dashed bg-card px-6 py-20 text-center">
                        <div className="mb-4 flex size-16 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <CarFront className="size-8" />
                        </div>
                        <h2 className="text-lg font-semibold">
                            Nenhum veículo encontrado
                        </h2>
                        <p className="mt-1 max-w-sm text-sm text-muted-foreground">
                            {temFiltro
                                ? 'Tente ajustar os filtros ou o termo de busca.'
                                : 'Comece cadastrando o primeiro veículo do estoque.'}
                        </p>
                        {!temFiltro && (
                            <Button asChild className="mt-6">
                                <Link href={create()}>
                                    <Plus />
                                    Cadastrar veículo
                                </Link>
                            </Button>
                        )}
                    </div>
                )}

                {veiculos.last_page > 1 && (
                    <nav className="flex flex-col items-center justify-between gap-3 sm:flex-row">
                        <p className="text-sm text-muted-foreground">
                            Exibindo {veiculos.from}–{veiculos.to} de{' '}
                            {veiculos.total}
                        </p>
                        <div className="flex items-center gap-1">
                            {veiculos.links.map((link, i) => {
                                const anterior = i === 0;
                                const proximo = i === veiculos.links.length - 1;

                                return (
                                    <Button
                                        key={i}
                                        variant={
                                            link.active ? 'default' : 'outline'
                                        }
                                        size="icon"
                                        disabled={!link.url}
                                        asChild={Boolean(link.url)}
                                    >
                                        {link.url ? (
                                            <Link
                                                href={link.url}
                                                preserveScroll
                                                preserveState
                                            >
                                                {anterior ? (
                                                    <ChevronLeft />
                                                ) : proximo ? (
                                                    <ChevronRight />
                                                ) : (
                                                    link.label
                                                )}
                                            </Link>
                                        ) : anterior ? (
                                            <ChevronLeft />
                                        ) : proximo ? (
                                            <ChevronRight />
                                        ) : (
                                            <span>{link.label}</span>
                                        )}
                                    </Button>
                                );
                            })}
                        </div>
                    </nav>
                )}
            </div>
        </>
    );
}

VeiculosIndex.layout = {
    breadcrumbs: [
        {
            title: 'Estoque de veículos',
            href: index(),
        },
    ],
};
