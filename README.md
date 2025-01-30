# AutoVendas

Plataforma de vendas de carros online através de anúncios de revendedoras. Permite que usuários busquem veículos, comparem preços e realizem negociações diretamente com as concessionárias cadastradas.

_**[Documentação Completa](https://docs.google.com/document/d/1UAPpEU-ygud93wjtDnIpoNadS8VgZBNRoQ8BsskMQak/edit?usp=sharing)**_

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

## Rotas Importantes

| Rota            | Método | Descrição                  |
| --------------- | ------ | -------------------------- |
| `/api/login`    | POST   | Autenticação de usuários   |
| `/api/register` | POST   | Cadastro de novos usuários |

## Roadmap

### Gestão de Usuários

- [x] Autenticação de usuários
- [ ] Permissões de usuários

### Gestão de Veículos

- [ ] CRUD Veículo

### Gestão de Revendedoras

- [ ] CRUD Revendedora
- [ ] Gerir os funcionários
- [ ] Gerir estoque de veículos

### Busca de Anúncios

- [ ] Adicionar filtros de busca
- [ ] Comparar preços de modelos
