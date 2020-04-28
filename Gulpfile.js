'use strict';

var gulp = require('gulp');
var exec = require('child_process').exec;
var download = require("gulp-download");
var decompress = require('gulp-decompress');
var clean = require('gulp-clean');

gulp.task('docs', function (cb) {
  exec('sphinx-build -b html docs/source build/docs', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

gulp.task('docs:watch', function () {
    exec('sphinx-autobuild docs/source build/docs', function (err, stdout, stderr) {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    });
});


gulp.task("elastic:download", function(next) {
  download('https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.0.0.zip')
    .pipe(gulp.dest("."))
    .on('end', next);
});

gulp.task("elastic:decompress", function(next) {
  gulp.src('./elasticsearch-6.0.0.zip')
    .pipe(decompress({strip: 1}))
    .pipe(gulp.dest('./elasticsearch'))
    .on('end', next);
});

gulp.task("elastic:clean", function(next) {
  gulp.src('./elasticsearch-6.0.0.zip', {read: false})
    .pipe(clean())
    .on('end', next);
});

gulp.task("elastic:install", gulp.series("elastic:download", "elastic:decompress", "elastic:clean"));

gulp.task('routes:dump', function (cb) {
    exec('bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json', function (err, stdout, stderr) {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    });
});