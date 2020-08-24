const CopyWebpackPlugin = require('copy-webpack-plugin');
const _ = require('lodash');

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

    }

    initWebpackConfig(config, name)
    {
        if(name === 'enhavo') {
            config.plugins.push(new CopyWebpackPlugin([
                { from: 'node_modules/tinymce/skins', to: 'skins' },
                { from: 'node_modules/tinymce/plugins', to: 'plugins' },
                { from: 'node_modules/tinymce/icons', to: 'icons' }
            ]));
        }

        if(config.theme) {
            config.plugins.push(new CopyWebpackPlugin([
                { from: 'node_modules/tinymce/skins', to: 'skins' },
                { from: 'node_modules/tinymce/plugins', to: 'plugins' },
                { from: 'node_modules/tinymce/icons', to: 'icons' }
            ]));
        }
    }
}

class Config
{
    constructor() {
        this.theme = false;
    }
}

module.exports = EncoreRegistryPackage;
