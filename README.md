# Symfony Project - SnowTricks
SnowTricks is a Symfony project as part of training course "Application Developer - PHP / Symfony" on OpenClassrooms.
## Prerequisites
*   PHP 8.0.11
*   SMTP server
## Installation
Copy project on your system
```bash
git clone https://github.com/AEcalle/SnowTricks.git
```
Install dependencies
```bash
composer install
```
### Configure environment variables

Replace the values in .env with your own values
```
DATABASE_URL=
MAILER_DSN=
```

### Create Database
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Load Assets
If you use yarn
```
yarn install
yarn encore dev
```
If you use npm
```
npm install
npm run dev
```

### Load Fixtures
```
php bin/console doctrine:fixtures:load
```



