import { Head } from '@inertiajs/react';
import VeiculoController from '@/actions/App/Http/Controllers/VeiculoController';
import PageHeader from '@/components/page-header';
import VeiculoForm from '@/components/veiculo-form';
import { create, index } from '@/routes/veiculos';
import type { OpcoesVeiculo } from '@/types/veiculo';

export default function VeiculosCreate({ opcoes }: { opcoes: OpcoesVeiculo }) {
    return (
        <>
            <Head title="Cadastrar veículo" />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    titulo="Cadastrar veículo"
                    descricao="Preencha os dados para incluir um novo veículo no estoque"
                />

                <VeiculoForm
                    form={VeiculoController.store.form()}
                    cancelar={index()}
                    opcoes={opcoes}
                    textoBotao="Cadastrar veículo"
                />
            </div>
        </>
    );
}

VeiculosCreate.layout = {
    breadcrumbs: [
        { title: 'Estoque de veículos', href: index() },
        { title: 'Cadastrar', href: create() },
    ],
};
