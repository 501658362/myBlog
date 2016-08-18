var gulp = require('gulp');
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

/**
 * 拷贝任何需要的文件
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

 gulp.src("vendor/bower_dl/jquery/dist/jquery.js")
     .pipe(gulp.dest("resources/assets/js/"));

 gulp.src("vendor/bower_dl/bootstrap/less/**")
     .pipe(gulp.dest("resources/assets/less/bootstrap"));

 gulp.src("vendor/bower_dl/bootstrap/dist/js/bootstrap.js")
     .pipe(gulp.dest("resources/assets/js/"));

 gulp.src("vendor/bower_dl/bootstrap/dist/fonts/**")
     .pipe(gulp.dest("public/assets/fonts"));

});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {

 // 合并 scripts
 mix.scripts(['js/jquery.js','js/bootstrap.js'],
     'public/assets/js/app.js',
     'resources/assets'
 );

 // 编译 Less
 mix.less('app.less', 'public/assets/css/app.css');
});