var gulp = require('gulp'),
	uglify = require('gulp-uglify'),
	connect = require('gulp-connect'),
    concat = require('gulp-concat'),
    cleanCSS = require('gulp-clean-css'),
    watch = require('gulp-watch');

var outputDir = '../app-dist';
//var outputDir = '../cordova/app-dist/www';
/* var sass = require('gulp-sass');

gulp.task('sass', function() {
  gulp.src('styles/main.scss')
  .pipe(sass({style: 'expanded'}))
    .on('error', gutil.log)
  .pipe(gulp.dest('assets'))
}); */

gulp.task('data', function(){
	gulp.src('../app/*.{xml,png}')
   .pipe(gulp.dest(outputDir))

	gulp.src('../app/res/**')
   .pipe(gulp.dest(outputDir+'/res'))

	return gulp.src('../app/data/*')
   .pipe(gulp.dest(outputDir+'/data'))
});


gulp.task('js1', function() {
  gulp.src([
    '../js/analytics.js',
  	'../js/index.js',
  	'../js/jquery.js',
  	'../js/jquery-ui.js',
  	'../bower_components/jquery-cookie/jquery.cookie.js',
  	'../js/bootstrap.min.js',
  	'../js/jquery.mobile-1.4.5.js',
  	'../js/handlebars.min.js',
  	'../js/jquery.validate-1.13.1.min.js',
  	'../js/plugins/storeLocator/jquery.storelocator.js',
  	'../js/libs/underscore-min.js',
  	'../js/libs/backbone-min.js',
  	//'../app/js/platform.js',
  	'../js/twitsFetcher.js',
  	'../js/libs/pretty-json-min.js',
  	'../js/script.js',
  	'../js/lity.min.js',

  	])
  //.pipe(uglify())
  .pipe(concat('scripts.js'))
  .pipe(gulp.dest(outputDir+'/js'))
  .pipe(connect.reload())
});

gulp.task('js2', function() {
	return gulp.src(['../js/plugins/storeLocator/templates/*'])
	.pipe(gulp.dest(outputDir+'/js/plugins/storeLocator/templates'))
});

gulp.task('js', ['js1', 'js2']);

gulp.task('html', function(){
	return gulp.src('../app/index.html')
	.pipe(gulp.dest(outputDir));
});



gulp.task('minify-css', function() {
  
  gulp.src('../css/fonts')
  .pipe(gulp.dest(outputDir+'/css'));

  return gulp.src([
  	'../css/bootstrap.css',
  	'../css/jquery.mobile.structure-1.4.5.min.css',
  	'../css/font-awesome.css',
  	'../css/veolia-fonts.css',
  	'../css/map.css',
  	'../css/lity.min.css',
  	'../css/style.css',
  	'../css/twitsFetcher.css',
  	])
    .pipe(cleanCSS({}))
    .pipe(concat('styles.css'))
    .pipe(gulp.dest(outputDir+'/css'))
});

/*
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

<link rel="stylesheet" href="css/jquery.mobile.structure-1.4.5.min.css" />
<link rel="stylesheet" type="text/css" href="css/map.css">
<link rel="stylesheet" type="text/css" href="css/lity.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/twitsFetcher.css">
*/

gulp.task('copy-fonts', function(){
	return gulp.src('../css/fonts/*')
   .pipe(gulp.dest(outputDir+'/css/fonts'))
});


gulp.task('copy-css-images', function(){
	return gulp.src('../css/*.{png,jpg,jpeg,gif,svg}')
   .pipe(gulp.dest(outputDir+'/css'))
});


gulp.task('css', ['minify-css', 'copy-fonts', 'copy-css-images']);



gulp.task('copy-images', function(){
	return gulp.src('../images/*')
   .pipe(gulp.dest(outputDir+'/images'))
});


gulp.task('stream-css', function(){
  gulp.watch('../css/**/*.css', ['css']);
});

gulp.task('stream-js', function(){
  gulp.watch('../js/**/*.js', ['js']);
});

gulp.task('stream', ['stream-css', 'stream-js']);


/*

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script src="js/handlebars.min.js"></script>

<script src="http://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyBPcrMogq2hfn8w6eP10O0Y12bLeknZ4Bs"></script>

<script src="js/plugins/storeLocator/jquery.storelocator.js"></script>
<script type="js/libs/underscore-min.js"></script>
<script type="js/libs/backbone-min.js"></script>
<script src="js/twitsFetcher.js"></script>
<script type="js/libs/pretty-json-min.js"></script>
<script type="text/javascript" src="js/GooglePlus.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>

<script src="js/script.js"></script>
<script src="js/search.js"></script>
<script src="js/lity.min.js"></script>

*/

gulp.task('default', ['data', 'html','js', 'css', 'copy-images']);