'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var compass = require('compass-importer');
var exec = require('child_process').exec;
var options = require('gulp-options');
var download = require("gulp-download");
var decompress = require('gulp-decompress');
var clean = require('gulp-clean');

gulp.task('sass:compile', function (next) {
  var directories = [
    {
      from: 'docs/theme/enhavo/static/sass/*.scss',
      to: 'docs/theme/enhavo/static/css'
    },
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
    },
    {
      from: 'src/Enhavo/Bundle/NavigationBundle/Resources/public/sass/*.scss',
      to: 'src/Enhavo/Bundle/NavigationBundle/Resources/public/css'
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
      .on('end', next)
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

gulp.task('sass', gulp.series('sass:compile'));

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