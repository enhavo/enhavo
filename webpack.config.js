
var Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/app/App.ts')
  .enableTypeScriptLoader()
  .autoProvidejQuery()
;

var config = Encore.getWebpackConfig();

module.exports = config;