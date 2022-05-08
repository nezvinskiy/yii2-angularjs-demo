const mix = require('laravel-mix');

mix.js('src/js/app.js', 'js')
    .sass('src/sass/app.scss', 'css')
    .setPublicPath('public/dist')
    .sourceMaps(! mix.inProduction())
    .version();
