import { Form } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';
import type { ReactNode } from 'react';
import VeiculoController from '@/actions/App/Http/Controllers/VeiculoController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import type { Veiculo } from '@/types/veiculo';

export default function ExcluirVeiculoDialog({
    veiculo,
    children,
}: {
    veiculo: Veiculo;
    children: ReactNode;
}) {
    return (
        <Dialog>
            <DialogTrigger asChild>{children}</DialogTrigger>
            <DialogContent className="sm:max-w-md">
                <div className="flex size-12 items-center justify-center rounded-full bg-red-500/10 text-red-600 dark:text-red-400">
                    <TriangleAlert className="size-6" />
                </div>
                <DialogTitle>Remover veículo do estoque?</DialogTitle>
                <DialogDescription>
                    O veículo{' '}
                    <strong className="text-foreground">
                        {veiculo.marca} {veiculo.modelo}
                        {veiculo.versao ? ` ${veiculo.versao}` : ''}
                    </strong>{' '}
                    será excluído permanentemente. Essa ação não pode ser
                    desfeita.
                </DialogDescription>

                <Form {...VeiculoController.destroy.form(veiculo.id)}>
                    {({ processing }) => (
                        <DialogFooter className="gap-2">
                            <DialogClose asChild>
                                <Button type="button" variant="secondary">
                                    Cancelar
                                </Button>
                            </DialogClose>
                            <Button
                                type="submit"
                                variant="destructive"
                                disabled={processing}
                                data-test="confirmar-exclusao"
                            >
                                {processing && <Spinner />}
                                Excluir veículo
                            </Button>
                        </DialogFooter>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
