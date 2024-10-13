<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel Docker Starter Kit
- Laravel v11.x
- PHP v8.3.x
- MySQL v8.1.x (default)
- MariaDB v10.11.x
- PostgreSQL v16.x
- pgAdmin v4.x
- phpMyAdmin v5.x
- Mailpit v1.x
- Node.js v18.x
- NPM v10.x
- Yarn v1.x
- Vite v5.x
- Rector v1.x
- Redis v7.2.x

# Requirements
- Stable version of [Docker](https://docs.docker.com/engine/install/)
- Compatible version of [Docker Compose](https://docs.docker.com/compose/install/#install-compose)

## Branch Hierarchy
Your project will follow a three-branch hierarchy:
1. **Main**: This branch contains the stable version of your application. Only thoroughly tested code should be merged here.
2. **Staging**: This branch is for testing features that are ready for the QA team to review. Code is merged from the development branch to staging for quality assurance before it is promoted to main.
3. **Development**: This branch is where all new features and bug fixes are implemented. Developers should work here and create pull requests to merge their changes into the staging branch.

## Merging Strategy
1. **Feature Development**:
   - Developers create a new branch from the `development` branch for each feature or bug fix.
   - After implementing and testing the feature, the developer creates a pull request (PR) to merge their feature branch into the `development` branch.

2. **Staging**:
   - Once a set of features is completed and merged into the `development` branch, create a PR to merge `development` into `staging`.
   - After QA tests the features in the staging branch, if everything is approved, create a PR to merge `staging` into `main`.

3. **Deployment**:
   - The `main` branch represents the live production version of the application and should only contain stable code.

---

# How To Deploy

### For first time only !
```bash
git clone https://github.com/refactorian/laravel-docker.git
```
```bash
cd laravel-docker
```
```bash
docker compose up -d --build
```
```bash
docker compose exec phpmyadmin chmod 777 /sessions
```
```bash
docker compose exec php bash
```
```bash
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```
```bash
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```
```bash
composer setup
```


### From the second time onwards
```bash
docker compose up -d
```        
# Notes

### Laravel Versions
- [Laravel 11.x](https://github.com/refactorian/laravel-docker/tree/main)
- [Laravel 10.x](https://github.com/refactorian/laravel-docker/tree/laravel_10x)

### Laravel App
- URL: http://localhost:8000

### Mailpit
- URL: http://localhost:8025

### phpMyAdmin
- URL: http://localhost:8080
- Server: `db`
- Username: `refactorian`
- Password: `refactorian`
- Database: `refactorian`

### Adminer
- URL: http://localhost:9090
- Server: `db`
- Username: `refactorian`
- Password: `refactorian`
- Database: `refactorian`

### Basic docker compose commands
- Build or rebuild services
    - `docker compose build`
- Create and start containers
    - `docker compose up -d`
- Stop and remove containers, networks
    - `docker compose down`
- Stop all services
    - `docker compose stop`
- Restart service containers
    - `docker compose restart`
- Run a command inside a container
    - `docker compose exec [container] [command]`

### Useful Laravel Commands
- Display basic information about your application
    - `php artisan about`
- Remove the configuration cache file
    - `php artisan config:clear`
- Flush the application cache
    - `php artisan cache:clear`
- Clear all cached events and listeners
    - `php artisan event:clear`
- Delete all of the jobs from the specified queue
    - `php artisan queue:clear`
- Remove the route cache file
    - `php artisan route:clear`
- Clear all compiled view files
    - `php artisan view:clear`
- Remove the compiled class file
    - `php artisan clear-compiled`
- Remove the cached bootstrap files
    - `php artisan optimize:clear`
- Delete the cached mutex files created by scheduler
    - `php artisan schedule:clear-cache`
- Flush expired password reset tokens
    - `php artisan auth:clear-resets`

### Laravel Pint (Code Style Fixer | PHP-CS-Fixer)
- Format all files
    - `vendor/bin/pint`
- Format specific files or directories
    - `vendor/bin/pint app/Models`
    - `vendor/bin/pint app/Models/User.php`
- Format all files with preview
    - `vendor/bin/pint -v`
- Format uncommitted changes according to Git
    - `vendor/bin/pint --dirty`
- Inspect all files
  - `vendor/bin/pint --test`

### Rector
- Dry Run
    - `vendor/bin/rector process --dry-run`
- Process
    - `vendor/bin/rector process`
