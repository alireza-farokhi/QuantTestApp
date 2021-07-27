## Goal
a fresh laravel project with sail to accomplish Quant test application

## Installation
- clone project
- optimize `.env` file
- run `./vendor/bin/sail up` (build docker image of project)
- run `./vendor/bin/sail artisan migrate`
- run `./vendor/bin/sail artisan passport:install`
- copy `Password grant client secret` key to `.env` as name `PASSPORT_GRANT_CLINET`
- run `./vendor/bin/sail npm install && npm run dev`

