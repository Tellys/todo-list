## Vue (frontend) + Laravel (api)

    
## Dados do sistema backend + frontend
Na parte backend estamos utilizando Laravel na versão acima citada e com os requisitos:
- [Laravel Sanctun](https://github.com/laravel/sanctum)

Na parte frontend estamos utilizando o VUE.JS na versão acima citada com os seguintes requisitos:
- [axios](https://github.com/axios)
- [bootstrap](https://github.com/bootstrap-vue/bootstrap-vue)
- [vue-router](https://github.com/vuejs/vue-router)
- [vue-sweetalert2](https://github.com/sweetalert2/sweetalert2)
- [vuex](https://github.com/vuejs/vuex)

## Instalando o backend Laravel
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

- Conferir a versão do php, o teste esta na Versão "^8.0".
- Rodar os comandos abaixo:
    ```bash
    cd backend
    composer install
    ```

- Configure o .env
    ```bash
    cp .env.example .env
    ```

- Key generate
    ```bash
    php artisan key:generate
    ```

- Dataase Create (criar o banco de dados)
    ```bash
    todo_list
    ```

- Rode as migrations e os seed's.
    ```bash
    php artisan migrate --seed
    php artisan storage:link
    ```

Opções para instalação
```bash
composer update --no-scripts
```
Crie o arquivo .env (pode copiar o exemplo e mudar as vars)

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve 
```

## Instalando o frontend VUE.JS
<p align="center"><a href="https://vuejs.org/" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/512px-Vue.js_Logo_2.svg.png" width="400" alt="Vue Logo"></a></p>

```bash
cd frontend
```

```bash
NPM i
```

Install Tailwindcss
```bash
npx tailwindcss init
```

```bash
//tailwind.config.js

module.exports = {
  content: ["./src/**/*.{html,js}"], // add this line
  theme: {
    extend: {},
  },
  plugins: [],
}
```

## Rodar os sistemas backend e frontend

Dentro da pasta backend /backend
```bash
php artisan serve
```

Dentro da pasta frontend /frontend
```bash
npm run serve
```
    
Se deu tudo certo até aqui, agora é com você, vai encontrar mais detalhes no projeto, faz rodar e divirta-se 😁.

## Acesse o sistema
```bash
http://localhost:8080
```

Caso quera testar o sistam de login
```bash
user = diretor@mail.com
senha = diretor
```
