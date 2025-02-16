# Laravel News Aggregator Api

## Introduction
This is a simple Laravel 11 api. it fetches and stores articles from different sources and provides a simple api to the users to access them.

### Basic functionalities:
1. Auth:
Users can perform basic auth functionalities (register, log-in, log-out, reset-password).

2. Articles:
Users can request articles with filter on (date, category or sources), they can also do keyword search within the contents of the articles.

3. Preferences:
Users can store preferences related to their favorite (categories, authors, ..)
They can fetch and delete all their preferences

4. Feed:
Users with preferences can access the Feed resource, which has articles stored based on their preferences.
## Installation

1. Clone the repository:
```sh
https://github.com/OpadaAlzaiede/news_aggregator_api
```

2. Copy .env.example file to .env file:
```php
cp .env.example .env
```

3. Setup database connection credentials within .env file:
```sh
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_username_password
```

4. Setup sanctum secret to use when generating new login tokens:
```sh
SANCTUM_LOGIN_TOKEN=
```

5. Setup your mail server credentials to deal with email verification and password resets mails:
```sh
MAIL_MAILER=
MAIL_SCHEME=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

6. Configure frontend app domain, to send proper mail notifications:
```sh
FRONTEND_APP_DOMAIN=
```

7. Setup api keys to deal with news sources:
```sh
NEWS_API_KEY=
NYT_API_KEY=
```


8. Run the following docker command to build the containers:
```docker
docker-compose up -d
```

9. Run the following docker command to run migrations and seeders within the app container

```docker
docker-compose exec app php artisan migrate:fresh --seed
```

10. Run the following docker command to run migrations and seeders within the app container

```docker
docker-compose exec app php artisan migrate:fresh --seed
```

## Running Tests

To run tests, run the following command

```docker
docker-compose exec app php artisan test
```

You can run tests in parallel, by passing --parallel tag to the test command

```docker
docker-compose exec app php artisan test --parallel
```

## Scheduled tasks

To listen for all dispatched jobs, run the following command

```artisan
docker-compose exec app php artisan queue:listen
```

You can view all scheduled tasks, using this command
```artisan
docker-compose exec app php artisan schedule:list
```
## Api documentation

To access swagger documentation visit the following url

http://localhost/api/documentation


## Development process

1. Create plan of actions (I always start with building plan of actions doc which can be found at the link):

https://drive.google.com/file/d/1NbmqcsEteBwcItKhLc1GZ1-UNvzXQfVU/view?usp=sharing

2. Design the ERD (you can view the final erd through this link):

https://drive.google.com/file/d/1vtkvvdGfoejKnS4tEOveD4IFUl9h4YTr/view?usp=sharing
