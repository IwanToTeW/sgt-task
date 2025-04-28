
## Project setup

- Clone the repository
- Run the following command and nothing else
- NOTE: .env is committed for quicker setup & testing. I know it should be ignored in a real-world app.
```
php setup.php
```

- navigate to http://localhost:81/login

## Testing

- You can login with the following users
```
teodor@sgtautotransport.com
ivan@sgtautotransport.com
Password for both is - password
```

## Decisions

- I decided to use stable versions of PHP and Laravel 8.3 and 12 respectively.
- BitFinex was my choice of API as they have very generous limits and I can get the data I need for testing purposes.
- Pest was my choice of testing framework as it is easy to use and has a nice syntax.
- Vue and Inertia were my chosen technologies for the frontend, as they are the default options provided when setting up a Laravel project.

## Where I cut corners

- I haven't fully tested the code that gets the hourly data. 
- I haven't implemented the weekly view functionality.
- I haven't cached the API responses as I haven't got enough time to implement and test the data persistence in the DB.


