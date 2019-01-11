
var Encore = require('@symfony/webpack-encore');


Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('enhavo/app', './assets/app')
  .enableTypeScriptLoader()
  .enableForkedTypeScriptTypesChecking()
  .autoProvidejQuery()
;

var config = Encore.getWebpackConfig();

module.exports = config;