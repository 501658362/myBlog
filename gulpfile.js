var gulp = require('gulp');
var rename = require('gulp-rename');
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

    gulp.src("vendor/bower_dl/font-awesome/less/**")
        .pipe(gulp.dest("resources/assets/less/fontawesome"));

    gulp.src("vendor/bower_dl/font-awesome/fonts/**")
        .pipe(gulp.dest("public/assets/fonts"));

    // 拷贝 datatables
    var dtDir = 'vendor/bower_dl/Plugins/integration/';

    gulp.src("vendor/bower_dl/datatables/media/js/jquery.dataTables.js")
        .pipe(gulp.dest('resources/assets/js/'));

    gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.css')
        .pipe(rename('dataTables.bootstrap.less'))
        .pipe(gulp.dest('resources/assets/less/others/'));

    gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.js')
        .pipe(gulp.dest('resources/assets/js/'));

});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {
    // 合并脚本文件
    mix.scripts([
            'js/jquery.js',
            'js/bootstrap.js',
            'js/jquery.dataTables.js',
            'js/dataTables.bootstrap.js'
        ],
        'public/assets/js/app.js',
        'resources/assets'
    );
     // 编译 Less
     mix.less('app.less', 'public/assets/css/app.css');
});