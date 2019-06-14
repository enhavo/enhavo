const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const yaml = require('js-yaml');

class EnhavoThemeEncore
{
    getWebpackConfig(config)
    {
        const projectDir = this.getProjectDir();

        config.module.rules.forEach(function(rule) {
            if(".ts".match(rule.test)) {
                delete rule.exclude;
                rule.use.forEach(function(loader) {
                    if(loader.loader == 'ts-loader') {
                        loader.options.allowTsInNodeModules = true;
                        loader.options.transpileOnly = true;
                    }
                });
            }
            if(".scss".match(rule.test)) {
                rule.use.forEach(function(loader) {
                    if(loader.loader == 'sass-loader') {
                        loader.options.data = '@import "custom";';
                        let themePath = path.join(projectDir, 'var/theme');
                        if(!fs.existsSync(themePath)) {
                            fs.mkdirSync(themePath);
                        }

                        let customPath = themePath + '/custom.scss';
                        if(!fs.existsSync(path)) {
                            fs.openSync(customPath, 'w');
                        }
                        loader.options.includePaths = [themePath];
                    }
                });
            }
        });

        return config;
    }

    getThemeConfigs(Encore)
    {
        let configs = [];
        for(let theme of this.getThemes()) {
            Encore
                .setOutputPath('public/build/' + theme.key)
                .setPublicPath('/build/' + theme.key)
                .enableSingleRuntimeChunk()
                .enableSourceMaps(!Encore.isProduction())
                .splitEntryChunks()
                .autoProvidejQuery()
                .enableVueLoader()
                .enableSassLoader()
                .enableTypeScriptLoader()
                .enableVersioning(Encore.isProduction())
                .copyFiles({
                    from: theme.path + '/images/',
                    to: 'images/[path][name].[ext]'
                });


                for(let entry of theme.entries) {
                    Encore.addEntry(entry.name, entry.path)
                }
            ;

            let themeConfig = this.getWebpackConfig(Encore.getWebpackConfig());
            themeConfig.name = theme.key;

            configs.push(themeConfig)
        }
        return configs;
    }

    getThemes()
    {
        let theme = this.loadFile(path.dirname(__dirname) + '/theme/theme/base/manifest.yml');
        return [theme];
    }

    loadFile(file)
    {
        let manifest = yaml.safeLoad(fs.readFileSync(file, 'utf8'));
        let theme = {
            entries: [],
            key: manifest.key,
            path: path.dirname(file)
        };

        let entries = manifest.webpack.entries;
        for(let key in entries) {
            if (entries.hasOwnProperty(key)) {
                theme.entries.push({
                    name: key,
                    path: path.dirname(file) + '/' + entries[key]
                })
            }
        }

        return theme;
    }

    getProjectDir()
    {
        const pathArray = path.dirname(__dirname).split(path.sep);
        while(pathArray.length > 0) {
            var tryPath = pathArray.join(path.sep) + path.sep + 'webpack.config.js';
            if(fs.existsSync(tryPath)) {
                return pathArray.join(path.sep);
            }
            pathArray.pop();
        }
        return null;
    }
}

module.exports = new EnhavoThemeEncore;