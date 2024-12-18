const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/categories/index.js', 'public/js/categories');
mix.js('resources/js/products/index.js', 'public/js/products');


mix.styles('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/theme.css');
mix.js('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/js/theme.js');
