import type { ReactNode } from 'react';

export default function PageHeader({
    titulo,
    descricao,
    children,
}: {
    titulo: string;
    descricao?: ReactNode;
    children?: ReactNode;
}) {
    return (
        <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div className="space-y-1">
                <h1 className="text-2xl font-bold tracking-tight sm:text-3xl">
                    {titulo}
                </h1>
                {descricao && (
                    <p className="text-sm text-muted-foreground">{descricao}</p>
                )}
            </div>
            {children && (
                <div className="flex flex-wrap items-center gap-2">
                    {children}
                </div>
            )}
        </div>
    );
}
