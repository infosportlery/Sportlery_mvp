var elixir = require('laravel-elixir');
require('laravel-elixir-livereload');

elixir.config.assetsPath = 'themes/sportlery/assets/';
elixir.config.publicPath = 'themes/sportlery/assets/compiled/';

elixir(function(mix){
    mix.sass('sportlery.scss');

    mix.scripts([
        'jquery.js',
        'app.js',
    ]);
})