# AutoVendas 1.0

Plataforma de vendas de carros online através de anúncios de revendedoras. Permite que usuários busquem veículos, comparem preços e realizem negociações diretamente com as concessionárias cadastradas.

_**[Documentação](https://docs.google.com/document/d/1Et5qpdvoW-axkDRrRnYireStbGSFyK1ab5VhhFKAY2E/edit?usp=sharing)**_

## Tecnologias Utilizadas

- Symfony 5.4
- PHP 7.4
- Nginx (stable)
- MySQL 8.4
- Docker

## Como Iniciar

### Pré-requisitos

- Docker _(obrigatório)_
- PHP _(opcional, caso deseje rodar localmente sem Docker)_
- Composer _(opcional, caso precise instalar dependências manualmente)_

### Instalação

1. Clone o repositório:

   ```bash
   git clone git@github.com:gustavo-cavalho/AutoVendas.git
   ```

2. Acesse o diretório do projeto:

   ```bash
   cd AutoVendas
   ```

3. Suba os contêineres Docker:

   ```bash
   docker compose up -d --build
   ```

4. Instale as dependências:

   ```bash
   make composer-install
   ```

5. Execute as migrations para preparar o banco de dados:

   ```bash
   make exec-migrations
   ```

## Estrutura de Pastas

```plaintext
AutoVendas/
├── src/            # Código-fonte principal do projeto
├── config/         # Arquivos de configuração
├── migrations/     # Migrations do banco de dados
├── public/         # Pasta pública do projeto (ponto de entrada)
├── docker/         # Configurações do ambiente Docker
├── .env            # Variáveis de ambiente
├── Makefile        # Para facilitar alguns comandos
```

## Como utilizar

### Geral

Para realizar qualquer ação relacionada à manipulação de dados de uma loja, como criar, atualizar ou excluir carros, anúncios ou a própria loja, é necessário estar logado e vinculado à loja.
Para **criar** uma loja, é preciso ter permissão de administrador. No entanto, **visualizar** os anúncios não requer login.

### Funcionamento

1. Criar usuário:
   `POST /api/register`
   payload:
   ```json
   {
     "email": "seu email",
     "password": "sua ssenha",
     "name": "seu nome, apenas letras"
   }
   ```
2. Logar na conta criada:
   `POST /api/login`
   payload:

   ```json
   {
     "email": "seu email",
     "password": "sua ssenha"
   }
   ```

   Utilize o token gerado.

3. Torne-se um admin(rota temporária):
   `POST /api/user/to-admin`
   Sem payload, mas é nescessário está com o token.

4. Crie uma loja:
   `POST /api/store`
   payload:

   ```json
   {
    "credencial": "12.345.678/0001-95",
    "phone": "(11) 98765-4321",
    "email": "veiculo@top.com",
    "name": "Veiculo Top",
    "cep": "15050-305",
    "street": "Rua Josina Teixeira de Carvalho",
    "number": "345",
    "neighborhood": "Vila Anchieta",
    "city": "São José do Rio Preto",
    "state": "SP",
    "complement": "Galpão grande"
   ```

5. Associa-se à loja:
   `POST /api/store/{id}/user`
   Sem payload, o id será o da loja que deseja se associar.

6. Registrar veículos:]
   `POST /api/store/{id}/vehicle`
   payload:

   ```json
   {
     "type": "carros",
     "brand": "ford",
     "model": "Ka 1.5 Sedan SE 12V Flex 4p Mec.",
     "year": "2020 Gasolina",
     "mileage": 65000,
     "plate": "ABC-1113"
   }
   ```

7. Fazer anúncio de um veículo existente:
   `POST /api/store/{storeId}/vehicle/{id}/ad`
   payload:
   ```json
   {
     "description": "Carro muito bom e confiavel",
     "price": 50000.0,
     "status": "ON_SALE"
   }
   ```

## Rotas Importantes

### Autenticação

- **Login**

- `POST /api/login`
- Endpoint para autenticação de usuários.

- **Registro de Usuário**
- `POST /api/register`
- Criação de uma nova conta de usuário.

### Anúncios

- **Criar Anúncio para um Veículo**

- `POST /api/store/{storeId}/vehicle/{vehicleId}/ad`
- Associa um anúncio a um veículo específico dentro de uma loja.

- **Atualizar Anúncio**

- `PUT /api/ad/{id}`
- Atualiza as informações de um anúncio existente.

- **Detalhes do Anúncio**

- `GET /api/ad/{id}`
- Obtém informações de um anúncio específico.

- **Listar Anúncios**
- `GET /api/ad`
- Retorna uma lista de anúncios disponíveis.

### Veículos

- **Registrar Veículo**

- `POST /api/store/{id}/vehicle`
- Adiciona um novo veículo a uma loja específica.

- **Atualizar Veículo**

- `PUT /api/store/{storeId}/vehicle/{id}`
- Atualiza as informações de um veículo específico dentro de uma loja.

- **Detalhes do Veículo**
- `GET /api/store/{storeId}/vehicle/{id}`
- Obtém informações detalhadas de um veículo específico.

### Lojas

- **Registrar Loja**

- `POST /api/store`
- Criação de uma nova loja.

- **Atualizar Loja**

- `PUT /api/store/{id}`
- Atualiza as informações de uma loja existente.

- **Listar Lojas**

- `GET /api/store`
- Retorna uma lista de lojas registradas.

- **Detalhes da Loja**
- `GET /api/store/{id}`
- Obtém informações detalhadas de uma loja específica.

## Roadmap

### Gestão de Usuários

- [x] Autenticação de usuários
- [x] Permissões de usuários

### Gestão de Veículos

- [x] CRUD Veículo

### Gestão de Lojas

- [x] CRUD lojas
- [ ] Gerir os funcionários
- [ ] Gerir estoque de veículos

### Busca de Anúncios

- [ ] Adicionar filtros de busca
- [ ] Comparar preços de modelos
