var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var path = require('path');
var del = require('del');
var gutil = require('gulp-util');

var paths = {
    styles: 'src/static/styles/**/*.{scss,css}',
    scripts: 'src/static/scripts/**/*.js',
    images: 'src/static/images/*.{jpg,jpeg,png,svg}',
    fonts: 'src/static/fonts/*',
    php: 'src/**/*.{php,json,pem}',
    stuff: ['src/.htaccess', 'src/humans.txt', 'src/robots.txt'],
    vendor: ['vendor/**/*'],
};

var reportError = function(error) {
    if ('CI' in process.env && process.env.CI === 'true') {
        process.exit(1);
    }
    notify({
        title: error.plugin + ' failed!',
        message: 'See console.'
    }).write(error);

    // Prevent the 'watch' task from stopping
    this.emit('end');
};

gulp.task('clean', function() {
    // Must be synchronous if we're going to use this task as a dependency
    del.sync('build/');
});

gulp.task('styles', function() {
    return gulp.src(paths.styles)
        .pipe(plumber({
            errorHandler: reportError
        }))
        .pipe(sass().on('error', sass.logError))
        .on('error', reportError)
        .pipe(autoprefixer())
        .pipe(gulp.dest('build/static/styles'));
});

gulp.task('scripts', function() {
    return gulp.src('src/static/scripts/**/*.js')
        .pipe(gulp.dest('build/static/scripts'));
});

gulp.task('images', function() {
    return gulp.src(paths.images)
        .pipe(gulp.dest('build/static/images'));
});

gulp.task('fonts', function() {
    return gulp.src(paths.fonts)
        .pipe(gulp.dest('build/static/fonts'));
});

gulp.task('php', function() {
    return gulp.src(paths.php)
        .pipe(gulp.dest('build/'));
});

gulp.task('stuff', function() {
    return gulp.src(paths.stuff)
        .pipe(gulp.dest('build/'));
});

gulp.task('vendor', function() {
    return gulp.src(paths.vendor, {dot: true})
        .pipe(gulp.dest('build/vendor/'));
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

    var srcToBuildDeleter = deleter('src/', 'build/');

    gulp.watch(paths.styles, ['styles'])
        .on('change', deleter(function(file) {
            return file.replace('src/', 'build/')
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
    gulp.watch(paths.vendor, {dot: true})
        .on('change', deleter('vendor/', 'build/vendor/'));
});

gulp.task('build', ['styles', 'scripts', 'images', 'fonts', 'php', 'stuff', 'vendor']);

gulp.task('default', ['clean'], function() {
    gulp.run('build');
});
