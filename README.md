<p align="center">
  <img src="public/assets/images/logo.svg" alt="NetPOS Logo" width="180" height="180">
</p>

<h1 align="center">NetPOS</h1>

<p align="center">
  Sistema de Ponto de Venda (POS) e Gestão de Inventário Multi-Tenant
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/Framework-Slenix-orange.svg" alt="Framework Slenix">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

---

## Sobre o Projecto

O **NetPOS** é uma aplicação web robusta desenvolvida para o comércio e gestão de estabelecimentos. Construído sobre o ecossistema do framework **Slenix** e utilizando o motor de templates **Luna**, o sistema oferece controlo completo de inventário, base de dados de clientes, gestão de funcionários e operações de ponto de venda em tempo real com arquitectura multi-tenant.

---

## Funcionalidades

* Gestão de Produtos com validação AJAX e paginação dinâmica.
* Controlo de Clientes e registo de NIF.
* Administração de Funcionários, utilizadores e níveis de acesso.
* Arquitectura Multi-Tenant isolada por organização.
* Autenticação e Middlewares de segurança integrados.
* Interface responsiva optimizada para terminais POS.

---

## Estrutura do Projecto

```text
netpos/
├── app/
│   ├── Controllers/    # Controladores da aplicação (Admin e Auth)
│   ├── Http/Requests/  # Validação de formulários e Form Requests
│   ├── Middlewares/    # Camadas de segurança e controlo de acesso
│   └── Models/         # Modelos Eloquent / ORM Slenix
├── database/
│   ├── migrations/     # Estrutura e migrações da base de dados
│   └── seeds/          # Seeds para dados iniciais
├── public/             # Ponto de entrada público e assets estáticos
├── routes/             # Definição de rotas web e admin
├── src/                # Núcleo do framework e utilitários (Slenix)
├── storage/            # Logs e cache da aplicação
└── views/              # Templates do motor Luna (admin, auth, partials)

```

---

## Requisitos de Sistema

* **PHP:** Versão 8.2 ou superior
* **Composer:** Gestor de dependências PHP
* **Base de Dados:** MySQL / MariaDB

---

## Instalação e Configuração

1. Clone o repositório:
```bash
git clone [https://github.com/claudiovictors/netpos.git](https://github.com/claudiovictors/netpos.git)
cd netpos

```


2. Instale as dependências via Composer:
```bash
composer install

```


3. Configure o ambiente e execute as migrações e seeds da base de dados.
4. Inicie o servidor de desenvolvimento:
```bash
php -S 127.0.0.1:3000 -t public

```

> OBS: Nada de usar php <= 8.0 kkk


---

## Licença

Este projecto está licenciado sob os termos da licença [MIT](https://www.google.com/search?q=LICENSE).