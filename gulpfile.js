var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var browserify = require('browserify');
var babelify = require('babelify');
var source = require('vinyl-source-stream');
var shell = require('gulp-shell');

var es6_files = ['login.es6', 'index.es6'];

gulp.task('build:sass', function() {
  return sass('./src/AppBundle/Resources/scss/*.scss', {style: 'expanded'})
  .pipe(gulp.dest('./web/css'));
});

gulp.task('build:js', function() {
  return es6_files.forEach(function(file_name) {
    browserify('./src/AppBundle/Resources/es6/' + file_name, { debug: true })
     .transform(babelify)
     .bundle()
     .on("error", function (err) { console.log("Error : " + err.message); })
     .pipe(source(file_name.split('.')[0] + '.js'))
     .pipe(gulp.dest('./web/js'));
  });
});

gulp.task('build',[
  'build:sass',
  'build:js'
]);

gulp.task('chmod', shell.task([
  'sudo chmod -R 777 ./app/cache/',
  'sudo chmod -R 777 ./app/logs/'
]));

gulp.task('unit', shell.task(
  './vendor/phpunit/phpunit/phpunit --verbose --debug -c app/'
));

gulp.task('watch', function() {
  gulp.watch('./src/AppBundle/Resources/scss/*.scss', ['build:sass']);
  gulp.watch('./src/AppBundle/Resources/es6/*.es6', ['build:js']);
  gulp.watch('./src/AppBundle/Resources/es6/*.jsx', ['build:js']);
});
