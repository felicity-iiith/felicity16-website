# Felicity '16
![Travis CI status](https://travis-ci.org/felicity-iiith/felicity16-website.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/felicity-iiith/felicity16-website/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/felicity-iiith/felicity16-website/?branch=master)

This is the repository for the Felicity '16 website.

## Fork and Clone

In order to clone this repository, you will need to use the `--recursive` flag with `git clone` (or you could initialize the submodule later with `git submodule update --init --recursive`).

## Prerequisites

You will need an Apache server that can run PHP and a MySQL-compatiable server for the database. You will also need to install the `mysqlnd` mysql driver.

The prerequisites required to build and run the website are [Node.js](https://nodejs.org/) (with npm) and [Composer](https://getcomposer.org).

If you need help installing these dependencies, please refer to [INSTALL.md](INSTALL.md).

## Install and Build
In order to build the development environment, `cd` into the root directory of this repo, become root if neccessary and run:
```sh
$ npm install # installs required npm packages
$ composer install # installs required composer packages
$ npm install -g gulp # globall install gulp, a task runner
# build the site and then watch for changes, rebuilding when updated
$ gulp watch
```

After running these commands, you'll have a built copy of the site in a `build/` folder in the root of the project. You work in `src/` and the built site goes to `build/`.

Also,
- Copy `src/app/config.sample.php` to `src/app/config.php` and edit values (at least the database configuration).
- Import the `database.sql` file into your database, you can do this by `mysql -u username -p password DBNAME < database.sql`
- There are additional SQL files to import in `/sql/` and if you already have a particular version of the database and want to upgrade, look at `/sql/migrations/`.

Please contribute!
