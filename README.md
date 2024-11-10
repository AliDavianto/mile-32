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
1. **Main**: This branch contains the stable version of our application. Only thoroughly tested code should be merged here.
2. **Staging**: This branch is for testing features that are ready for the QA  to review. Code is merged from the development branch to staging for quality assurance before it is promoted to main.
3. **Development**: This branch is where all new features and bug fixes are implemented. Developers should work here and create pull requests to merge their changes into the staging branch.

## How to Navigate, Merge Branches, and Commit Format

### Navigating Between Branches
To switch between branches in Git, use the following command:

```bash
# Switch to the development branch
git checkout development
```
```bash
# Switch to the staging branch
git checkout staging
```
```bash
# Switch to the main branch
git checkout main
```

### Merge Between Branches
To work in the development branch:
```bash
git checkout development
```
To merge from development to staging:
```bash
git checkout staging
git merge development
```

To merge from staging to main:
```bash
git checkout main
git merge staging
```

### Commit Message Format
Format:
```
<type>(<scope>): <subject>

<body>

<footer>
```
1. **Type**: Indicates the nature of the change. Common types include:
- `feat: A new feature`
- `fix: A bug fix`
- `docs: Documentation only changes`
- `style: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc.)`
- `refactor: A code change that neither fixes a bug nor adds a feature`
- `perf: A code change that improves performance`
- `test: Adding missing or correcting existing tests`
- `chore: Changes to the build process or auxiliary tools and libraries such as documentation generation`
- `Scope: (Optional) The part of the codebase affected (e.g., api, ui, database).`
2. **Subject**: A brief description of the change, written in the imperative mood (e.g., "add", "update", "remove"). Keep it concise (ideally 50 characters or less).
3. **Body**: (Optional) A more detailed description of the change, explaining what and why, wrapped at 72 characters. This is where you can elaborate on the reason for the change and its impact.
4. **Footer**: (Optional) Include any references to issues or tasks, such as Fixes #123 or Related to #456.

---

# How To Deploy

### For first time only !
**Make sure git is already installed on your device**
1. Open CMD
2. Navigate to the folder you want to put the project
3. Follow the command below on your CMD
```bash
git clone https://github.com/AliDavianto/mile-32.git
```
```bash
cd mile-32
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
4. Open your browser and open
```
http://localhost:8000
```

### From the second time onwards
```bash
docker compose up -d
```        
# Notes

### Laravel App
- URL: http://localhost:8000/menu

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
