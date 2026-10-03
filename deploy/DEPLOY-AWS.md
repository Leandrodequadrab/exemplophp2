# Deploy na AWS (EC2 + RDS PostgreSQL)

Guia para publicar a aplicação **Prime Motors** (Laravel 13 + React/Inertia) na AWS,
atendendo aos requisitos do trabalho:

| Requisito do trabalho                                 | Onde está neste guia            |
| ----------------------------------------------------- | ------------------------------- |
| Criar um VPS (EC2) e configurar a stack               | Passos 2 e 3                    |
| Utilizar repositório de código                        | Passo 0 e Passo 4 (`git clone`) |
| Copiar o sistema para o servidor e servir a aplicação | Passos 4, 5 e 6                 |
| Banco de dados no AWS RDS                             | Passo 1                         |
| Configurar backup                                     | Passo 7                         |

> **Stack:** Ubuntu 24.04 · Nginx · PHP 8.4-FPM · Composer · Node.js 22 · PostgreSQL 16+ (RDS)

---

## 0. Repositório de código

O projeto já é um repositório Git. Crie um repositório no GitHub (ou GitLab) e envie:

```bash
git add .
git commit -m "Sistema de concessionária"
git branch -M main
git remote add origin https://github.com/SEU-USUARIO/concessionaria.git
git push -u origin main
```

O `.env` **não** vai para o repositório (está no `.gitignore`). Ele é criado à mão no servidor.

---

## 1. Banco de dados — AWS RDS (PostgreSQL)

1. **RDS → Create database**
    - Engine: **PostgreSQL** (16 ou superior)
    - Template: **Free tier**
    - DB instance identifier: `concessionaria-db`
    - Master username: `postgres` · defina uma senha forte (anote)
    - Instance: `db.t3.micro` / `db.t4g.micro`
    - Public access: **No** (só o EC2 acessa)
    - Em _Additional configuration_ → **Initial database name: `concessionaria`**
    - **Backup:** deixe _Enable automated backups_ marcado, retenção **7 dias** (ver Passo 7)
2. **Security Group do RDS:** regra de entrada **PostgreSQL (5432)** com origem = **Security Group do EC2**.
3. Copie o **Endpoint** (RDS → Databases → concessionaria-db → Connectivity). Ele vai no `DB_HOST`.

---

## 2. Servidor — EC2

1. **EC2 → Launch instance**
    - AMI: **Ubuntu Server 24.04 LTS**
    - Tipo: `t2.micro` / `t3.micro` (free tier)
    - Key pair: crie/baixe o `.pem`
    - Security Group: liberar **SSH (22)** (seu IP), **HTTP (80)** e **HTTPS (443)** (0.0.0.0/0)
2. (Recomendado) associe um **Elastic IP** para o IP não mudar ao reiniciar.
3. Conecte: `ssh -i chave.pem ubuntu@IP-DO-EC2`

---

## 3. Stack no EC2

```bash
sudo apt update && sudo apt upgrade -y

# PHP 8.4 + extensões usadas pelo Laravel/PostgreSQL
sudo add-apt-repository ppa:ondrej/php -y && sudo apt update
sudo apt install -y nginx git unzip postgresql-client \
  php8.4-fpm php8.4-cli php8.4-pgsql php8.4-mbstring php8.4-xml \
  php8.4-curl php8.4-zip php8.4-bcmath php8.4-intl

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 22 (necessário para gerar o build do React)
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
```

**Instância com 1 GB de RAM:** o `npm run build` pode ficar sem memória. Crie 2 GB de swap:

```bash
sudo fallocate -l 2G /swapfile && sudo chmod 600 /swapfile
sudo mkswap /swapfile && sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

---

## 4. Copiar o sistema para o servidor

```bash
sudo mkdir -p /var/www && sudo chown ubuntu:ubuntu /var/www
cd /var/www
git clone https://github.com/SEU-USUARIO/concessionaria.git
cd concessionaria

