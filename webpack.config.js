const Encore = require('@symfony/webpack-encore');
const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');

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
  .enableBuildNotifications()
  .enableVersioning(Encore.isProduction())

  .addEntry('enhavo/main', './assets/enhavo/main')
  .addEntry('enhavo/index', './assets/enhavo/index')
  .addEntry('enhavo/view', './assets/enhavo/view')
  .addEntry('enhavo/form', './assets/enhavo/form')
  .addEntry('enhavo/editor', './assets/enhavo/editor')
  .addEntry('enhavo/image-cropper', './assets/enhavo/image-cropper')
  .addEntry('enhavo/media-library', './assets/enhavo/media-library')
  .addEntry('enhavo/dashboard', './assets/enhavo/dashboard')
  .addEntry('enhavo/preview', './assets/enhavo/preview')
  .addEntry('enhavo/delete', './assets/enhavo/delete')
  .addEntry('enhavo/list', './assets/enhavo/list')
  .addEntry('enhavo/login', './assets/enhavo/login')
;

config = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());

module.exports = config;