# Prime Motors — Sistema de Concessionária

Trabalho 1 de **Infraestrutura Cloud AWS** (UNESC): um CRUD de veículos para uma
concessionária, pronto para rodar em um **EC2** com banco **PostgreSQL no RDS**.

## Funcionalidades

- **Vitrine pública** (`/`): página da loja com os veículos disponíveis em destaque.
- **Login / cadastro** de usuários da equipe.
- **Painel** (`/dashboard`): totais de estoque, reservados e vendidos, valor em estoque,
  faturamento, veículos por categoria e últimos cadastrados.
- **CRUD de veículos** (`/veiculos`):
    - listar com busca (marca, modelo, versão, placa), filtro por status e categoria, e paginação
    - cadastrar, ver detalhes, editar e excluir (com confirmação)
    - validação em português (placa antiga ou Mercosul, ano do modelo ≥ ano de fabricação etc.)
- Tema claro/escuro e layout responsivo (celular).

## Stack

| Camada    | Tecnologia                                                        |
| --------- | ----------------------------------------------------------------- |
| Back-end  | PHP 8.4 · Laravel 13 · Fortify (autenticação)                     |
| Front-end | React 19 · Inertia.js 3 · TypeScript · Tailwind CSS 4 · shadcn/ui |
| Banco     | PostgreSQL (local no desenvolvimento, AWS RDS em produção)        |
| Servidor  | Nginx + PHP-FPM no AWS EC2                                        |

## Rodando localmente

Requisitos: PHP 8.4 com as extensões `pdo_pgsql`, `pgsql`, `zip`, `intl` · Composer · Node 22+ · PostgreSQL.

```bash
# 1. Crie o banco no PostgreSQL local
#    (pgAdmin, ou: psql -U postgres -c "CREATE DATABASE concessionaria;")

# 2. No .env, preencha a senha do seu PostgreSQL:
#    DB_PASSWORD=sua_senha

# 3. Dependências, tabelas e dados de exemplo
composer install
npm install
php artisan migrate --seed

# 4. Subir (servidor PHP + Vite juntos)
composer run dev
```

Acesse **http://localhost:8000** e entre com **admin@primemotors.com** / **password**.

Testes: `php artisan test` · Lint/format: `npm run check` e `composer lint`.

## Estrutura principal

```
app/Http/Controllers/VeiculoController.php   CRUD de veículos
app/Http/Controllers/DashboardController.php estatísticas do painel
app/Http/Controllers/HomeController.php      vitrine pública
app/Http/Requests/VeiculoRequest.php         regras de validação
app/Models/Veiculo.php                       model + opções (categorias, câmbio...)
database/migrations/…_create_veiculos_table  tabela "veiculos"
database/seeders/VeiculoSeeder.php           estoque de exemplo
resources/js/pages/veiculos/                 telas do CRUD (index, create, edit, show)
resources/js/pages/dashboard.tsx             painel
resources/js/pages/welcome.tsx               vitrine
deploy/                                      guia e scripts para a AWS
```

## Deploy na AWS

O passo a passo completo (EC2, RDS, Nginx, backup) está em
**[deploy/DEPLOY-AWS.md](deploy/DEPLOY-AWS.md)**, com:

- `deploy/nginx.conf`: virtual host do Nginx
- `deploy/deploy.sh`: atualiza o servidor a partir do Git
- `deploy/backup-db.sh`: backup do banco com `pg_dump` (cron + S3 opcional)

A aplicação já vem preparada para produção na AWS: confia nos cabeçalhos de proxy
(Load Balancer/HTTPS), tem a rota de _health check_ `/up`, suporta `DB_SSLMODE=require`
para o RDS e não guarda arquivos no disco do servidor (as fotos dos veículos são URLs).
