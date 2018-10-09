var path = require('path');
var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
  .setOutputPath('web/build/')
  .setPublicPath('/build')
  .addEntry('media', './src/Enhavo/Bundle/MediaBundle/Resources/public/ts/src/media/Block/MediaLibrary.ts')
  .addEntry('app', './app.js')
  .addPlugin(new CopyWebpackPlugin([
    { from: 'node_modules/tinymce/skins/lightgray', to: 'tinymce/skin' },
    { from: 'node_modules/tinymce/jquery.tinymce.js', to: 'tinymce/jquery.tinymce.js' },
    { from: 'node_modules/tinymce/plugins', to: 'tinymce/plugins' }
  ]))
  .enableTypeScriptLoader()
;

var config = Encore.getWebpackConfig();

config.resolve.alias = {
  'media': path.resolve(__dirname, './src/Enhavo/Bundle/MediaBundle/Resources/public/ts/src/media')
};

module.exports = config;