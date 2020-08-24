const EncoreUtil = require('@enhavo/core/EncoreUtil');
const _ = require('lodash');
const path = require('path');

class AppThemePackage
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
        Encore
            .enableSingleRuntimeChunk()
            .enableSourceMaps(!Encore.isProduction())
            .splitEntryChunks()
            .autoProvidejQuery()
            .enableSassLoader()
            .enableTypeScriptLoader()
            .enableVersioning(Encore.isProduction())
        ;

        Encore.copyFiles({
            from: './assets/enhavo/images',
            to: 'images/[path][name].[ext]'
        })

    }

    initWebpackConfig(config)
    {
        const projectDir = EncoreUtil.getProjectDir();

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

module.exports = AppThemePackage;
