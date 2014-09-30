var gulp = require('gulp');
var run = require('gulp-run');

function handleError(err) {
    console.log(err.toString());
    this.emit('end');
}

gulp.task('code1337-kata', function() {
    run('clear; php kata.php').exec().on('error', handleError);
});

gulp.task('watch', function() {
    gulp.watch('kata.php', ['code1337-kata']);
});

gulp.task('default', ['watch']);
