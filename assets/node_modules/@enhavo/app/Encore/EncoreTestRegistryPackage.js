const EncoreUtil = require('@enhavo/core/EncoreUtil');
const path = require('path');
const fs = require('fs');
const _ = require('lodash');

class EncoreTestRegistryPackage
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
        Encore
            .disableSingleRuntimeChunk()
            .enableSourceMaps(true)
            .autoProvidejQuery()
            .enableVueLoader()
            .enableSassLoader()
            .enableTypeScriptLoader()
            .enableVersioning(false)
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

module.exports = EncoreTestRegistryPackage;
