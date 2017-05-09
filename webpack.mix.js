const path = require('path');
const { mix, config } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery']
});

mix.webpackConfig({
    externals: {
        jquery: 'jQuery'
    }
});

mix.js('themes/sportlery/assets/js/sportlery.js', 'public/themes/sportlery/assets/js/')
   .sass('themes/sportlery/assets/scss/sportlery.scss', 'public/themes/sportlery/assets/css/');
