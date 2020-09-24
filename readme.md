
## About Laravel

Instructions to run & compile the app on the local machine

- clone repo to local machine
- create a duplicate file of `.env.example` then rename it to `.env` file
- on your terminal run `php artisan generate:key`
- on the project directory, open terminal, excecute in your terminal the command `composer install` & `npm install && npm run dev`
- after installing composer & npm dependencies, setup your local MySQL server, then create a Database named `laravel`
- then, execute `php artisan migrate --seed` in your terminal
- access application through a browser using the root url assign on your local machine
