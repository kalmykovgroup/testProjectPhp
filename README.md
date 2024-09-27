
### - Проект клонируем в папку
git clone git clone git@github.com:dr-andru/lesson_20240828.git

cd lesson_20240828

"Если надо поменяйте название контейнеров"
sed -i -e 's/lesson_20240828_/lesson_new_/g' ./docker-compose.yaml


Доработать .docker/app/Dockerfile
* Добавить zip к инсталяции ОС (пакетные менедджеры могут быть apt, apt-get, yum, ... etc.)
* Добавить composer (https://getcomposer.org/download/)
  * К комадам добавить `RUN` 
  * Добавить `mv composer.phar /usr/local/bin/composer`


Собрать контейнер
 
`docker compose build --build-arg DOCKER_ID=1000 --build-arg DOCKER_GID=1000`

--build-arg передаются в Dockerfile


Запускаем контейнер

Устанавливаем фреймворк

docker exec lesson_new_app composer create-project laravel/laravel example-app
docker exec lesson_new_app bash -c 'mv example-app/* .;mv example-app/.* .;rmdir example-app'


Замените в docker-compose.yaml для контейнера app
environment (если есть и все переменные секции) на 

     env_file:
          - './app/.env'`

(Внимательно с пробелами) Проверьте что доступы в .env совпадают с переменными в docker-compose для db

.env

      DB_CONNECTION=pgsql
      DB_HOST=db
      DB_PORT=5432
      DB_DATABASE=app
      DB_USERNAME=db_user
      DB_PASSWORD=db_pass

docker-compose.yaml

      POSTGRESQL_USERNAME: db_user
      POSTGRESQL_PASSWORD: db_pass
      POSTGRESQL_DATABASE: app

### - Перезапустите проект



docker exec lesson_new_app composer require orchid/platform

docker exec lesson_new_app php artisan orchid:install

docker exec lesson_new_app php artisan orchid:admin admin admin@admin.com password

docker exec lesson_new_app composer require orchid/crud


Удалим ненужные пункты меню

`sed -i  "s/Menu::make('Get Started')/\/*Menu::make('Get Started')/g" ./app/app/Orchid/PlatformProvider.php`

`sed -i  "s/   Menu::make(__('Users'))/*\/   Menu::make(__('Users'))/g" ./app/app/Orchid/PlatformProvider.php`

Руками замените `->active('*/examples/form/*')`, на `->active('* /examples/form/*')`,


Создайте модели, миграции.

docker exec lesson_new_app php artisan migrate

Создайте ресурсы.

docker exec lesson_new_app php artisan orchid:resource --help

проверьте что они работают в админке


Досутп в БД
`docker exec -it lesson_new_db bash`
`psql -U user app`
 


# Если надо:
## cron - Расписания, если надо запускать скрепты в определенное время
Добавить в docker-compose.yaml

    cron:
    container_name: lesson_new_cron
    build:
      context: .
      dockerfile: .docker/app/Dockerfile.cron
    working_dir: /app
    volumes:
      - ./app:/app
    env_file:
      - ./app/.env
      

`cp .docker/app/Dockerfile .docker/app/Dockerfile.cron`

`touch ./app/crontab` - создаем файл с будущими расписаниями

в .docker/app/Dockerfile.cron добавьте

  
    ################ SUPERCRONIC #################
    ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.1.9/supercronic-linux-amd64 \
        SUPERCRONIC=supercronic-linux-amd64 \
        SUPERCRONIC_SHA1SUM=5ddf8ea26b56d4a7ff6faecdd8966610d5cb9d85
     RUN curl -fsSLO "$SUPERCRONIC_URL" \
     && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
     && chmod +x "$SUPERCRONIC" \
     && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
     && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic
    
    ADD ./app/crontab /etc/crontab
    
    #############################################
    
    USER docker
    
    CMD ["supercronic", "/etc/crontab"]
  
## jobs - Если планируете работать с очередями

Добавить в docker-compose.yaml

    jobs:
      container_name: lesson_new_jobs
      build:
        context: .
        dockerfile: .docker/app/Dockerfile
      working_dir: /app
      volumes:
        - ./app:/app
      env_file:
        - ./app/.env
      entrypoint: ['php', 'artisan', 'queue:work']
      depends_on:
        - db


## mail
Добавить в docker-compose.yaml

    mail:
      container_name: lesson_new_mail
      image: eaudeweb/mailtrap
      ports:
        - "8090:80"
      environment:
        MT_USER: user
        MT_PASSWD: password
        MT_SIZE_LIMIT: 0

Надо будет настроить перенменные в app/.env


## s3 - fileStorage

    minio:
      container_name: lesson_new_minio
      image: minio/minio
      environment:
        MINIO_ROOT_USER: user
        MINIO_ROOT_PASSWORD: password
      ports:
        - "9000:9000"
        - "9001:9001"
      command: 'server /data'

Надо зайти на localhost:9000
Создать access key

его добавить в .env


    FILESYSTEM_DISK=s3
    AWS_URL=http://*****:9000/
    AWS_ENDPOINT=http://*****:9001/
    AWS_ACCESS_KEY_ID=*****
    AWS_SECRET_ACCESS_KEY=******
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=****
    AWS_USE_PATH_STYLE_ENDPOINT=true

docker exec lesson_new_app composer require league/flysystem-aws-s3-v3 "^3.0" --with-all-dependencies

