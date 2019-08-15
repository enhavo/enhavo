const Encore = require('@symfony/webpack-encore');
const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const EnhavoThemeEncore = require('./assets/node_modules/@enhavo/theme/EnhavoThemeEncore');

Encore
    .setOutputPath('public/build/enhavo/')
    .setPublicPath('/build/enhavo')
    .enableSingleRuntimeChunk()
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

enhavoConfig = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());
enhavoConfig.name = 'enhavo';

let configs = EnhavoThemeEncore.getThemeConfigs(Encore);
configs.push(enhavoConfig);
module.exports = configs;