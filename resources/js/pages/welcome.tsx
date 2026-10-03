import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BadgePercent,
    Calendar,
    Fuel,
    Gauge,
    MapPin,
    Phone,
    Repeat,
    ShieldCheck,
    Wrench,
} from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import VeiculoFoto from '@/components/veiculo-foto';
import { formatarKm, formatarPreco } from '@/lib/format';
import { dashboard, login } from '@/routes';
import type { Veiculo } from '@/types/veiculo';

type Props = {
    destaques: Veiculo[];
    totalDisponiveis: number;
};

const diferenciais = [
    {
        icone: ShieldCheck,
        titulo: 'Garantia de procedência',
        texto: 'Todos os veículos passam por vistoria cautelar e têm laudo aprovado.',
    },
    {
        icone: BadgePercent,
        titulo: 'Financiamento facilitado',
        texto: 'Parcelas que cabem no seu bolso com as melhores taxas do mercado.',
    },
    {
        icone: Repeat,
        titulo: 'Seu usado na troca',
        texto: 'Avaliação justa e na hora para usar o seu carro como entrada.',
    },
    {
        icone: Wrench,
        titulo: 'Revisados e prontos',
        texto: 'Revisão completa em oficina própria antes da entrega.',
    },
];

export default function Welcome({ destaques, totalDisponiveis }: Props) {
    const { auth, name } = usePage().props;

    return (
        <>
            <Head title="Seminovos e 0 km" />

            <div className="min-h-screen bg-background text-foreground">
                <section className="relative overflow-hidden bg-slate-950 text-white">
                    <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(59,130,246,0.35),transparent_55%),radial-gradient(ellipse_at_bottom_left,rgba(99,102,241,0.25),transparent_50%)]" />
                    <div className="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:48px_48px]" />

                    <header className="relative mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                        <div className="flex items-center gap-3">
                            <div className="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-900/50">
                                <AppLogoIcon className="size-6" />
                            </div>
                            <span className="text-lg font-bold tracking-tight">
                                {name}
                            </span>
                        </div>
                        <nav className="flex items-center gap-2 text-sm">
                            <a
                                href="#estoque"
                                className="hidden rounded-lg px-4 py-2 text-white/70 transition hover:text-white sm:inline-block"
                            >
                                Estoque
                            </a>
                            <a
                                href="#contato"
                                className="hidden rounded-lg px-4 py-2 text-white/70 transition hover:text-white sm:inline-block"
                            >
                                Contato
                            </a>
                            <Link
                                href={auth.user ? dashboard() : login()}
                                className="rounded-lg bg-white px-4 py-2 font-semibold text-slate-900 transition hover:bg-blue-50"
                            >
                                {auth.user
                                    ? 'Acessar painel'
                                    : 'Área do vendedor'}
                            </Link>
                        </nav>
                    </header>

                    <div className="relative mx-auto grid max-w-7xl items-center gap-12 px-6 pt-12 pb-24 lg:grid-cols-2 lg:pt-20 lg:pb-32">
                        <div className="space-y-8">
                            <span className="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-sm text-blue-100 backdrop-blur">
                                <span className="size-2 animate-pulse rounded-full bg-emerald-400" />
                                {totalDisponiveis} veículos disponíveis agora
                            </span>
                            <h1 className="text-4xl leading-tight font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                                Encontre o carro{' '}
                                <span className="bg-gradient-to-r from-blue-400 to-indigo-300 bg-clip-text text-transparent">
                                    dos seus sonhos
                                </span>
                            </h1>
                            <p className="max-w-xl text-lg text-slate-300">
                                Seminovos selecionados e 0 km com procedência,
                                garantia e as melhores condições de pagamento da
                                região.
                            </p>
                            <div className="flex flex-wrap gap-3">
                                <a
                                    href="#estoque"
                                    className="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold shadow-lg shadow-blue-600/30 transition hover:bg-blue-500"
                                >
                                    Ver estoque
                                    <ArrowRight className="size-4" />
                                </a>
                                <a
                                    href="#contato"
                                    className="inline-flex items-center gap-2 rounded-xl border border-white/20 px-6 py-3 font-semibold transition hover:bg-white/10"
                                >
                                    Falar com consultor
                                </a>
                            </div>
                            <dl className="grid max-w-md grid-cols-3 gap-6 border-t border-white/10 pt-8">
                                <div>
                                    <dt className="text-xs text-slate-400">
                                        No mercado
                                    </dt>
                                    <dd className="text-2xl font-bold">
                                        15 anos
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-xs text-slate-400">
                                        Clientes
                                    </dt>
                                    <dd className="text-2xl font-bold">
                                        +8 mil
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-xs text-slate-400">
                                        Garantia
                                    </dt>
                                    <dd className="text-2xl font-bold">
                                        3 meses
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div className="relative hidden lg:block">
                            <div className="absolute inset-x-10 bottom-0 h-16 rounded-full bg-blue-500/40 blur-3xl" />
                            {destaques[0] ? (
                                <div className="relative overflow-hidden rounded-3xl border border-white/10 shadow-2xl">
                                    <div className="aspect-[4/3]">
                                        <VeiculoFoto
                                            veiculo={destaques[0]}
                                            iconClassName="size-40"
                                            mostrarMarca
                                        />
                                    </div>
                                    <div className="absolute inset-x-0 bottom-0 flex items-end justify-between bg-gradient-to-t from-black/80 to-transparent p-6">
                                        <div>
                                            <p className="text-sm text-white/70">
                                                Destaque da semana
                                            </p>
                                            <p className="text-2xl font-bold">
                                                {destaques[0].marca}{' '}
                                                {destaques[0].modelo}
                                            </p>
                                        </div>
                                        <p className="text-2xl font-extrabold">
                                            {formatarPreco(destaques[0].preco)}
                                        </p>
                                    </div>
                                </div>
                            ) : (
                                <div className="flex aspect-[4/3] items-center justify-center rounded-3xl border border-white/10 bg-white/5">
                                    <AppLogoIcon className="size-40 text-white/20" />
                                </div>
                            )}
                        </div>
                    </div>
                </section>

                <section className="relative z-10 mx-auto -mt-12 max-w-7xl px-6">
                    <div className="grid gap-4 rounded-2xl border bg-card p-6 shadow-xl sm:grid-cols-2 lg:grid-cols-4">
                        {diferenciais.map(({ icone: Icone, titulo, texto }) => (
                            <div key={titulo} className="flex gap-4">
                                <div className="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <Icone className="size-5" />
                                </div>
                                <div>
                                    <h3 className="font-semibold">{titulo}</h3>
                                    <p className="text-sm text-muted-foreground">
                                        {texto}
                                    </p>
                                </div>
                            </div>
                        ))}
                    </div>
                </section>

                <section
                    id="estoque"
                    className="mx-auto max-w-7xl scroll-mt-8 px-6 py-20"
                >
                    <div className="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p className="text-sm font-semibold tracking-wider text-primary uppercase">
                                Estoque
                            </p>
                            <h2 className="text-3xl font-bold tracking-tight">
                                Veículos em destaque
                            </h2>
                            <p className="mt-2 text-muted-foreground">
                                Confira as novidades que acabaram de chegar à
                                nossa loja.
                            </p>
                        </div>
                    </div>

                    {destaques.length > 0 ? (
                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {destaques.map((veiculo) => (
                                <article
                                    key={veiculo.id}
                                    className="group overflow-hidden rounded-2xl border bg-card shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10"
                                >
                                    <div className="relative aspect-[16/10] overflow-hidden">
                                        <VeiculoFoto
                                            veiculo={veiculo}
                                            className="transition-transform duration-500 group-hover:scale-105"
                                        />
                                        <span className="absolute top-3 left-3 rounded-full bg-black/50 px-2.5 py-0.5 text-xs font-medium text-white backdrop-blur-sm">
                                            {veiculo.categoria}
                                        </span>
                                    </div>
                                    <div className="space-y-4 p-5">
                                        <div>
                                            <p className="text-xs font-semibold tracking-wider text-primary uppercase">
                                                {veiculo.marca}
                                            </p>
                                            <h3 className="text-lg font-bold">
                                                {veiculo.modelo}
                                            </h3>
                                            <p className="line-clamp-1 text-sm text-muted-foreground">
                                                {veiculo.versao || ' '}
                                            </p>
                                        </div>
                                        <div className="flex flex-wrap gap-x-4 gap-y-1 text-sm text-muted-foreground">
                                            <span className="flex items-center gap-1.5">
                                                <Calendar className="size-4" />
                                                {veiculo.ano_fabricacao}/
                                                {veiculo.ano_modelo}
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                <Gauge className="size-4" />
                                                {formatarKm(
                                                    veiculo.quilometragem,
                                                )}
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                <Fuel className="size-4" />
                                                {veiculo.combustivel}
                                            </span>
                                        </div>
                                        <div className="flex items-center justify-between border-t pt-4">
                                            <p className="text-2xl font-extrabold tracking-tight">
                                                {formatarPreco(veiculo.preco)}
                                            </p>
                                            <a
                                                href="#contato"
                                                className="text-sm font-semibold text-primary hover:underline"
                                            >
                                                Tenho interesse
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    ) : (
                        <div className="rounded-2xl border border-dashed p-12 text-center text-muted-foreground">
                            Em breve novos veículos no nosso estoque.
                        </div>
                    )}
                </section>

                <footer
                    id="contato"
                    className="border-t bg-slate-950 text-slate-300"
                >
                    <div className="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-3">
                        <div className="space-y-3">
                            <div className="flex items-center gap-3 text-white">
                                <div className="flex size-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600">
                                    <AppLogoIcon className="size-5" />
                                </div>
                                <span className="font-bold">{name}</span>
                            </div>
                            <p className="text-sm text-slate-400">
                                Seminovos e 0 km com procedência e as melhores
                                condições.
                            </p>
                        </div>
                        <div className="space-y-3 text-sm">
                            <h4 className="font-semibold text-white">
                                Contato
                            </h4>
                            <p className="flex items-center gap-2">
                                <Phone className="size-4 text-blue-400" />
                                (48) 3000-0000
                            </p>
                            <p className="flex items-center gap-2">
                                <MapPin className="size-4 text-blue-400" />
                                Av. Centenário, 1000 - Criciúma/SC
                            </p>
                        </div>
                        <div className="space-y-3 text-sm">
                            <h4 className="font-semibold text-white">
                                Horário de atendimento
                            </h4>
                            <p>Segunda a sexta: 8h às 18h</p>
                            <p>Sábado: 8h às 12h</p>
                        </div>
                    </div>
                    <div className="border-t border-white/10 py-6 text-center text-xs text-slate-500">
                        © {new Date().getFullYear()} {name}. Projeto acadêmico —
                        Infraestrutura Cloud AWS.
                    </div>
                </footer>
            </div>
        </>
    );
}
