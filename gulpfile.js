var source = require('vinyl-source-stream')
  , clean = require('gulp-clean')
  , gulp = require('gulp')
  , sass = require('gulp-sass')
  , postcss = require('gulp-postcss')
  , autoprefixer = require('autoprefixer')
  , browserify = require('browserify')
  , sourcemaps = require('gulp-sourcemaps')
  , rename = require('gulp-rename')
  , partialify = require('partialify')

// Paths

var resourcePath = './public/resources/'
  , buildPath = resourcePath + 'compiled/'
  , sassPath = resourcePath + 'sass/'
  , jsPath = resourcePath + 'javascript/'


// Build Sass

gulp.task('sass', ['clean:css'], function () {
    return gulp.src(sassPath + 'main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            includePaths: [
                './node_modules/bootstrap-sass/assets/stylesheets/'
            ]
        }))
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(buildPath + 'css/'))
})


// Build JS

gulp.task('js', ['clean:js'], function (){
    return browserify({
            debug: true, // Generates sourcemaps
            entries: [jsPath + 'main.js']
        })
        .transform(partialify)
        .bundle()
        .pipe(source('main.js'))
        .pipe(gulp.dest(buildPath + 'js/'));
})


// Copy images

gulp.task('copy:images', ['clean:images'], function() {
    return gulp.src(resourcePath + 'images/**')
        .pipe(gulp.dest(buildPath + 'images/'))
})

// Copy fonts

gulp.task('copy:fonts', ['clean:fonts'], function() {
    return gulp.src(resourcePath + 'fonts/**')
        .pipe(gulp.dest(buildPath + 'fonts/'))
})

// Clean Fonts

gulp.task('clean:fonts', function(){
    return gulp.src(buildPath + 'fonts', {
        read: false
    }).pipe(clean())
})

// Clean Images

gulp.task('clean:images', function(){
    return gulp.src(buildPath + 'images', {
        read: false
    }).pipe(clean())
})

// Clean CSS

gulp.task('clean:css', function(){
    return gulp.src(buildPath + 'css', {
        read: false
    }).pipe(clean())
})

// Clean JS

gulp.task('clean:js', function(){
    return gulp.src(buildPath + 'js', {
        read: false
    }).pipe(clean())
})


//Watch
gulp.task('watch', function () {
    gulp.watch(jsPath + '**', ['js']);
    gulp.watch(sassPath + '**', ['sass']);
});

// Default task

gulp.task('default', ['sass', 'js', 'copy:images', 'copy:fonts'])
