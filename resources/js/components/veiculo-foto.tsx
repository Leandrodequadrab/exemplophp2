import { CarFront } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import type { Veiculo } from '@/types/veiculo';

const gradientes: Record<string, string> = {
    Hatch: 'from-sky-500 via-blue-600 to-indigo-700',
    Sedan: 'from-slate-600 via-slate-800 to-slate-950',
    SUV: 'from-emerald-500 via-teal-600 to-cyan-800',
    Picape: 'from-amber-500 via-orange-600 to-red-700',
    Esportivo: 'from-rose-500 via-red-600 to-red-900',
    Utilitário: 'from-zinc-500 via-zinc-700 to-zinc-900',
};

type Props = {
    veiculo: Pick<Veiculo, 'marca' | 'modelo' | 'categoria' | 'foto_url'>;
    className?: string;
    iconClassName?: string;
    mostrarMarca?: boolean;
};

export default function VeiculoFoto({
    veiculo,
    className,
    iconClassName,
    mostrarMarca = false,
}: Props) {
    const [falhou, setFalhou] = useState(false);

    if (veiculo.foto_url && !falhou) {
        return (
            <img
                src={veiculo.foto_url}
                alt={`${veiculo.marca} ${veiculo.modelo}`}
                loading="lazy"
                onError={() => setFalhou(true)}
                className={cn('size-full object-cover', className)}
            />
        );
    }

    return (
        <div
            className={cn(
                'relative flex size-full items-center justify-center overflow-hidden bg-gradient-to-br',
                gradientes[veiculo.categoria] ?? gradientes.Sedan,
                className,
            )}
        >
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.25),transparent_55%)]" />
            <div className="absolute -bottom-6 left-1/2 h-10 w-3/4 -translate-x-1/2 rounded-full bg-black/30 blur-xl" />
            {mostrarMarca && (
                <span className="absolute top-4 left-5 text-sm font-bold tracking-[0.3em] text-white/40 uppercase">
                    {veiculo.marca}
                </span>
            )}
            <CarFront
                className={cn(
                    'relative size-16 text-white/90 drop-shadow-lg',
                    iconClassName,
                )}
                strokeWidth={1.25}
            />
        </div>
    );
}
