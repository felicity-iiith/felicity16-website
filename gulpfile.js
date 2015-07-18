var gulp = require('gulp');
var jshint = require('gulp-jshint');
var autoprefixer = require('gulp-autoprefixer');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');

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

gulp.task('lint', function() {
    return gulp.src(['src/static/scripts/**/*.js', 'gulpfile.js', '!src/static/js/vendor'])
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(jshint.reporter('fail'));
});

gulp.task('styles', function() {
    return gulp.src('src/static/styles/**/*.{scss,css}')
        .pipe(plumber({
            errorHandler: reportError
        }))
        .pipe(sass().on('error', sass.logError))
        .on('error', reportError)
        .pipe(gulp.dest('build/static/css'));
});

gulp.task('scripts', function() {
    return gulp.src('src/static/scripts/**/*.js')
        .pipe(gulp.dest('build/static/js'));
});

gulp.task('images', function() {
    return gulp.src('src/static/images/*.{jpg,jpeg,png,svg}')
        .pipe(gulp.dest('build/static/img'));
});

gulp.task('php', function() {
    return gulp.src('src/**/*.php')
        .pipe(gulp.dest('build/'));
});

gulp.task('stuff', function() {
    return gulp.src(['src/.htaccess', 'src/humans.txt', 'src/robots.txt'])
        .pipe(gulp.dest('build/'));
});

gulp.task('default', ['styles', 'scripts', 'images', 'php', 'stuff']);
