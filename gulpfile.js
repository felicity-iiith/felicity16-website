var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var path = require('path');
var del = require('del');
var gutil = require('gulp-util');
var args = require('yargs').argv;
var gulpif = require('gulp-if');
var minifyCss = require('gulp-minify-css');
var uglifyjs = require('gulp-uglify');

var paths = {
    styles: 'src/static/styles/**/*.{scss,css}',
    scripts: 'src/static/scripts/**/*.js',
    images: 'src/static/images/**/*.{jpg,jpeg,png,svg}',
    fonts: 'src/static/fonts/*',
    php: 'src/**/*.{php,json,pem}',
    stuff: ['src/.htaccess', 'src/humans.txt', 'src/robots.txt', 'src/favicon.ico'],
    locale: ['src/locale/**/*'],
    vendor: ['vendor/**/*'],
};

var destination = args.deploy ? 'deploy' : 'build';

var reportError = function(error) {
    if ('CI' in process.env && process.env.CI === 'true') {
        process.exit(1);
    }
    notify({
        title: error.plugin + ' failed!',
        message: error.message
    }).write(error);

    // Prevent the 'watch' task from stopping
    this.emit('end');
};

gulp.task('clean', function() {
    // Must be synchronous if we're going to use this task as a dependency
    del.sync(destination + '/');
});

gulp.task('styles', function() {
    return gulp.src(paths.styles)
        .pipe(plumber({
            errorHandler: reportError
        }))
        .pipe(sass())
        .on('error', reportError)
        .pipe(autoprefixer())
        .pipe(gulpif(args.deploy, minifyCss()))
        .pipe(gulp.dest(destination + '/static/styles'));
});

gulp.task('scripts', function() {
    return gulp.src('src/static/scripts/**/*.js')
        .pipe(gulpif(args.deploy, uglifyjs()))
        .pipe(gulp.dest(destination + '/static/scripts'));
});

gulp.task('images', function() {
    return gulp.src(paths.images)
        .pipe(gulp.dest(destination + '/static/images'));
});

gulp.task('fonts', function() {
    return gulp.src(paths.fonts)
        .pipe(gulp.dest(destination + '/static/fonts'));
});

gulp.task('php', function() {
    return gulp.src(paths.php)
        .pipe(gulp.dest(destination + '/'));
});

gulp.task('stuff', function() {
    return gulp.src(paths.stuff)
        .pipe(gulp.dest(destination + '/'));
});

gulp.task('locale', function() {
    return gulp.src(paths.locale, {dot: true})
        .pipe(gulp.dest(destination + '/locale'));
});

gulp.task('vendor', function() {
    return gulp.src(paths.vendor, {dot: true})
        .pipe(gulp.dest(destination + '/vendor/'));
});

gulp.task('watch', ['default'], function() {
    function deleter() {
        var replaceFunc = null;
        if (typeof arguments[0] === 'string') {
            var before = arguments[0];
            var after = arguments[1];
            replaceFunc = function(file) {
                return file.replace(before, after);
            };
        }
        else if (typeof arguments[0] === 'function') {
            replaceFunc = arguments[0];
        }
        return function(event) {
            if (event.type == 'deleted') {
                var file = path.relative('./', event.path);
                if (typeof replaceFunc === 'function') {
                    file = replaceFunc(file);
                }
                del(file);
                gutil.log('Deleted file', '\'' + file + '\'');
            }
        };
    }

    var srcToBuildDeleter = deleter('src/', destination + '/');

    gulp.watch(paths.styles, ['styles'])
        .on('change', deleter(function(file) {
            return file.replace('src/', destination + '/')
                .replace(/\.scss$/, '.css');
        }));
    gulp.watch(paths.scripts, ['scripts'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.images, ['images'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.fonts, ['fonts'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.php, ['php'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.stuff, ['stuff'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.locale, ['locale'])
        .on('change', srcToBuildDeleter);
    gulp.watch(paths.vendor, {dot: true})
        .on('change', deleter('vendor/', destination + '/vendor/'));
});

gulp.task('build', ['styles', 'scripts', 'images', 'fonts', 'php', 'stuff', 'locale', 'vendor']);

gulp.task('default', ['clean'], function() {
    gulp.run('build', function(){
        if (args.deploy) {
            del.sync(['deploy/.htaccess', 'deploy/index.php', 'deploy/app/config.php']);
        }
    });
});
