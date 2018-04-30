#!/usr/bin/env bash

if [[ ! -f .env ]]; then
    cat .env.dist | sed "s/LOCAL_USER_ID=1000/LOCAL_USER_ID=$(id -u)/" | sed "s/LOCAL_GROUP_ID=1000/LOCAL_GROUP_ID=$(id -g)/" > .env
fi

docker-compose up -d

if [[ $1 == '--prod' ]]; then
    docker-compose run --rm frontend.symfony bash -c "npm install --no-save && yarn run encore production"
    docker-compose exec prod.php.symfony composer install --prefer-dist -n
    docker-compose exec prod.php.symfony bin/console doc:database:drop --force
    docker-compose exec prod.php.symfony bin/console doc:database:create
    docker-compose exec prod.php.symfony bin/console doc:migrations:migrate --no-interaction
    echo -e "Generating project fixtures..."
    docker-compose exec prod.php.symfony bin/console hautelook:fixtures:load --no-interaction
else
    docker-compose run --rm frontend.symfony bash -c "npm install --no-save && yarn run encore dev"
    docker-compose exec php.symfony composer install --prefer-dist -n
    if [[ $1 == '--schema' ]]; then
        docker-compose exec php.symfony bin/console doc:database:drop --force
        docker-compose exec php.symfony bin/console doc:database:create
        docker-compose exec php.symfony bin/console doc:migrations:migrate --no-interaction
        if [[ $2 == '--with-fixtures' ]]; then
            echo -e "Generating project fixtures..."
            docker-compose exec php.symfony bin/console hautelook:fixtures:load --no-interaction
        fi
    fi
fi