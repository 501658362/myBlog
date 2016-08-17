var elixir = require('laravel-elixir');

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

//elixir(function(mix) {
//    mix.sass('app.scss') .browserify('app.js');
//});
//
//elixir(function (mix) {
// mix.styles([
//  'test.css'
// ],'public/assets/css');
//});

/* MIS ASSETS */
resourcesPath = 'resources/assets/';
basePath = 'public/assets/';

elixir(function (mix) {
 mix.copy(resourcesPath + 'avatars', basePath + 'avatars/');
 mix.copy(resourcesPath + 'css', basePath + 'css/');
 mix.copy(resourcesPath + 'font', basePath + 'font/');
 mix.copy(resourcesPath + 'images', basePath + 'images/');
 mix.copy(resourcesPath + 'js', basePath + 'js/');
 mix.copy(resourcesPath + 'js', basePath + 'js/');
 // mix.task('uglify');
});