# Sportlery

This is the development repository for the [Sportlery](http://www.sportlery.nl) website.


## Installation guide

1. Install [Node.js](https://nodejs.org) (v7+)
2. Run `npm install` from the root directory

## Development

Run `npm run dev` to compile the necessary assets for development mode.
If you are developing JavaScript or SCSS you can use `npm run watch` to automatically
compile assets as upon save.

## Installing OctoberCMS plugins

After installing an OctoberCMS plugin, please make sure to run `php artisan october:mirror public/` to ensure
that all plugin assets are mirrored into the public directory!

## Before publishing

Run `npm run production` to compile the assets for production (minified, optimized).

