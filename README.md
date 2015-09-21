# Felicity '16

This is the repository for the Felicity '16 website.

## Install

In order to install the prerequisites required to build and run the website, please refer to [INSTALL.md](INSTALL.md). [Node](https://nodejs.org) (and [npm](https://npmjs.org/)) and [composer](https://getcomposer.org) are required.

## Build
In order to build the development environment, `cd` into the root directory of this repo and run:
```sh
$ npm install # installs required npm packages
$ composer install # installs required composer packages
$ npm install -g gulp # globall install gulp, a task runner
# build the site and then watch for changes, rebuilding when updated
$ gulp && gulp watch
```

After running these commands, you'll have a built copy of the site in a `build/` folder in the root of the project. You work in `src/` and the built site goes to `build/`. You will also need to copy `src/app/config.sample.php` to `src/app/config.php` and edit values (at least the database configuration).

This repo could still needs a lot of work before it even becomes a good starting point. Please contribute!
