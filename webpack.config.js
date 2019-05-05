const Encore = require('@symfony/webpack-encore');
const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');

Encore
    .setOutputPath('public/build/enhavo/')
    .setPublicPath('/build/enhavo')
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

enhavoConfig = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());
enhavoConfig.name = 'enhavo';

Encore.reset();

Encore
    .setOutputPath('public/build/theme/')
    .setPublicPath('/build/theme')
    .enableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .splitEntryChunks()
    .autoProvidejQuery()
    .enableVueLoader()
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableVersioning(Encore.isProduction())

    .addEntry('base', './assets/theme/base')

    .copyFiles({
        from: './assets/theme/images',
        to: 'images/[path][name].[ext]'
    })
;

themeConfig = EnhavoEncore.getWebpackConfig(Encore.getWebpackConfig());
themeConfig.name = 'theme';

module.exports = [enhavoConfig, themeConfig];