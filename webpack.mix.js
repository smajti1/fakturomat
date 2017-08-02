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

mix.webpackConfig({
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    }
});

mix
    .js([
        'node_modules/jquery/dist/jquery.js',
        'node_modules/tether/dist/js/tether.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/microplugin/src/microplugin.js',
        'node_modules/sifter/sifter.js',
        'node_modules/selectize/dist/js/selectize.js',
        'resources/assets/js/app.js',
    ], 'public/js/app.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/invoice-pdf.scss', 'public/css')
    .autoload({
        jquery: ['$', 'jQuery', 'window.jQuery'],
        tether: ['Tether', 'window.Tether']
    })
    .version();

