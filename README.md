<h1 align="left">Code-challenge O Povo Online</h1>

<p align="left">Este projeto é uma REST API, que usa Laravel e MySQL, para realizar operações com jornalistas e noticias, onde o jornalista é um usúario autenticado(e é possivel acessar as rotas e a pi somente por autenticação), que realiza os processos de criar, editar, excluir listar noticias e tipos de noticias.</p>

<!--ts-->

-   [Sobre](#Sobre)
-   [Instalação](#instalacao)
-   [Como usar](#como-usar)
    -   [Jornalistas](#jornalistas)
    -   [Noticias](#noticias)
    -   [Tipos de noticias](#tiposnoticias)

## Sobre

Este projeto é um code challenge para a vaga de emprego de Desenvolvedor PHP Pleno na empresa O Povo Online.<br>
Optei por usar o Laravel, pela sua forte documentação, o que facilita na sua resolução de problemas, além de gostar bastante da sua organização, do padrão de arquitetura MVC, das suas várias ferramentas que facilitam bastante a escrita do código, e também na descrição da vaga pede conhecimento em alguns frameworks, e Laravel é um desses.<br>
Como banco de dados usei o MySQL, pois era uma das sugestões do code challenge. O que tornou o desenvolvimento mais fácil, pois já possuo conhecimento em MySQL<br>
Optei por usar JSON para receber e enviar dados. Para a autenticação foi usado JWT(JSON Web Token) como pedido no projeto, porém provalvelmente será substituido pelo Sancutm (autenticador nativo do laravel) posteriormente<br>

## Instalação

### Requisitos

-Docker

### Como instalar

Baixe este projeto e o descompacte.<br>

Copiamos o .env.example como nosso .env principal<br>
**cp .env.example .env**

Navegue até o diretório do projeto e use<br>
**docker-compose build app**

Use este comando para executar os containers:<br>
**docker-compose up -d**

Agora, vamos executar o composer install para instalar as dependências do aplicativo:<br>
**docker-compose exec app composer install**

Rodaremos as nossas migrations para criar as tabelas do nosso banco de dados<br>
**docker-compose exec app php artisan migrate**

(Opcional) Foi inserido um pequeno seeder com apenas um úsuario para testar a rota de login<br>
**docker-compose exec app php artisan db:seed**

Primeiro precisamos criar a chave da nossa aplicação usando:<br>
**docker-compose exec app php artisan key:generate**

Neste passo iremos criar a chave do nosso JWT usando:<br>
**docker-compose exec app php artisan jwt:secret**

(Opcional) Caso tenha optado por usar o db:seeder para fazer o teste unitario usaremos:<br>
**docker-compose exec app php artisan test**

# Como Usar

## Jornalistas

### Registrar

Para registrar jornalistas acessamos a rota: /api/register usando o método POST, e enviamos as informações no seguinte formato:<br>
Header:
```javascript

    {
        "Accept":"application/json"
    }

 ```
<br>

Body
```javascript

    {
        "nome" : "Carlos Cesar",
        "sobrenome" : "dos Santos",
        "email": "cesarsantos@gmail.com",
        "password": "senhateste1990"
    }
```
<br>

A aplicação retornará o status code 200, e um token (falaremos sobre ele em seguida) para acessar funções que apenas usuarios autenticados podem acessar, caso os dados forem inseridos corretament. 
Importante: o e-mail é um dado único, isso quer dizer que apenas um úsuario tem um e-mail registrado, não havendo assim dois ou mais úsuarios com o mesmo e-mail.


### Token

O token é gerado pelo autenticador JWT, e tem expiração em 5 minutos, portanto a cada 5 minutos é necessário logar em seu usuario para obter um novo token.
O formato do Token é parecido com este: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY2MjMyNDk4NCwiZXhwIjoxNjYyMzI4NTg0LCJuYmYiOjE2NjIzMjQ5ODQsImp0aSI6ImtmeFo4QnV1SHRsWDdWUUciLCJzdWIiOjIsInBydiI6IjdiMzcxY2U0NDVkMWMwNjdiOWM2ZWNiZDYxM2M1MTkwMWFlZjA1M2IifQ.TO_0wgTMWt79mS885Xm9iE5tpGk5Jk8-EOuUH-29T9o <br>

Declararemos ele no Header da requisição para ter acesso aos próximos passos (exceto login, afinal não é possível esta rota ser autenticada, qualquer um pode precisar logar-se, o mesmo vale para a rota de registro) da seguinte forma:

Header:
```javascript

    {
        "Accept":"application/json",
        "Authorization" : "Beaver eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY2MjMyNDk4NCwiZXhwIjoxNjYyMzI4NTg0LCJuYmYiOjE2NjIzMjQ5ODQsImp0aSI6ImtmeFo4QnV1SHRsWDdWUUciLCJzdWIiOjIsInBydiI6IjdiMzcxY2U0NDVkMWMwNjdiOWM2ZWNiZDYxM2M1MTkwMWFlZjA1M2IifQ.TO_0wgTMWt79mS885Xm9iE5tpGk5Jk8-EOuUH-29T9o"
    }

```
Lembre-se de fazer isso para todas as próximas rotas(exceto login)<br>

### Login
Para logarmos com nosso usuario basta acessar a rota /api/login usando o método POST com os dados do nosso usúario no corpo da requisição da seguinte formato:

```javascript

    {
        "email": "cesarsantos@gmail.com",
        "password": "senhateste1990"
    }

```

### Informações sobre o úsuario

Acessar a rota /api/me usando o método GET retorna os dados do usuario logado(o hash da senha estará omitido).<br>

## Noticias

### Criar 

Para criar noticias basta acessar a rota /api/news/create usando o método POST e preencher o corpo da requisição da seguinte forma: 

```javascript
{
    
    "id_tipo": 2,
    "titulo": "teste para testes, um teste",
    "descricao": "teste teste teste teste teste teste teste teste",
    "corpo_da_noticia": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur non leo arcu. Aenean in suscipit libero, sed luctus purus. Nunc gravida eu dui sed aliquam. Nulla posuere molestie lorem vel faucibus. Duis vel fermentum enim. Aliquam dapibus, ex sit amet interdum porta, nisl velit bibendum diam, quis finibus massa ex vitae odio. Integer mi neque, sollicitudin non semper a, rhoncus ut sapien. Aliquam vehicula convallis mi at egestas."
    
}

```
Importante: Para criar noticias, previamente precisamos criar tipos de noticias (trataremos isso no último tópico).
Observação: Um jornalista não conseguirará adicionar, editar, listar ou excluir noticias criadas por outros jornalistas, ou mesmo usar (adicionando ou editando) tipos de noticias criados por outros jornalistas.

### Listar

Para listar as noticias feitas pelo úsuario logado basta acessar a rota /api/news/me usando o método GET, para listar por time usamos /api/news/type/{type_id} (onde {type_id} é o id do tipo de noticia) também pelo método GET.


### Deletar 
Para deletar usamos a rota /api/news/delete/{news_id} (onde {type_id} é o id da noticia) usando o método POST, e prontamente receberemos o response informando que deletamos com sucesso.

### Editar
Para editar usamos a rota /api/news/update/{news_id} (onde {type_id} é o id da noticia) usando o método POST, preenchendo o corpo da requisição com os campos que serão editados e suas respectivas edições:

```javascript
{
    
    "id_tipo": 1,
    "titulo": "teste diferente, aqui editamos",
    "descricao": "agora o teste aparece bem menos",
    "corpo_da_noticia": "aqui a noticia agora aparece bem menos"
    
}
```

## Tipos de Noticias

### Criar
Para criar um tipo de noticia usamos a rota /api/type/create usando o método POST e preenchendo o corpo da requisição da seguinte forma:

```javascript
{
    
   "nome":"Politica"
    
}
```

### Editar
Para editar segue a mesma linha de raciocinio: rota /api/type/update/{type_id}(onde {type_id} é o id do tipo de noticia) usando o método POST e segue o corpo da requisição:
```javascript
{
    "nome":"Cultura"
}

```

### Deletar
Para deletar usamos a rota /api/type/delete/{type_id} (onde {type_id} é o id da noticia) usando o método POST, e prontamente receberemos o response informando que deletamos com sucesso.

### Listar
Por fim, para listar os tipos de notícias usamos a rota /api/type/me usando o método GET.

