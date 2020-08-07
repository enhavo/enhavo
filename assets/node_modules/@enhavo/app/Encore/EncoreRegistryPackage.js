const EncoreUtil = require('@enhavo/core/EncoreUtil');
const fs = require('fs');
const _ = require('lodash');
const DependencyInjectionPlugin = require('@enhavo/dependency-injection/Webpack/DependencyInjectionPlugin');
const path = require('path');

class EncoreRegistryPackage
{
    constructor(config = null)
    {
        this.config = new Config();

        if(typeof config == 'object') {
            _.merge(this.config, config);
        }
    }

    initEncore(Encore, name)
    {
        Encore.addPlugin(new DependencyInjectionPlugin(
            path.resolve(EncoreUtil.getProjectDir(), './assets/services/*')
        ));

        if(name === 'enhavo') {
            Encore
                .enableSingleRuntimeChunk()
                .enableSourceMaps(!Encore.isProduction())
                .splitEntryChunks()
                .autoProvidejQuery()
                .enableVueLoader()
                .enableSassLoader()
                .enableTypeScriptLoader()
                .enableVersioning(Encore.isProduction())

                .addEntry('enhavo/main', './assets/enhavo/main')
                .addEntry('enhavo/index', './assets/enhavo/index')
                .addEntry('enhavo/view', './assets/enhavo/view')
                .addEntry('enhavo/form', './assets/enhavo/form')
                .addEntry('enhavo/preview', './assets/enhavo/preview')
                .addEntry('enhavo/delete', './assets/enhavo/delete')
                .addEntry('enhavo/list', './assets/enhavo/list')
            ;
        } else if(this.config.theme) {
            Encore
                .enableSingleRuntimeChunk()
                .enableSourceMaps(!Encore.isProduction())
                .splitEntryChunks()
                .autoProvidejQuery()
                .enableSassLoader()
                .enableTypeScriptLoader()
                .enableVersioning(Encore.isProduction())
            ;

            if(this.config.copyThemeImages) {
                Encore.copyFiles({
                    from: './assets/enhavo/images',
                    to: 'images/[path][name].[ext]'
                })
            }
        }
    }

    initWebpackConfig(config, name)
    {
        const projectDir = EncoreUtil.getProjectDir();

        if(name === 'enhavo') {
            config.module.rules.forEach(function(rule) {
                if(".scss".match(rule.test)) {
                    rule.use.forEach(function(loader) {
                        if(loader.loader === 'sass-loader') {
                            loader.options.data = '@import "custom-vars";';
                            if(fs.existsSync(path.join(projectDir, 'assets/enhavo/styles/custom-vars.scss'))) {
                                loader.options.includePaths = [path.join(projectDir, 'assets/enhavo/styles')];
                            } else {
                                loader.options.includePaths = [path.join(projectDir, 'node_modules/@enhavo/app/assets/styles/custom')];
                            }
                        }
                    });
                }
            });
        }

        config.module.rules.forEach(function(rule) {
            if(".ts".match(rule.test)) {
                delete rule.exclude;
                rule.use.forEach(function(loader) {
                    if(loader.loader === 'ts-loader') {
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
        this.copyThemeImages = true;
        this.theme = true;
    }
}

module.exports = EncoreRegistryPackage;
