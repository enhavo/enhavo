const EncoreUtil = require('@enhavo/core/EncoreUtil');
const path = require('path');
const fs = require('fs');

class EncoreRegistryPackage
{
    constructor(themeLoader)
    {
        this.themeLoader = themeLoader;
    }

    initEncore(Encore, name)
    {
        let theme = this.themeLoader.getTheme(name);

        if(theme === null) {
            return;
        }

        Encore.copyFiles({
            from: theme.path + '/images/',
            to: 'images/[path][name].[ext]'
        });
    }

    initWebpackConfig(config, name)
    {
        let theme = this.themeLoader.getTheme(name);

        if(theme === null) {
            return;
        }

        const projectDir = EncoreUtil.getProjectDir();

        config.module.rules.forEach(function(rule) {
            // if(".scss".match(rule.test)) {
            //     rule.use.forEach(function(loader) {
            //         if(loader.loader === 'sass-loader') {
            //             loader.options.data = '@import "custom";';
            //             let themePath = path.join(projectDir, 'var/theme');
            //             if(!fs.existsSync(themePath)) {
            //                 fs.mkdirSync(themePath);
            //             }
            //
            //             let customPath = themePath + '/custom.scss';
            //             if(!fs.existsSync(path)) {
            //                 fs.openSync(customPath, 'w');
            //             }
            //             loader.options.includePaths = [themePath];
            //         }
            //     });
            // }
        });
    }
}

module.exports = EncoreRegistryPackage;