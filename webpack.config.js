var Encore = require('@symfony/webpack-encore');
var VueLoaderPlugin = require('vue-loader/lib/plugin');

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .autoProvidejQuery()
  .addEntry('enhavo/app', './assets/app')
  .addEntry('enhavo/view', './assets/view')
;

var config = Encore.getWebpackConfig();

config.resolve = {
  extensions: [".js", ".ts", ".tsx"]
};

config.module = {
  rules: [
    {
      test: /\.vue$/,
      loader: "vue-loader"
    },
    {
      test: /\.tsx?$/,
      loader: "ts-loader",
      options: {
        allowTsInNodeModules: true,
        transpileOnly: true,
        appendTsSuffixTo: [/\.vue$/]
      }
    },
    {
      test: /\.scss$/,
      use: [
        'vue-style-loader',
        'css-loader',
        'sass-loader'
      ]
    },
    {
      test: /\.css$/,
      use: [
        'vue-style-loader',
        'css-loader'
      ]
    }
  ]
};

config.plugins.push(new VueLoaderPlugin());

module.exports = config;