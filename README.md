## Simple Subscription Multi Website

```
Author: Wisnu Hafid
Create At: 30 Oct 2021
Framework: Laravel 8
```

---------

## Setting up

make database name `simplesubscribe` first, then run this command:

```sh
$ git clone <repo-url>
$ cp .env.example .env
$ composer install
$ php artisan key:generate
$ php artisan migrate --seed
```

---------

## Custom Artisan Command

Send email to all subscriber by post id

```sh
$ php artisan mail:send {postid}
```

---------

## API Docs

Endpoint: /api/posts

Method: POST

Parameter: 

required:

- title
- content
- website_id

optional:

- state (1 = published, 0 = draft) default 0

---------

Endpoint: /api/subscribe

Method: POST

Parameter: 

required:

- name
- email
- website_id

optional:

- state (1 = subscribe, 0 = unsubscribe) default 1