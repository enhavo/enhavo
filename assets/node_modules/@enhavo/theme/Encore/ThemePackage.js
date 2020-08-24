const EncoreUtil = require('@enhavo/core/EncoreUtil');
const path = require('path');
const fs = require('fs');

class ThemePackage
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
            config.module.rules.forEach(function(rule) {
                if(".scss".match(rule.test)) {
                    for (let loader of rule.oneOf[1].use) {
                        if(loader.loader && loader.loader.match(/sass-loader/)) {
                            let themePath = path.join(projectDir, 'var/theme');
                            if(!fs.existsSync(themePath)) {
                                fs.mkdirSync(themePath);
                            }

                            let customPath = themePath + '/custom.scss';
                            if(!fs.existsSync(path)) {
                                fs.openSync(customPath, 'w');
                            }

                            loader.options.sassOptions.includePaths = [themePath];
                        }
                    }
                }
            });
        });
    }
}

module.exports = ThemePackage;