cp .env.example .env
nano .env
```

No `.env`, preencha:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP-OU-DOMINIO

DB_CONNECTION=pgsql
DB_HOST=concessionaria-db.xxxxxxxx.us-east-1.rds.amazonaws.com   # endpoint do RDS
DB_PORT=5432
DB_DATABASE=concessionaria
DB_USERNAME=postgres
DB_PASSWORD=senha-do-rds
DB_SSLMODE=require
```

Teste a conexão com o RDS antes de seguir:

```bash
psql "host=ENDPOINT-DO-RDS port=5432 dbname=concessionaria user=postgres sslmode=require" -c "select version();"
```

---

## 5. Instalar, gerar o build e criar as tabelas

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
npm ci
npm run build
php artisan migrate --seed --force     # cria as tabelas e o estoque de exemplo
php artisan optimize

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

O `--seed` cria o usuário **admin@primemotors.com / password**. **Troque a senha**
depois do primeiro login (menu do usuário → Configurações → Segurança).

---

## 6. Servir a aplicação com o Nginx

```bash
sudo cp deploy/nginx.conf /etc/nginx/sites-available/concessionaria
sudo ln -s /etc/nginx/sites-available/concessionaria /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

Acesse `http://IP-DO-EC2`. Para checar a saúde da aplicação: `http://IP-DO-EC2/up`
(rota pronta para _health check_ de Load Balancer).

**HTTPS (opcional, se tiver domínio):**
`sudo apt install -y certbot python3-certbot-nginx && sudo certbot --nginx -d seudominio.com`
e depois altere `APP_URL` para `https://...` e rode `php artisan optimize`.

**Atualizações futuras:** depois de dar `git push` com mudanças, no servidor rode
`bash deploy/deploy.sh` (puxa do Git, instala, gera o build, migra e recarrega).

---

## 7. Backup

São duas camadas complementares:

### 7.1 Backups automáticos do RDS (principal)

- RDS → `concessionaria-db` → **Modify** → _Backup retention period_: **7 dias** → _Backup window_ (ex.: 03:00 UTC).
- Isso permite **Point-in-Time Recovery** (restaurar o banco em qualquer minuto dos últimos 7 dias):
  _Actions → Restore to point in time_.
- **Snapshot manual** antes da apresentação: _Actions → Take snapshot_.
- (Opcional) **AWS Backup → Backup plans** com regra diária para o recurso RDS.

### 7.2 Dump lógico agendado (`deploy/backup-db.sh`)

Gera um `.sql.gz` do banco com `pg_dump`, mantém 7 dias e (opcionalmente) envia para o S3.

```bash
chmod +x deploy/backup-db.sh deploy/deploy.sh
sudo mkdir -p /var/backups/concessionaria && sudo chown ubuntu /var/backups/concessionaria
bash deploy/backup-db.sh          # teste manual

crontab -e
# todo dia às 03:00:
0 3 * * * /var/www/concessionaria/deploy/backup-db.sh >> /var/backups/concessionaria/backup.log 2>&1
```

Para enviar ao S3: crie um bucket, dê ao EC2 uma **IAM Role** com `s3:PutObject` nesse bucket,
instale o AWS CLI (`sudo snap install aws-cli --classic`) e adicione no crontab
`BACKUP_S3_BUCKET=nome-do-bucket` antes do comando.

**Restaurar um dump:** `gunzip -c arquivo.sql.gz | psql -h ENDPOINT -U postgres -d concessionaria`

---

## Problemas comuns

| Sintoma                          | Causa provável                                                                          |
| -------------------------------- | --------------------------------------------------------------------------------------- |
| `SQLSTATE[08006] ... timeout`    | Security Group do RDS não libera a porta 5432 para o SG do EC2                          |
| `could not find driver`          | falta `php8.4-pgsql`                                                                    |
| Página 500 em branco             | permissão em `storage/` ou `bootstrap/cache` (passo 5); veja `storage/logs/laravel.log` |
| CSS/JS não carregam              | esqueceu `npm run build`                                                                |
| `npm run build` morre ("Killed") | pouca RAM → crie o swap (passo 3)                                                       |
