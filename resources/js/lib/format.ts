const moeda = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
    maximumFractionDigits: 0,
});

const numero = new Intl.NumberFormat('pt-BR');

export function formatarPreco(valor: string | number): string {
    return moeda.format(Number(valor));
}

export function formatarKm(km: number): string {
    return km === 0 ? '0 km' : `${numero.format(km)} km`;
}

export function formatarPlaca(placa: string | null): string {
    if (!placa) {
        return '—';
    }

    return /^[A-Z]{3}[0-9]{4}$/.test(placa)
        ? `${placa.slice(0, 3)}-${placa.slice(3)}`
        : placa;
}

export function formatarData(data: string): string {
    return new Date(data).toLocaleDateString('pt-BR');
}
