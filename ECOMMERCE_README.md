# E-commerce Backend - Laravel 10

Sistema backend completo em Laravel 10 com Filament Admin Panel e API REST para e-commerce com suporte a pagamentos via Crypto (NowPayments) e Zelle.

## 📋 Requisitos

- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js e NPM (para assets)

## 🚀 Instalação

1. Clone o repositório
2. Instale as dependências:
```bash
composer install
npm install
```

3. Configure o arquivo `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure as variáveis de ambiente no `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

NOWPAYMENTS_API_KEY=sua_chave_api_nowpayments
NOWPAYMENTS_BASE_URL=https://api.nowpayments.io/v1

JWT_SECRET=sua_chave_jwt_secreta
```

5. Execute as migrations:
```bash
php artisan migrate
```

6. Execute os seeders:
```bash
php artisan db:seed
```

7. Publique a configuração do JWT (se necessário):
```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

8. Compile os assets:
```bash
npm run build
```

## 📦 Estrutura do Sistema

### Models Criados
- `UserClient` - Clientes do e-commerce
- `Category` - Categorias de produtos
- `Product` - Produtos
- `Order` - Pedidos
- `OrderItem` - Itens dos pedidos
- `Banner` - Banners promocionais
- `Setting` - Configurações gerais

### APIs Disponíveis

#### Autenticação (Públicas)
- `POST /api/v1/register` - Registro de cliente
- `POST /api/v1/login` - Login
- `GET /api/v1/profile` - Perfil do usuário (autenticado)
- `POST /api/v1/logout` - Logout (autenticado)

#### Produtos (Públicas)
- `GET /api/v1/products` - Listar produtos (filtros: category_id, status, search)
- `GET /api/v1/products/{slug}` - Detalhes do produto

#### Categorias (Pública)
- `GET /api/v1/categories` - Listar categorias

#### Banners (Pública)
- `GET /api/v1/banners` - Listar banners ativos

#### Configurações (Pública)
- `GET /api/v1/settings` - Listar configurações

#### Pedidos (Autenticadas)
- `POST /api/v1/orders` - Criar pedido
- `GET /api/v1/orders` - Listar pedidos do cliente
- `GET /api/v1/orders/{id}` - Detalhes do pedido

#### Checkout (Autenticada)
- `POST /api/v1/checkout` - Processar checkout com pagamento

### Painel Administrativo (Filament)

Acesse: `http://localhost/admin`

CRUDs disponíveis:
- UserClients
- Categories
- Products
- Orders (E-commerce)
- Banners
- Settings

Dashboard com estatísticas:
- Total de pedidos
- Faturamento total
- Produtos ativos
- Pedidos pendentes

## 💳 Pagamentos

### Crypto (NowPayments)
Integração real com a API da NowPayments. Configure `NOWPAYMENTS_API_KEY` no `.env`.

### Zelle
Pagamento manual. O cliente informa a referência e o admin confirma via painel Filament.

### Cartão
Placeholder para futura implementação.

## 🔐 Autenticação JWT

O sistema usa JWT para autenticação da API. Após o login, use o token no header:
```
Authorization: Bearer {token}
```

## 📝 Seeders

O seeder `EcommerceSeeder` cria:
- 3 categorias (Eletrônicos, Roupas, Livros)
- 6 produtos de exemplo
- 3 banners promocionais
- Configurações básicas

Execute: `php artisan db:seed --class=EcommerceSeeder`

## 🛠️ Desenvolvimento

Para desenvolvimento com hot-reload:
```bash
npm run dev
php artisan serve
```

## 📄 Licença

MIT License
