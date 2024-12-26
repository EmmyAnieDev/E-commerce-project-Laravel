-   Created Seeder to add an admin. ====> php artisan make:seeder AdminSeeder

-   Edited seeder file to create an admin  and also called the seeder in the DatabaseSeeder then ran the command ====>>  php artisan db:seed

-   When we want to fetch related data we use the Eager loading.

-   Eager loading is used to fetch related data along with the main model to avoid multiple queries.

-   When the "Add to Cart" button is clicked, the product information is loaded and saved into the user's session.

*   _THINGS TO STORE IN THE SESSION_    ====>>   Product's ID, Image, Name, Price, Color, QTY, 

-   To Add Notification when a user adds a product to Cart. run this commands below.

-   composer require php-flasher/flasher-notyf-laravel

-   php artisan flasher:install
