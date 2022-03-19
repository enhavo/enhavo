const EncoreUtil = require('@enhavo/core/EncoreUtil');
const fs = require('fs');
const _ = require('lodash');
const DependencyInjectionPlugin = require('@enhavo/dependency-injection/webpack/DependencyInjectionPlugin');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const Webpack = require('webpack');

class AppPackage
{
    constructor(config = null)
    {
        this.config = new Config();

        if(typeof config == 'object') {
            this.config = _.merge(this.config, config);
        }
    }

    initEncore(Encore)
    {
        // Because we need at least one entrypoint to pass the validation check, we add some empty dummy entrypoint
        // Other entrypoint are loaded via the dependency injection
        let dummyEntrypoint = './'+path.relative(EncoreUtil.getProjectDir(), path.resolve( __dirname, '../entrypoint/dummy.ts'));

        Encore
            .addEntry('enhavo/app/dummy', dummyEntrypoint)
            .enableSingleRuntimeChunk()
            .enableSourceMaps(!Encore.isProduction())
            .splitEntryChunks()
            .autoProvidejQuery()
            .enableVueLoader(() => {}, { runtimeCompilerBuild: false })
            .enableSassLoader()
            .enableTypeScriptLoader()
            .enableVersioning(Encore.isProduction())
            .addPlugin(new DependencyInjectionPlugin(
                path.resolve(EncoreUtil.getProjectDir(), './assets/services/enhavo/*')
            ))
            .addPlugin(new Webpack.DefinePlugin({
                __VUE_OPTIONS_API__: true,
                __VUE_PROD_DEVTOOLS__: true
            }));
    }

    initWebpackConfig(config)
    {
        const projectDir = EncoreUtil.getProjectDir();

        config.module.rules.unshift({
            test: /\.font\.js/,
            use: [
                MiniCssExtractPlugin.loader,
                {
                    loader: 'css-loader',
                    options: {
                        url: false
                    }
                },
                require.resolve('webfonts-loader')
            ]
        });

        config.module.rules.forEach(function(rule) {
            if(".scss".match(rule.test)) {
                for (let loader of rule.oneOf[1].use) {
                    if(loader.loader && loader.loader.match(/sass-loader/)) {
                        if(fs.existsSync(path.join(projectDir, 'assets/enhavo/styles/custom-vars.scss'))) {
                            loader.options.sassOptions.includePaths = [path.join(projectDir, 'assets/enhavo/styles')];
                        } else {
                            loader.options.sassOptions.includePaths = [path.join(projectDir, 'node_modules/@enhavo/app/assets/styles/custom')];
                        }
                    }
                }
            }
        });

        config.module.rules.forEach(function(rule) {
            if(".ts".match(rule.test)) {
                delete rule.exclude;
                rule.use.forEach(function(loader) {
                    if(loader.loader.match(/ts-loader/)) {
                        loader.options.allowTsInNodeModules = true;
                        loader.options.transpileOnly = true;
                    }
                });
            }
        });

        config.resolve.alias['jquery'] = path.join(projectDir, 'node_modules/jquery/src/jquery');
        config.resolve.alias['jquery-ui/ui/widget'] = path.join(projectDir, 'node_modules/blueimp-file-upload/js/vendor/jquery.ui.widget.js');
        return config;
    }
}

class Config
{
    constructor() {

    }
}

module.exports = AppPackage;
