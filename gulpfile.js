var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var browserify = require('browserify');
var babelify = require('babelify');
var source = require('vinyl-source-stream');

var es6_files = ['login.es6', 'index.es6', 'timeline.jsx', 'menu.jsx'];

gulp.task('sass', function() {
  return sass('./src/AppBundle/Resources/scss/*.scss', {style: 'expanded'})
  .pipe(gulp.dest('./web/css'));
});

gulp.task('browserify', function() {
  return es6_files.forEach(function(file_name) {
    browserify('./src/AppBundle/Resources/es6/' + file_name, { debug: true })
     .transform(babelify)
     .bundle()
     .on("error", function (err) { console.log("Error : " + err.message); })
     .pipe(source(file_name.split('.')[0] + '.js'))
     .pipe(gulp.dest('./web/js'));
  });
});

gulp.task('watch', function() {
  gulp.watch('./src/AppBundle/Resources/scss/*.scss', ['sass']);
  gulp.watch('./src/AppBundle/Resources/es6/*.es6', ['browserify']);
  gulp.watch('./src/AppBundle/Resources/es6/*.jsx', ['browserify']);
});
