#!/usr/bin/env bash

if [[ ! -f .env ]]; then
    cat .env.dist | sed "s/LOCAL_USER_ID=1000/LOCAL_USER_ID=$(id -u)/" | sed "s/LOCAL_GROUP_ID=1000/LOCAL_GROUP_ID=$(id -g)/" > .env
fi

docker-compose up -d
docker pull selenium/standalone-chrome:3.11.0-dysprosium
docker run -d -p 127.0.0.1:4444:4444 selenium/standalone-chrome:3.11.0-dysprosium
docker ps -a

if [[ $1 == '--prod' ]]; then
    docker-compose run --rm frontend.symfony bash -c "npm install --no-save && yarn run encore production"
    docker-compose exec prod.php.symfony composer install --prefer-dist -n
    docker-compose exec prod.php.symfony bin/console doc:database:drop --if-exists --force
    docker-compose exec prod.php.symfony bin/console doc:database:create
    docker-compose exec prod.php.symfony bin/console doc:migrations:migrate --no-interaction
    echo -e "Generating project fixtures..."
    docker-compose exec prod.php.symfony bin/console doctrine:fixtures:load --no-interaction
else
    docker-compose run --rm frontend.symfony bash -c "npm install --no-save && yarn && yarn run encore dev"
    docker-compose exec php.symfony composer install --prefer-dist -n
    docker-compose run php.symfony bash -c "cd acceptance-tests && composer install"
    if [[ $1 == '--schema' ]]; then
        docker-compose exec php.symfony bin/console doc:database:drop --if-exists --force
        docker-compose exec php.symfony bin/console doc:database:create
        docker-compose exec php.symfony bin/console doc:migrations:migrate --no-interaction
        if [[ $2 == '--with-fixtures' ]]; then
            echo -e "Generating project fixtures..."
            docker-compose exec php.symfony bin/console doctrine:fixtures:load --no-interaction
        fi
    fi
fi