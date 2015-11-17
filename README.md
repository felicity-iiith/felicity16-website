# Felicity '16
![Travis CI status](https://travis-ci.org/felicity-iiith/felicity16-website.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/felicity-iiith/felicity16-website/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/felicity-iiith/felicity16-website/?branch=master)

This is the repository for the Felicity '16 website.

## Fork and Clone

In order to clone this repository, you might need to use the `--recursive` flag with `git clone`.

## Install

The prerequisites required to build and run the website are [Node.js](https://nodejs.org/) with npm and [Composer](https://getcomposer.org). If you have those installed, you can skip this.
If you need help installing these, please refer to [INSTALL.md](INSTALL.md).

## Build
In order to build the development environment, `cd` into the root directory of this repo, become root if neccessary and run:
```sh
$ npm install # installs required npm packages
$ composer install # installs required composer packages
$ npm install -g gulp # globall install gulp, a task runner
# build the site and then watch for changes, rebuilding when updated
$ gulp && gulp watch
```

After running these commands, you'll have a built copy of the site in a `build/` folder in the root of the project. You work in `src/` and the built site goes to `build/`. You will also need to copy `src/app/config.sample.php` to `src/app/config.php` and edit values (at least the database configuration).

This repo could still need a lot of work before it even becomes a good starting point. Please contribute!
