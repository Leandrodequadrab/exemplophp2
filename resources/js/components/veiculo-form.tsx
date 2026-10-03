import { Form, Link } from '@inertiajs/react';
import { BadgeDollarSign, CarFront, ImageIcon, Settings2 } from 'lucide-react';
import type { ReactNode } from 'react';
import { useState } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import VeiculoFoto from '@/components/veiculo-foto';
import type { RouteDefinition, RouteFormDefinition } from '@/wayfinder';
import type { OpcoesVeiculo, Veiculo } from '@/types/veiculo';

type Props = {
    form: RouteFormDefinition<'post'>;
    cancelar: RouteDefinition<'get'>;
    opcoes: OpcoesVeiculo;
    veiculo?: Veiculo;
    textoBotao: string;
};

function Secao({
    icone,
    titulo,
    descricao,
    children,
}: {
    icone: ReactNode;
    titulo: string;
    descricao: string;
    children: ReactNode;
}) {
    return (
        <Card className="gap-5">
            <CardHeader className="flex flex-row items-center gap-3">
                <div className="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    {icone}
                </div>
                <div className="space-y-1">
                    <CardTitle>{titulo}</CardTitle>
                    <CardDescription>{descricao}</CardDescription>
                </div>
            </CardHeader>
            <CardContent className="grid gap-5 sm:grid-cols-2">
                {children}
            </CardContent>
        </Card>
    );
}

function Campo({
    id,
    label,
    erro,
    className,
    children,
}: {
    id: string;
    label: string;
    erro?: string;
    className?: string;
    children: ReactNode;
}) {
    return (
        <div className={`grid content-start gap-2 ${className ?? ''}`}>
            <Label htmlFor={id}>{label}</Label>
            {children}
            <InputError message={erro} />
        </div>
    );
}

function CampoSelect({
    name,
    valores,
    valorPadrao,
    placeholder,
}: {
    name: string;
    valores: Record<string, string>;
    valorPadrao?: string;
    placeholder: string;
}) {
    return (
        <Select name={name} defaultValue={valorPadrao}>
            <SelectTrigger id={name} className="w-full">
                <SelectValue placeholder={placeholder} />
            </SelectTrigger>
            <SelectContent>
                {Object.entries(valores).map(([valor, label]) => (
                    <SelectItem key={valor} value={valor}>
                        {label}
                    </SelectItem>
                ))}
            </SelectContent>
        </Select>
    );
}

const paraMapa = (lista: string[]) =>
    Object.fromEntries(lista.map((item) => [item, item]));

