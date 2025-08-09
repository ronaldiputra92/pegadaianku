@echo off
if "%1"=="" (
    echo Available commands:
    echo.
    echo artisan serve          - Start development server
    echo artisan migrate        - Run migrations
    echo artisan migrate:fresh  - Fresh migration
    echo artisan db:seed        - Seed database
    echo artisan make:model     - Create model
    echo artisan make:controller - Create controller
    echo artisan make:migration - Create migration
    echo artisan route:list     - List all routes
    echo artisan tinker         - Laravel REPL
    echo.
    echo Usage: artisan [command] [options]
    pause
) else (
    php artisan %*
)