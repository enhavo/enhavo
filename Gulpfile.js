'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var compass = require('compass-importer');
var exec = require('child_process').exec;

gulp.task('sass', function () {
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
    }
  ];

  var streams = [];
  for (var i = 0; i < directories.length; i++) {
    streams.push(
      gulp.src(directories[i].from)
      .pipe(sass({
        importer: compass,
        outputStyle: 'compressed',
        sourceMapEmbed: true
      }).on('error', sass.logError))
      .pipe(gulp.dest(directories[i].to))
    );
  }
  return streams;
});

gulp.task('sass:watch', function () {
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