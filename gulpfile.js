const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    mix
        .copy('vendor/bower_components/font-awesome/fonts', 'public/fonts/')
        .scripts([
            '../../../vendor/bower_components/jquery/dist/jquery.js',
            '../../../vendor/bower_components/tether/dist/js/tether.js',
            '../../../vendor/bower_components/bootstrap/dist/js/bootstrap.js',
            '../../../vendor/bower_components/microplugin/src/microplugin.js',
            '../../../vendor/bower_components/sifter/sifter.js',
            '../../../vendor/bower_components/selectize/dist/js/selectize.js',
            'app.js',
        ], 'public/js/app.js')
        .sass('app.scss')
        .sass('invoice-pdf.scss');
});
