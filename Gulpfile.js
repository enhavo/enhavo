'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var compass = require('compass-importer');
var exec = require('child_process').exec;
var ts = require("gulp-typescript");
var options = require('gulp-options');
var async = require('async');
var download = require("gulp-download");
var decompress = require('gulp-decompress');
var clean = require('gulp-clean');

gulp.task('sass', ['sass:compile']);

gulp.task('sass:compile', function () {
  var directories = [
    {
      from: 'src/Enhavo/Bundle/ThemeBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/ThemeBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/SliderBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/SliderBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/DownloadBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/DownloadBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/AppBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/AppBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/GridBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/GridBundle/Resources/public/css'
    },    {
      from: 'src/Enhavo/Bundle/SearchBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/SearchBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/ShopBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/ShopBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/TranslationBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/TranslationBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/MediaBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/MediaBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/ProjectBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/ProjectBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/DashboardBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/DashboardBundle/Resources/public/css'
    },
    {
      from: 'src/Enhavo/Bundle/ContentBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/ContentBundle/Resources/public/css'
    }
  ];

  var streams = [];

  var sourceMapEmbed = false;
  if (options.has('debug')) {
    sourceMapEmbed = true
  }

  for (var i = 0; i < directories.length; i++) {
    streams.push(
      gulp.src(directories[i].from)
      .pipe(sass({
        importer: compass,
        outputStyle: 'compressed',
        sourceMapEmbed: sourceMapEmbed
      }).on('error', sass.logError))
      .pipe(gulp.dest(directories[i].to))
    );
  }
  return streams;
});

gulp.task('sass:watch', function () {
  gulp.start(['sass']);
  gulp.watch([
    'src/Enhavo/Bundle/*Bundle/Resources/public/sass/**/*.scss'
  ], ['sass']);
});

gulp.task('changelog', function (cb) {
  exec('bundler exec github_changelog_generator enhavo/enhavo', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

gulp.task('docs', function (cb) {
  exec('sphinx-build -b html docs/source build/docs', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

gulp.task('docs:watch', function () {
  gulp.watch([
    'docs/**/*.rst'
  ], ['docs']);
});

gulp.task("tsc", ["tsc:compile"]);

gulp.task("tsc:compile", function () {
  gulp.start(['tsc:compile:app']);
  gulp.start(['tsc:compile:media']);
  gulp.start(['tsc:compile:grid']);
});

gulp.task("tsc:compile:media", function () {
  var tsProject = ts.createProject("src/Enhavo/Bundle/MediaBundle/Resources/public/ts/tsconfig.json");
  return tsProject.src()
    .pipe(tsProject())
    .js.pipe(gulp.dest("src/Enhavo/Bundle/MediaBundle/Resources/public/js"));
});

gulp.task("tsc:compile:app", function () {
  var tsProject = ts.createProject("src/Enhavo/Bundle/AppBundle/Resources/public/ts/tsconfig.json");
  return tsProject.src()
    .pipe(tsProject())
    .js.pipe(gulp.dest("src/Enhavo/Bundle/AppBundle/Resources/public/js"));
});

gulp.task("tsc:compile:grid", function () {
  var tsProject = ts.createProject("src/Enhavo/Bundle/GridBundle/Resources/public/ts/tsconfig.json");
  return tsProject.src()
    .pipe(tsProject())
    .js.pipe(gulp.dest("src/Enhavo/Bundle/GridBundle/Resources/public/js"));
});

gulp.task("tsc:watch", function () {
  gulp.start(['tsc:compile']);
  gulp.watch([
    'src/Enhavo/Bundle/MediaBundle/Resources/public/ts/src/*',
    'src/Enhavo/Bundle/MediaBundle/Resources/public/ts/src/**/*'
  ], ['tsc:compile:media']);
  gulp.watch([
    'src/Enhavo/Bundle/AppBundle/Resources/public/ts/src/*',
    'src/Enhavo/Bundle/AppBundle/Resources/public/ts/src/**/*'
  ], ['tsc:compile:app']);
  gulp.watch([
    'src/Enhavo/Bundle/GridBundle/Resources/public/ts/src/*',
    'src/Enhavo/Bundle/GridBundle/Resources/public/ts/src/**/*'
  ], ['tsc:compile:grid']);
});

gulp.task("elastic:install", function(callback) {
  async.series([
    function (next) {
      download('https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.0.0.zip')
        .pipe(gulp.dest("."))
        .on('end', next);
    },
    function (next) {
      gulp.src('./elasticsearch-6.0.0.zip')
        .pipe(decompress({strip: 1}))
        .pipe(gulp.dest('./elasticsearch'))
        .on('end', next);
    },
    function (next) {
      gulp.src('./elasticsearch-6.0.0.zip', {read: false})
        .pipe(clean())
        .on('end', next);
    }
  ], callback);
});