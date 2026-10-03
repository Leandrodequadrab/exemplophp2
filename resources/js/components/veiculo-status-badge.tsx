import { cn } from '@/lib/utils';
import type { StatusVeiculo } from '@/types/veiculo';

const estilos: Record<StatusVeiculo, { label: string; classe: string }> = {
    disponivel: {
        label: 'Disponível',
        classe: 'bg-emerald-500/15 text-emerald-700 ring-emerald-500/30 dark:text-emerald-300',
    },
    reservado: {
        label: 'Reservado',
        classe: 'bg-amber-500/15 text-amber-700 ring-amber-500/30 dark:text-amber-300',
    },
    vendido: {
        label: 'Vendido',
        classe: 'bg-slate-500/15 text-slate-700 ring-slate-500/30 dark:text-slate-300',
    },
};

export default function VeiculoStatusBadge({
    status,
    className,
}: {
    status: StatusVeiculo;
    className?: string;
}) {
    const estilo = estilos[status];

    return (
        <span
            className={cn(
                'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 backdrop-blur-sm ring-inset',
                estilo.classe,
                className,
            )}
        >
            <span className="size-1.5 rounded-full bg-current" />
            {estilo.label}
        </span>
    );
}
