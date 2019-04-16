var Encore = require('@symfony/webpack-encore');
var path = require('path');
var CopyWebpackPlugin = require('copy-webpack-plugin');
var HardSourceWebpackPlugin = require('hard-source-webpack-plugin');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .splitEntryChunks()
  .autoProvidejQuery()
  .enableVueLoader()
  .enableSassLoader()
  .enableTypeScriptLoader()

  .addEntry('enhavo/main', './assets/main')
  .addEntry('enhavo/index', './assets/index')
  .addEntry('enhavo/view', './assets/view')
  .addEntry('enhavo/form', './assets/form')
  .addEntry('enhavo/editor', './assets/editor')
  .addEntry('enhavo/image-cropper', './assets/image-cropper')
  .addEntry('enhavo/media-library', './assets/media-library')
  .addEntry('enhavo/dashboard', './assets/dashboard')
  .addEntry('enhavo/preview', './assets/preview')

  .addPlugin(new CopyWebpackPlugin([
    { from: 'node_modules/tinymce/skins', to: 'enhavo/skins' },
    { from: 'node_modules/tinymce/plugins', to: 'enhavo/plugins' }
  ]))
  //.addPlugin(new HardSourceWebpackPlugin())
;

var config = Encore.getWebpackConfig();

config.module.rules.forEach(function(rule) {
  if(".ts".match(rule.test)) {
    delete rule.exclude;
    rule.use.forEach(function(loader) {
      if(loader.loader == 'ts-loader') {
        loader.options.allowTsInNodeModules = true;
        loader.options.transpileOnly = true;
      }
    });
  }
  if(".scss".match(rule.test)) {
     rule.use.forEach(function(loader) {
      if(loader.loader == 'sass-loader') {
        loader.options.data = '@import "custom";';
        loader.options.includePaths = [path.join(__dirname, 'assets/styles/enhavo')];
      }
    });
  }
});

config.resolve.alias['jquery'] = path.join(__dirname, 'node_modules/jquery/src/jquery');
config.resolve.alias['jquery.ui.widget'] = path.join(__dirname, 'node_modules/blueimp-file-upload/js/vendor/jquery.ui.widget.js');

module.exports = config;