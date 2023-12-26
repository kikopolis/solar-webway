# Live app url: http://172.105.70.177/

It is deployed to a nanode instance on Akamai Linode.

# How to run the app locally

1. Clone the repo
2. Install dependencies, `composer install`, `npm install`, `npm run build`
3. Create a database and add the credentials to the .env file
4. Run migrations, `php artisan migrate`
5. Run the app, `php artisan serve`
6. Visit the app on your browser, should be `http://localhost:8000`

Alternatively, if you have docker installed, you can run the app with Sail as well.


To view added bookings, visit `http://localhost:8000/get-bookings`
It returns a json response of all bookings in the database.
