var Encore = require('@symfony/webpack-encore');
var VueLoaderPlugin = require('vue-loader/lib/plugin');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .autoProvidejQuery()
  .enableVueLoader()
  .enableSassLoader()
  .enableTypeScriptLoader()

  .addEntry('enhavo/app', './assets/app')
  .addEntry('enhavo/view', './assets/view')
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

module.exports = config;