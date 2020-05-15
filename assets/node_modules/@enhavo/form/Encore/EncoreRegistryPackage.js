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
        if(name === 'enhavo') {
            Encore.addEntry('enhavo/editor', './assets/enhavo/editor');
        }
    }

    initWebpackConfig(config, name)
    {
        if(name === 'enhavo') {
            config.plugins.push(new CopyWebpackPlugin([
                { from: 'node_modules/tinymce/skins', to: 'enhavo/skins' },
                { from: 'node_modules/tinymce/plugins', to: 'enhavo/plugins' }
            ]));
        }

        if(config.theme) {
            config.plugins.push(new CopyWebpackPlugin([
                { from: 'node_modules/tinymce/skins', to: 'enhavo/skins' },
                { from: 'node_modules/tinymce/plugins', to: 'enhavo/plugins' }
            ]));
        }
    }
}

class Config
{
    constructor() {
        this.theme = true;
    }
}

module.exports = EncoreRegistryPackage;
