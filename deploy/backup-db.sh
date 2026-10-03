#!/usr/bin/env bash
# Backup lógico do banco PostgreSQL (RDS) com pg_dump.
# Complementa os backups automáticos/snapshots do próprio RDS.
#
# Lê as credenciais do .env do projeto. Opcionalmente envia para o S3
# se a variável BACKUP_S3_BUCKET estiver definida (requer AWS CLI e uma
# IAM Role na instância EC2 com permissão s3:PutObject no bucket).
#
# Uso manual:   bash deploy/backup-db.sh
# Agendado (todo dia às 03:00), via "crontab -e":
#   0 3 * * * /var/www/concessionaria/deploy/backup-db.sh >> /var/log/backup-concessionaria.log 2>&1
set -euo pipefail

PROJETO="$(cd "$(dirname "$0")/.." && pwd)"
DESTINO="${BACKUP_DIR:-/var/backups/concessionaria}"
RETENCAO_DIAS="${BACKUP_RETENCAO_DIAS:-7}"

ler_env() {
    grep -E "^$1=" "$PROJETO/.env" | tail -n1 | cut -d '=' -f2- | sed -e 's/^"//' -e 's/"$//'
}

DB_HOST="$(ler_env DB_HOST)"
DB_PORT="$(ler_env DB_PORT)"
DB_DATABASE="$(ler_env DB_DATABASE)"
DB_USERNAME="$(ler_env DB_USERNAME)"
export PGPASSWORD="$(ler_env DB_PASSWORD)"
export PGSSLMODE="$(ler_env DB_SSLMODE || echo require)"

mkdir -p "$DESTINO"
ARQUIVO="$DESTINO/${DB_DATABASE}_$(date +%Y-%m-%d_%H-%M-%S).sql.gz"

echo "[$(date)] Gerando backup de ${DB_DATABASE} em ${DB_HOST}..."
pg_dump --host="$DB_HOST" --port="${DB_PORT:-5432}" --username="$DB_USERNAME" \
    --dbname="$DB_DATABASE" --no-owner --no-privileges | gzip > "$ARQUIVO"
echo "[$(date)] Backup salvo em $ARQUIVO ($(du -h "$ARQUIVO" | cut -f1))"

if [[ -n "${BACKUP_S3_BUCKET:-}" ]]; then
    aws s3 cp "$ARQUIVO" "s3://${BACKUP_S3_BUCKET}/postgres/$(basename "$ARQUIVO")"
    echo "[$(date)] Enviado para s3://${BACKUP_S3_BUCKET}/postgres/"
fi

find "$DESTINO" -name "*.sql.gz" -mtime +"$RETENCAO_DIAS" -delete
echo "[$(date)] Backups com mais de ${RETENCAO_DIAS} dias removidos."

# Para restaurar:
#   gunzip -c ARQUIVO.sql.gz | psql -h HOST -U USUARIO -d concessionaria
