var gulp = require('gulp');
var sass = require('gulp-ruby-sass');

gulp.task('sass', function() {
  return sass('./src/OAuth/LoginBundle/Resources/scss/*.scss', {style: 'expanded'})
  .pipe(gulp.dest('./src/OAuth/LoginBundle/Resources/public/css'));
});

gulp.task('watch', function() {
  gulp.watch('./src/OAuth/LoginBundle/Resources/scss/*.scss', ['sass']);
});
