const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.webpackConfig({
    stats: {
        children: true,
    },
});
mix.js('packages/wikichua/bliss/resources/js/app.js', 'public/js')
.js('packages/wikichua/bliss/resources/js/alpine.js', 'public/js')
.postCss('packages/wikichua/bliss/resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);
mix.disableNotifications();
