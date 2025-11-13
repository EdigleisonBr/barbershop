# PASSO 1 - Criar projeto Laravel 8
composer create-project laravel/laravel="8.*" barbershop_pro

# PASSO 2 - Entrar na pasta
cd barbershop_pro

# PASSO 3 - Criar banco de dados no phpMyAdmin com nome "barbershop_pro"

# PASSO 4 - Substituir os arquivos conforme os exemplos do ChatGPT

# PASSO 5 - Gerar chave
php artisan key:generate

# PASSO 6 - Rodar as migrations e o seeder
php artisan migrate --seed

# PASSO 7 - Subir servidor local
php artisan serve

# ABRIR NO NAVEGADOR:
http://localhost:8000