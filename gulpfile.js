var gulp = require('gulp');
var sass = require('gulp-ruby-sass');

gulp.task('sass', function() {
  return sass('app/Resources/scss/*.scss', {style: 'expanded', base: 'app'})
  .pipe(gulp.dest('./web/css'));
});

gulp.task('watch', function() {
  gulp.watch('app/Resources/scss/*.scss', ['sass']);
});
