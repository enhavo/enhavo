const path = require('path');
const fs = require('fs');
const EncoreUtil = require('@enhavo/core/EncoreUtil');

class EnhavoThemeEncore
{
    constructor() {
        this.manifestFiles = [];
    }

    addThemes(EnhavoEncore, ThemeLoader) {
        for(let theme of ThemeLoader.getThemes()) {
            let webpackFile = path.join(theme.path, 'webpack.config.js')
            if (fs.existsSync(webpackFile)) {
                let config = this._createConfig(webpackFile, theme.key);
                if (config === null) {
                    throw 'No webpack config with name "'+theme.key+'" exists in file "'+webpackFile+'"';
                }
                EnhavoEncore.addConfig(theme.key, config);
            }
        }
    }

    _createConfig(webpackFile, key)
    {
        let content = fs.readFileSync(webpackFile)+'';
        let m = new module.constructor();
        m.paths = module.paths;
        m._compile(content, webpackFile);
        let configs = m.exports;

        for (let config of configs) {
            if (config.name === key) {
                return this._rewriteConfig(config, path.dirname(webpackFile));
            }
        }

        return null;
    }

    _rewriteConfig(config, dirname)
    {
        for (let key in config.entry) {
            if (!config.entry.hasOwnProperty(key)) {
                continue;
            }
            let entry = config.entry[key];
            if (entry.match(/^.\//)) { // ignore _tmp_copy
                let entryPath = path.join(dirname, entry);
                config.entry[key] = './'+path.relative(EncoreUtil.getProjectDir(), entryPath);
            }
        }
        return config;
    }
}

module.exports = new EnhavoThemeEncore;
