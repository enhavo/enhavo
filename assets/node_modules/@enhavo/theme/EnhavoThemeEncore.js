const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const yaml = require('js-yaml');

class EnhavoThemeEncore
{
    constructor() {
        this.manifestFiles = [];
    }

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
            Encore.reset();
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
        let themes = [];

        this.searchFile(this.getProjectDir() + '/assets/theme', 'manifest.yml');
        this.searchFile(this.getProjectDir() + '/assets/theme', 'manifest.yaml');

        for(let file of this.manifestFiles) {
            themes.push(this.loadFile(file));
        }

        return themes;
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

    searchFile(searchPath, filter) {
        if (!fs.existsSync(searchPath)){
            return;
        }

        let files = fs.readdirSync(searchPath);
        for(let i=0;i<files.length;i++){
            let filename = path.join(searchPath, files[i]);
            let stat = fs.lstatSync(filename);
            if (stat.isDirectory()){
                this.searchFile(filename,filter); //recurse
            }
            else if (filename.indexOf(filter)>=0) {
                this.manifestFiles.push(filename);
            }
        }
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