let mix = require('laravel-mix');

require('./nova.mix');

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  // .postCss('resources/css/app.css', 'public/css', [
  // .postCss('resources/css/field.css', 'css')
  .postCss('resources/css/field.css', 'css', [
    require('@tailwindcss/postcss'),
  ])
  .vue({ version: 3 })
  // .css('resources/css/field.css', 'css')
  .nova('piotrku/seo-fields');