export default function VeiculoForm({
    form,
    cancelar,
    opcoes,
    veiculo,
    textoBotao,
}: Props) {
    const [fotoUrl, setFotoUrl] = useState(veiculo?.foto_url ?? '');
    const [previa, setPrevia] = useState({
        marca: veiculo?.marca ?? '',
        modelo: veiculo?.modelo ?? '',
        categoria: veiculo?.categoria ?? 'Sedan',
    });
    const anoAtual = new Date().getFullYear();

    return (
        <Form
            {...form}
            options={{ preserveScroll: true }}
            className="grid gap-6 lg:grid-cols-[1fr_320px]"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-6">
                        <Secao
                            icone={<CarFront className="size-5" />}
                            titulo="Identificação"
                            descricao="Marca, modelo e categoria do veículo"
                        >
                            <Campo id="marca" label="Marca" erro={errors.marca}>
                                <Input
                                    id="marca"
                                    name="marca"
                                    defaultValue={veiculo?.marca}
                                    placeholder="Ex.: Toyota"
                                    onChange={(e) =>
                                        setPrevia({
                                            ...previa,
                                            marca: e.target.value,
                                        })
                                    }
                                    required
                                    autoFocus
                                />
                            </Campo>
                            <Campo
                                id="modelo"
                                label="Modelo"
                                erro={errors.modelo}
                            >
                                <Input
                                    id="modelo"
                                    name="modelo"
                                    defaultValue={veiculo?.modelo}
                                    placeholder="Ex.: Corolla"
                                    onChange={(e) =>
                                        setPrevia({
                                            ...previa,
                                            modelo: e.target.value,
                                        })
                                    }
                                    required
                                />
                            </Campo>
                            <Campo
                                id="versao"
                                label="Versão (opcional)"
                                erro={errors.versao}
                            >
                                <Input
                                    id="versao"
                                    name="versao"
                                    defaultValue={veiculo?.versao ?? ''}
                                    placeholder="Ex.: 2.0 XEi Dynamic Force"
                                />
                            </Campo>
                            <Campo
                                id="categoria"
                                label="Categoria"
                                erro={errors.categoria}
                            >
                                <Select
                                    name="categoria"
                                    defaultValue={veiculo?.categoria}
                                    onValueChange={(categoria) =>
                                        setPrevia({ ...previa, categoria })
                                    }
                                >
                                    <SelectTrigger
                                        id="categoria"
                                        className="w-full"
                                    >
                                        <SelectValue placeholder="Selecione" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {opcoes.categorias.map((categoria) => (
                                            <SelectItem
                                                key={categoria}
                                                value={categoria}
                                            >
                                                {categoria}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </Campo>
                        </Secao>

                        <Secao
                            icone={<Settings2 className="size-5" />}
                            titulo="Especificações"
                            descricao="Ano, motorização e estado de conservação"
                        >
                            <Campo
                                id="ano_fabricacao"
                                label="Ano de fabricação"
                                erro={errors.ano_fabricacao}
                            >
                                <Input
                                    id="ano_fabricacao"
                                    name="ano_fabricacao"
                                    type="number"
                                    min={1950}
                                    max={anoAtual + 1}
                                    defaultValue={
                                        veiculo?.ano_fabricacao ?? anoAtual
                                    }
                                    required
                                />
                            </Campo>
                            <Campo
                                id="ano_modelo"
                                label="Ano do modelo"
                                erro={errors.ano_modelo}
                            >
                                <Input
                                    id="ano_modelo"
                                    name="ano_modelo"
                                    type="number"
                                    min={1950}
                                    max={anoAtual + 1}
                                    defaultValue={
                                        veiculo?.ano_modelo ?? anoAtual
                                    }
                                    required
                                />
                            </Campo>
                            <Campo
                                id="combustivel"
                                label="Combustível"
                                erro={errors.combustivel}
                            >
                                <CampoSelect
                                    name="combustivel"
                                    valores={paraMapa(opcoes.combustiveis)}
                                    valorPadrao={veiculo?.combustivel}
                                    placeholder="Selecione"
                                />
                            </Campo>
                            <Campo
                                id="cambio"
                                label="Câmbio"
                                erro={errors.cambio}
                            >
                                <CampoSelect
                                    name="cambio"
                                    valores={paraMapa(opcoes.cambios)}
                                    valorPadrao={veiculo?.cambio}
                                    placeholder="Selecione"
                                />
                            </Campo>
                            <Campo id="cor" label="Cor" erro={errors.cor}>
                                <Input
                                    id="cor"
                                    name="cor"
                                    defaultValue={veiculo?.cor}
                                    placeholder="Ex.: Prata"
                                    required
                                />
                            </Campo>
                            <Campo
                                id="quilometragem"
                                label="Quilometragem"
                                erro={errors.quilometragem}
                            >
                                <div className="relative">
                                    <Input
                                        id="quilometragem"
                                        name="quilometragem"
                                        type="number"
                                        min={0}
                                        defaultValue={
                                            veiculo?.quilometragem ?? 0
                                        }
                                        className="pr-10"
                                    />
                                    <span className="pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-sm text-muted-foreground">
                                        km
                                    </span>
                                </div>
                            </Campo>
                        </Secao>

                        <Secao
                            icone={<BadgeDollarSign className="size-5" />}
                            titulo="Dados comerciais"
                            descricao="Preço de venda, placa e situação no estoque"
                        >
                            <Campo
                                id="preco"
                                label="Preço de venda"
                                erro={errors.preco}
                            >
                                <div className="relative">
                                    <span className="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-sm text-muted-foreground">
                                        R$
                                    </span>
                                    <Input
                                        id="preco"
                                        name="preco"
                                        type="number"
                                        min={0}
                                        step="0.01"
                                        defaultValue={veiculo?.preco}
                                        placeholder="0,00"
                                        className="pl-10"
                                        required
                                    />
                                </div>
                            </Campo>
                            <Campo
                                id="placa"
                                label="Placa (opcional)"
                                erro={errors.placa}
                            >
                                <Input
                                    id="placa"
                                    name="placa"
                                    defaultValue={veiculo?.placa ?? ''}
                                    placeholder="ABC1D23"
                                    maxLength={8}
                                    className="font-mono uppercase"
                                />
                            </Campo>
                            <Campo
                                id="status"
                                label="Status"
                                erro={errors.status}
                                className="sm:col-span-2"
                            >
                                <CampoSelect
                                    name="status"
                                    valores={opcoes.status}
                                    valorPadrao={
                                        veiculo?.status ?? 'disponivel'
                                    }
                                    placeholder="Selecione"
                                />
                            </Campo>
                        </Secao>

                        <Secao
                            icone={<ImageIcon className="size-5" />}
                            titulo="Foto e descrição"
                            descricao="Apresentação do veículo na vitrine"
                        >
                            <Campo
                                id="foto_url"
                                label="URL da foto (opcional)"
                                erro={errors.foto_url}
                                className="sm:col-span-2"
                            >
                                <Input
                                    id="foto_url"
                                    name="foto_url"
                                    type="url"
                                    value={fotoUrl}
                                    onChange={(e) => setFotoUrl(e.target.value)}
                                    placeholder="https://..."
                                />
                            </Campo>
                            <Campo
                                id="descricao"
                                label="Descrição (opcional)"
                                erro={errors.descricao}
                                className="sm:col-span-2"
                            >
                                <Textarea
                                    id="descricao"
                                    name="descricao"
                                    defaultValue={veiculo?.descricao ?? ''}
                                    placeholder="Destaques, opcionais, estado de conservação..."
                                    rows={4}
                                />
                            </Campo>
                        </Secao>
                    </div>

                    <aside className="lg:sticky lg:top-6 lg:self-start">
                        <Card className="gap-0 overflow-hidden py-0">
                            <div className="aspect-[4/3]">
                                <VeiculoFoto
                                    key={fotoUrl}
                                    veiculo={{
                                        ...previa,
                                        foto_url: fotoUrl || null,
                                    }}
                                />
                            </div>
                            <div className="space-y-1 p-5">
                                <p className="text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                    Pré-visualização
                                </p>
                                <p className="truncate text-lg font-semibold">
                                    {previa.marca || 'Marca'}{' '}
                                    {previa.modelo || 'Modelo'}
                                </p>
                            </div>
                            <div className="grid gap-2 border-t p-5">
                                <Button
                                    type="submit"
                                    size="lg"
                                    disabled={processing}
                                    data-test="salvar-veiculo"
                                >
                                    {processing && <Spinner />}
                                    {textoBotao}
                                </Button>
                                <Button variant="ghost" asChild>
                                    <Link href={cancelar}>Cancelar</Link>
                                </Button>
                            </div>
                        </Card>
                    </aside>
                </>
            )}
        </Form>
    );
}
