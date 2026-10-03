import { Link, usePage } from '@inertiajs/react';
import { CarFront, ShieldCheck } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    const { name } = usePage().props;

    return (
        <div className="grid min-h-svh lg:grid-cols-2">
            <div className="relative hidden flex-col justify-between overflow-hidden bg-slate-950 p-10 text-white lg:flex">
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(59,130,246,0.4),transparent_60%),radial-gradient(ellipse_at_bottom_left,rgba(99,102,241,0.3),transparent_55%)]" />
                <CarFront
                    className="absolute -right-20 -bottom-16 size-[28rem] text-white/5"
                    strokeWidth={0.75}
                />

                <Link
                    href={home()}
                    className="relative flex items-center gap-3"
                >
                    <div className="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg shadow-blue-900/50">
                        <AppLogoIcon className="size-6" />
                    </div>
                    <span className="text-lg font-bold">{name}</span>
                </Link>

                <div className="relative max-w-md space-y-4">
                    <h2 className="text-4xl leading-tight font-extrabold">
                        Gestão completa do seu estoque de veículos.
                    </h2>
                    <p className="text-slate-300">
                        Cadastre, acompanhe e venda com agilidade. Tudo em um só
                        lugar, acessível de qualquer dispositivo.
                    </p>
                </div>

                <div className="relative flex items-center gap-2 text-sm text-slate-400">
                    <ShieldCheck className="size-4 text-emerald-400" />
                    Acesso restrito à equipe da concessionária
                </div>
            </div>

            <div className="flex flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
                <div className="w-full max-w-sm">
                    <div className="flex flex-col gap-8">
                        <div className="flex flex-col items-center gap-4 lg:items-start">
                            <Link
                                href={home()}
                                className="flex items-center gap-2 font-medium lg:hidden"
                            >
                                <div className="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                                    <AppLogoIcon className="size-6" />
                                </div>
                                <span className="sr-only">{title}</span>
                            </Link>

                            <div className="space-y-2 text-center lg:text-left">
                                <h1 className="text-2xl font-bold tracking-tight">
                                    {title}
                                </h1>
                                <p className="text-sm text-muted-foreground">
                                    {description}
                                </p>
                            </div>
                        </div>
                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}
