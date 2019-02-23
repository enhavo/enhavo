var Encore = require('@symfony/webpack-encore');

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