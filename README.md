Aqui está o README atualizado em português e formatado corretamente em markdown:

```markdown
# API de Gerenciamento de Tarefas para Imóveis

Esta é uma API RESTful desenvolvida com Laravel 10 para gerenciar tarefas e comentários relacionados a edifícios.

## Instalação (Usando Docker)

1. Clone o repositório:
   ```bash
   git clone https://github.com/seuusuario/real-estate-task-manager.git
   ```

1. Navegue para o diretório do projeto:
   ```bash
   cd real-estate-task-manager
   ```

2. Copie o arquivo de exemplo `.env` e configure:
   ```bash
   cp .env.example .env
   ```

3. Atualize o arquivo `.env` com as configurações desejadas, como as credenciais do banco de dados, se necessário.

4. Construa e inicie os containers Docker:
   ```bash
   docker-compose up -d
   ```

5. Após os containers estarem em execução, acesse o container da aplicação:
   ```bash
   docker exec -it proprli-app bash
   ```

6. Dentro do container, instale as dependências:
   ```bash
   composer install
   ```

7. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

8. Execute as migrações e preencha o banco de dados com dados iniciais (seed):
   ```bash
   php artisan migrate --seed
   ```

Agora a aplicação deve estar rodando em `http://localhost:85`, conforme definido no arquivo Docker Compose.

## Executando Testes

Para executar os testes utilizando Pest (dentro do container):

1. Acesse o container da aplicação:
   ```bash
   docker exec -it proprli-app bash
   ```

2. Execute os testes:
   ```bash
   ./vendor/bin/pest
   ```

## Executando PHPStan para Análise Estática

Para rodar o PHPStan para análise estática de código (dentro do container):

1. Acesse o container da aplicação:
   ```bash
   docker exec -it proprli-app bash
   ```

2. Execute o PHPStan:
   ```bash
   ./vendor/bin/phpstan analyse --memory-limit=512M
   ```

## Executando as Migrações Manualmente

Para rodar as migrações manualmente dentro do container Docker:

1. Acesse o container da aplicação:
   ```bash
   docker exec -it proprli-app bash
   ```

2. Execute o comando de migração:
   ```bash
   php artisan migrate --seed
   ```

Este comando também irá preencher o banco de dados com dados iniciais.

```
