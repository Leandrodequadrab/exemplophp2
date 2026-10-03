export type StatusVeiculo = 'disponivel' | 'reservado' | 'vendido';

export type Veiculo = {
    id: number;
    marca: string;
    modelo: string;
    versao: string | null;
    categoria: string;
    ano_fabricacao: number;
    ano_modelo: number;
    cor: string;
    combustivel: string;
    cambio: string;
    quilometragem: number;
    placa: string | null;
    preco: string;
    status: StatusVeiculo;
    foto_url: string | null;
    descricao: string | null;
    created_at: string;
    updated_at: string;
};

export type OpcoesVeiculo = {
    categorias: string[];
    combustiveis: string[];
    cambios: string[];
    status: Record<StatusVeiculo, string>;
};

export type Paginado<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: { url: string | null; label: string; active: boolean }[];
};
