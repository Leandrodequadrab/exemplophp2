import { Head } from '@inertiajs/react';
import VeiculoController from '@/actions/App/Http/Controllers/VeiculoController';
import PageHeader from '@/components/page-header';
import VeiculoForm from '@/components/veiculo-form';
import { index, show } from '@/routes/veiculos';
import type { OpcoesVeiculo, Veiculo } from '@/types/veiculo';

type Props = {
    veiculo: Veiculo;
    opcoes: OpcoesVeiculo;
};

export default function VeiculosEdit({ veiculo, opcoes }: Props) {
    return (
        <>
            <Head title={`Editar ${veiculo.marca} ${veiculo.modelo}`} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <PageHeader
                    titulo={`Editar ${veiculo.marca} ${veiculo.modelo}`}
                    descricao="Atualize as informações do veículo"
                />

                <VeiculoForm
                    form={VeiculoController.update.form(veiculo.id)}
                    cancelar={show(veiculo.id)}
                    opcoes={opcoes}
                    veiculo={veiculo}
                    textoBotao="Salvar alterações"
                />
            </div>
        </>
    );
}

VeiculosEdit.layout = {
    breadcrumbs: [
        { title: 'Estoque de veículos', href: index() },
        { title: 'Editar', href: index() },
    ],
};
