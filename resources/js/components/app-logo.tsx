import { usePage } from '@inertiajs/react';

import AppLogoIcon from '@/components/app-logo-icon';

export default function AppLogo() {
    const { name } = usePage().props;

    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md shadow-blue-900/40">
                <AppLogoIcon className="size-5" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="truncate leading-tight font-semibold">
                    {name}
                </span>
                <span className="truncate text-xs text-sidebar-foreground/60">
                    Gestão de estoque
                </span>
            </div>
        </>
    );
}
