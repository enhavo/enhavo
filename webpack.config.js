var Encore = require('@symfony/webpack-encore');
var path = require('path');
var CopyWebpackPlugin = require('copy-webpack-plugin');


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

  .addPlugin(new CopyWebpackPlugin([
    { from: 'node_modules/tinymce/skins', to: 'enhavo/skins' },
    { from: 'node_modules/tinymce/plugins', to: 'enhavo/plugins' }
  ]))
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
});

config.resolve.alias.jquery = path.join(__dirname, 'node_modules/jquery/src/jquery');

module.exports = config;