const EncoreUtil = require('@enhavo/core/EncoreUtil');
const path = require('path');
const _ = require('lodash');
const DependencyInjectionPlugin = require('@enhavo/dependency-injection/webpack/DependencyInjectionPlugin');

class AppTestPackage
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
            .disableSingleRuntimeChunk()
            .enableSourceMaps(true)
            .autoProvidejQuery()
            .enableVueLoader()
            .enableSassLoader()
            .enableTypeScriptLoader()
            .enableVersioning(false)
            .cleanupOutputBeforeBuild()
            .addPlugin(new DependencyInjectionPlugin(path.resolve(EncoreUtil.getProjectDir(), './assets/node_modules/@enhavo/**/tests/fixtures/services/*')))

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

module.exports = AppTestPackage;
