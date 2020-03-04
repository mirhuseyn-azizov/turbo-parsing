#Turbo.az parsing sayti

**iki comandadan ibarət bir tooldu** 

Modelleri və markalari update elyir , (olmyanları yaradır)

    `php artisan turbo:categories`
   



Yeni maşınları  bazaya yığir

    `php artisan turbo:cars`
   
   `--skip  - yuklenenleri skip etmek ucun`  
   `--data=02.02.2020 - yukleme tarixi set etmek ucun`  

Taska her 30 deqiqədən bir  ikinci kamandanı run elemek lazımdı



laravel task:
php artisan  schedule:run




commands

`composer install`

`copy .env.example .env`

`docker-compose up build`

`docker-compose exec app php artisan migrate`

`docker-compose exec app php artisan turbo:categories`

`docker-compose exec app php artisan turbo:cars `

