### Running Queue in your server

Run the following command to listen to and execute any incoming queued jobs
```
sudo nohup php artisan queue:work --daemon >> storage/logs/laravel.log &
```

Whenever you make changes to your code or deploy to your server, you would need to run
```
php artisan queue:restart
```

> Do go through [Laravel's Documentation on Queues](https://laravel.com/docs/5.3/queues) to understand more on how to do initial setup for your development environment. 

Here's a shortcut if you were to use the `database` as your queue driver. Make sure to run the following artisan command to migrate the required tables to handle your queued jobs.
```
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```