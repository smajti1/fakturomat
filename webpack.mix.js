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
    .copy('node_modules/font-awesome/fonts/*', 'public/fonts')
    .js([
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/select2/dist/js/select2.js',
        'resources/js/app.js'
    ], 'public/js/app.js')
    .copy('node_modules/bootstrap/dist/css/bootstrap.css', 'public/css')
    .minify('public/css/bootstrap.css', 'public/css/bootstrap.mini.css')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/invoice-pdf.scss', 'public/css')
    .autoload({
        jquery: ['$', 'jQuery', 'window.jQuery'],
    })
    .version();

