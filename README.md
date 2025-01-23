# DOSZ új webpotál

## A csoamgok előkésíztése

A telepítés lépése:
1. A telepítendő helyre:
   
    ```
   composer create-project riodwanto/superduper-filament-starter-kit
    ```
   
2. Mozgassuk a atrtalmát egyel feljebb
3. Hozzuk létre az .env állományt a következő tartalommal:
 
    ```
    APP_NAME="DOSZ NEW TESZT"
    APP_ENV=local
    APP_KEY=base64:aMWCAE0m6FfnPN/tz6zNWma1eD6Sbmwn42htPo2TDxA=
    APP_DEBUG=true
    APP_TIMEZONE=Budapest/Hungary
    APP_URL=https://dosz-new.test
    
    APP_LOCALE=hu
    APP_FALLBACK_LOCALE=hu
    APP_FAKER_LOCALE=hu_HU
    
    APP_MAINTENANCE_DRIVER=file
    # APP_MAINTENANCE_STORE=database
    
    PHP_CLI_SERVER_WORKERS=4
    
    BCRYPT_ROUNDS=12
    
    #LOG_CHANNEL=stack
    LOG_CHANNEL=daily
    #LOG_STACK=daily
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=dosz
    DB_USERNAME=root
    DB_PASSWORD=
    
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    SESSION_ENCRYPT=false
    SESSION_PATH=/
    #SESSION_DOMAIN=localhost
    
    BROADCAST_CONNECTION=log
    FILESYSTEM_DISK=local
    QUEUE_CONNECTION=database
    
    CACHE_DRIVER=array
    CACHE_STORE=database
    CACHE_PREFIX=
    
    MEMCACHED_HOST=127.0.0.1
    
    REDIS_CLIENT=phpredis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_MAILER=smtp
    MAIL_HOST=mail.nethely.hu
    MAIL_PORT=465
    MAIL_USERNAME=noreply@test.dosz.hu
    MAIL_PASSWORD=T0tya1992testdosz
    MAIL_ENCRYPTION=ssl
    MAIL_FROM_ADDRESS=noreply@test.dosz.hu
    MAIL_FROM_NAME="${APP_NAME}"
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false
    
    VITE_APP_NAME="${APP_NAME}"
    ```

4. Az adatbázisának migrálása:

    ```
    php artisan migrate:fresh --seed
    ```

5. Kulcs generálása:

    ```
    php artisan key:generate
    ```

6. Pluginok:

    ```
    https://filamentphp.com/plugins/3x1io-tomato-language-switcher
    ```

    ```
    https://filamentphp.com/plugins/guava-icon-select-column
    ```

    ```
    https://github.com/shuvroroy/filament-spatie-laravel-health
    ```

    ```
    https://github.com/RickDBCN/filament-email
    ```

    ```
    https://github.com/croustibat/filament-jobs-monitor
    ```

    ```
    https://filamentphp.com/plugins/visual-builder-email-templates
    ```

    ```
    https://v2.filamentphp.com/tricks/use-font-awesome-or-any-other-icon-set
    ```

    ```
    https://github.com/bezhanSalleh/filament-language-switch
    ```

    ```
    https://flyonui.com/docs/getting-started/quick-start/
    ```

    ```
    https://filamentphp.com/plugins/amid-tinyeditor
    ```

    
