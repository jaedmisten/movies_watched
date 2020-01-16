var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');

/*
 -- TOP LEVEL FUNCTIONS --
 gulp.task - Define tasks
 gulp.src - Point tofiles to user
 gulp.dest - Points to folder to output
 gulp.watch - Watch files and folders for changes
*/

gulp.task('js', function(done) {
    gulp.src('public/js/controllers/moviesWatchedController.js')
		.pipe(uglify())
		.pipe(gulp.dest('assets/'));
		done();
});

gulp.task('concat', function(done) {
	gulp.src(['src/js/app.js', 'src/js/controllers/*'])
		.pipe(concat('main.js'))
		.pipe(gulp.dest('public/dist'))
		done();
});

gulp.task('concat_uglify', function(done) {
	gulp.src(['src/js/*'])
		.pipe(concat('main.js'))
		.pipe(uglify())
		.pipe(gulp.dest('public/dist'))
		done();
});

gulp.task('watch', function() {
	gulp.watch('src/js/controllers/*', gulp.series('concat'));
});

exports.default = gulp.series('concat');