<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://hml.carefy.com.br/wp-content/uploads/2024/07/carefoiuuuii.svg" width="300"></a>

# Desafio Prático Carefy
</p>


Este repositório é um desafio prático proposto pela empresa Carefy. A aplicação consiste em um gerenciador de
internações em um hospital, com a possibilidade de importação de dados de censo hospitalar através de um arquivo .CSV.

O censo hospitalar é uma listagem de pacientes e suas respectivas internações organizada em um arquivo estruturado para
cadastro em massa das informações em um sistema.

Essa aplicação se encontra em produção, clique <a href="http://carefy.jpdevs.cloud/" target="_blank">aqui</a> para acessar o deploy.

Clique <a href="https://www.youtube.com/watch?v=wRGJcvehULs" target="_blank">aqui</a> para assistir a uma demonstração no YouTube.

## Sumário

1. [O Problema](#o-problema)
2. [Solução Proposta](#solução-proposta)
    - [Requisitos de negócio](#requisitos-de-negócio)
    - [Regras de validação](#regras-de-validação)
3. [🚀 Começando](#-começando)
    - [📋 Pré-requisitos](#-pré-requisitos)
    - [🔧 Instalação](#-instalação)
4. [Documentação da API](#documentação-da-api)
    - [Internments](#internments)
        - [Index (Exibição de dados cadastrados)](#index-exibição-de-dados-cadastrados)
        - [Show (Exibição de dado individual)](#show-exibição-de-dado-individual)
        - [Store (Rota responsável pela criação de uma nova internação)](#store-rota-responsável-pela-criação-de-uma-nova-internação)
        - [Update (Atualização de dado individual)](#update-atualização-de-dado-individual)
        - [Destroy (Exclusão de dado individual)](#destroy-exclusão-de-dado-individual)
    - [Drafts](#drafts)
        - [Index (Exibição de dados cadastrados)](#index-exibição-de-dados-cadastrados-1)
        - [Show (Exibição de dado individual)](#show-exibição-de-dado-individual-1)
        - [Store (Rota responsável pela criação de uma nova internação)](#store-rota-responsável-pela-criação-de-uma-nova-internação-1)
        - [Update (Atualização de dado individual)](#update-atualização-de-dado-individual-1)
        - [Destroy (Exclusão de dado individual)](#destroy-exclusão-de-dado-individual-1)
        - [Publish (Converter um rascunho válido em internações)](#publish-converter-um-rascunho-válido-em-internações)
        - [Publish All Valids (Converter todos os rascunhos válidos em internações)](#publish-all-valids-converter-todos-os-rascunhos-válidos-em-internações)
    - [Patients](#patients)
        - [Index (Exibição de dados cadastrados)](#index-exibição-de-dados-cadastrados-2)
        - [Show (Exibição de dado individual)](#show-exibição-de-dado-individual-2)
        - [Store (Rota responsável pela criação de um novo paciente)](#store-rota-responsável-pela-criação-de-um-novo-paciente)
        - [Update (Atualização de dado individual)](#update-atualização-de-dado-individual-2)
        - [Destroy (Exclusão de dado individual)](#destroy-exclusão-de-dado-individual-2)
    - [Census](#census)
        - [Upload (Envio de dados)](#upload-envio-de-dados)
5. [🛠️ Ferramentas utilizadas no desenvolvimento da aplicação](#️-ferramentas-utilizadas-no-desenvolvimento-da-aplicação)


## O Problema

A clínica CareHealth costumava armazenar os dados de clientes e internações em uma planilha do Excel, porém
recentemente eles contrataram o sistema CareSys para poder ter maior facilidade no gerenciamento de seus dados.

A clínica não abrirá mão dos dados cadastrados na planilha, então solicitou aos desenvolvedores do CareSys que
desenvolvessem uma migração de dados do censo hospitalar em CSV para o sistema contratado, com a possibilidade de
verificar e corrigir possíveis incongruências encontradas nos dados da planilha.

## Solução Proposta

Foi desenvolvida uma aplicação com foco em importação de censo hospitalar CSV, seguindo alguns requisitos técnicos
solicitados pela CareHealth.

#### Requisitos de negócio:

- **Possibilidade de enviar os dados do censo através do upload de arquivos.**
- **Possibilidade de verificar os dados inconsistentes presentes nos arquivos.**
- **Possibilidade de salvar as informações válidas em um banco de dados relacional.**
- **Possibilidade de listar todos os pacientes com suas respectivas internações cadastradas.**

### Regras de validação:

Deverão ser inválidos os registros com as seguintes características:

- **01:** Pacientes com o mesmo NOME e NASCIMENTO, porém com CÓDIGO divergente de um cadastrado previamente.
- **02:** Internações com o mesmo código da GUIA de internação.
- **03:** Internações com a data de ENTRADA inferior à data de NASCIMENTO do paciente.
- **04:** Internações com a data de SAÍDA inferior ou igual à data de ENTRADA.
- **05:** Internações, do mesmo paciente, cujo período de internação (data de ENTRADA até a data de SAÍDA) conflite com
  o período de uma internação cadastrada previamente.
- **06:** Para pacientes com internação sem alta, ou seja, em que ele ainda está internado, não deve ser possível
  registrar internações futuras (data de ENTRADA maior que a data de ENTRADA de uma internação sem alta cadastrada
  previamente).

## 🚀 Começando

Essas instruções permitirão que você obtenha uma cópia do projeto em operação na sua máquina local.

### 📋 Pré-requisitos

```
PHP v8.1+
Composer v2.3.7+
MySql
```

### 🔧 Instalação

Primeiramente, clone o repositório no diretório desejado

```
git clone https://github.com/JpDevs/carefy-challange
```

Logo após, instale as dependências do mesmo utilizando o composer.

```
composer install
```

Copie o arquivo `env.example` e renomeie-o para `env`.

```
cp .env.example .env
sudo nano .env
```

```dotenv
DB_CONNECTION=mysql
DB_HOST=seu_host
DB_PORT=3306
DB_DATABASE=nomedobanco
DB_USERNAME=root
DB_PASSWORD=suasenha
```

Após a configuração das variáveis de ambiente, gere uma key para sua aplicação e habilite o storage.

```
php artisan key:generate
php artisan storage:link
```

E então, rode as migrations para instalação da database.

```
php artisan migrate:fresh
```

Feito isso, sua aplicação estará pronta para ser executada através do artisan.

```
php artisan serve
```

---

## Documentação da API

Segue abaixo a lista de rotas da API desenvolvida na aplicação.

### Internments

Grupo para o gerenciamento de internações, contém rotas de cadastro,listagem,atualização e exclusão de internações no
sistema.

#### **Index** (Exibição de dados cadastrados):

Método: GET

URL: /api/internments

Parâmetros:

- page (int): Número da página
- perPage (int): Número de registros por página

#### **Show** (Exibição de dado individual):

Método: GET

URL: /api/internments/{id}

Parâmetros: ---

#### **Store** (Rota responsável pela criação de uma nova internação):

Método: POST

URL: /api/internments/

Parâmetros:

```json
{
    "patient_id": 1,
    "guide": "123456",
    "entry": "2022-01-01",
    "exit": "2022-01-01"
}
```

#### **Update** (Atualização de dado individual):

Método: PUT

URL: /api/internments/{id}

Parâmetros:

```json
{
    "patient_id": 1,
    "guide": "123456",
    "entry": "2022-01-01",
    "exit": "2022-01-01"
}
```

#### **Destroy** (Exclusão de dado individual):
Método: DELETE

URL: /api/internments/{id}

Parâmetros: ---


---
### Drafts

Grupo para o gerenciamento de internações rascunhos (recém importadas de um arquivo CSV), contém rotas de cadastro,listagem,atualização,exclusão e publicação de internações rascunho.

#### **Index** (Exibição de dados cadastrados):

Método: GET

URL: /api/drafts

Parâmetros:

- page (int): Número da página
- perPage (int): Número de registros por página
- onlyValids (boolean): Somente os rascunhos validos (sem dados incongruentes)

#### **Show** (Exibição de dado individual):

Método: GET

URL: /api/drafts/{id}

Parâmetros: ---

#### **Store** (Rota responsável pela criação de uma nova internação):

Método: POST

URL: /api/drafts/

Parâmetros:

```json
{
    "patient_id": 1,
    "guide": "123456",
    "entry": "2022-01-01",
    "exit": "2022-01-01"
}
```

#### **Update** (Atualização de dado individual):

Método: PUT

URL: /api/drafts/{id}

Parâmetros:

```json
{
    "patient_id": 1,
    "guide": "123456",
    "entry": "2022-01-01",
    "exit": "2022-01-01"
}
```

#### **Destroy** (Exclusão de dado individual):
Método: DELETE

URL: /api/drafts/{id}

Parâmetros: ---


#### **Publish** (Converter um rascunho válido em internações):

Método: POST

URL: /api/drafts/{id}/publish

Parâmetros: ---

#### **Publish All Valids** (Converter todos os rascunhos válidos em internações):

Método: POST

URL: /api/drafts/publish

Parâmetros: ---

---

### Patients

Contém rotas responsáveis pelo gerenciamento de pacientes cadastrados no sistema.

#### **Index** (Exibição de dados cadastrados):

Método: GET

URL: /api/patients

Parâmetros:

- page (int): Número da página
- perPage (int): Número de registros por página

#### **Show** (Exibição de dado individual):

Método: GET

URL: /api/patients/{id}

Parâmetros: ---

#### **Store** (Rota responsável pela criação de uma nova internação):

Método: POST

URL: /api/patients/

Parâmetros:

```json

{
    "code": "34324234",
    "name": "João Pedro B",
    "birth": "2024-10-12"
}

```

#### **Update** (Atualização de dado individual):

Método: PUT

URL: /api/patients/{id}

Parâmetros:

```json
{
    "code": "343242234",
    "name": "João",
    "birth": "2024-10-12"
}
```

#### **Destroy** (Exclusão de dado individual):
Método: DELETE

URL: /api/patients/{id}

Parâmetros: ---


---

### Census

Rota responsável pelo envio, validação e persistência dos dados de censo hospitalar no sistema.

#### **Upload** (Exibição de dados cadastrados):

Método: POST

URL: /api/patients

Multipart/form-data

**Parâmetros:**
- File (arquivo com formato .csv)

---







## 🛠️ Ferramentas utilizadas no desenvolvimento da aplicação:

Para o desenvolvimento dessa aplicação, foram utilizadas algumas libs, frameworks e ferramentas open-source. Eu não
poderia deixar de creditar as mesmas.

* [Laravel](https://laravel.com/) - O Framework PHP para artesãos WEB
* [Composer](https://getcomposer.org/) - Gerenciador de dependências para PHP
* [MySQL](https://dev.mysql.com/) - Banco de dados relacional
* [SbAdmin 2](https://startbootstrap.com/sb-admin-2) - A free bootstrap admin theme
* [Bootstrap](https://getbootstrap.com/) - Framework CSS
* [Jquery](https://jquery.com/) - Biblioteca JavaScript
* [SweetAlert2](https://sweetalert2.github.io/) - Biblioteca de alertas
* [Datatables](https://datatables.net/) - Tabela de dados

---

## 🎉 Considerações finais do desenvolvedor


Sem sombra de dúvidas, este foi um projeto realmente desafiador, e de longe o que eu mais gostei de desenvolver. As validações dos dados tornaram todo o processo mais interessante, e, com a separação de dados incongruentes, tive a ideia de criar um sistema de "rascunhos", que adorei implementar. Foi um projeto que me trouxe grandes aprendizados em algumas questões relacionadas à linguagem PHP, e, com toda certeza, me fez beber alguns litros de café, rsrs. Agradeço muito ao pessoal da Carefy pela oportunidade e espero que gostem do sistema.

Atenciosamente,
João Pedro B. Santos (JpDevs)

