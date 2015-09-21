# Install

The following prerequisites are required to build and use the website along with a brief guide to quickly set it up.

## Node.js
In order to run the build process, you'll need [Node.js](https://nodejs.org/) and some npm modules.

There are several ways to install node.js, and it might be best for you to Google about how to get the latest version of node.js for your system. This repo has been tested with node v0.12. (The version provided by the default package manager is generally old.)

On Ubuntu (and Debian, Linux Mint, etc.), an easy way to get node is to run:
```sh
curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash -

# Then install with:
sudo apt-get install -y nodejs
```

An easy way to get node.js (not sure if latest version) on Fedora (and other similar distributions) is to run:
```sh
curl -sL https://rpm.nodesource.com/setup | sudo bash -

# Then install with:
yum -y install nodejs
```

Common ways to keep an updated node.js installation are to use [nvm](https://github.com/creationix/nvm) or [n](https://github.com/tj/n).

## Composer
You'll also need to get [composer](https://getcomposer.org), a PHP package manager.

There are excellent instructions on how to download it at [getcomposer.org/download](https://getcomposer.org/download/).

For quick reference, you can download a working `composer` script into your current directory by running:
```sh
curl -sS https://getcomposer.org/installer | php -- --filename=composer
```
You'll have to move it to some directory that's in your `$PATH` if you want to run it without giving a file path.

After you've got `node` and `composer` set up, check out [README.md](README.md) for information about how to run the build process and set up the site.
