const CopyWebpackPlugin = require('copy-webpack-plugin');

class EncoreRegistryPackage
{
    constructor(config = null)
    {
        this.config = {};
        if(this.config !== null) {
            this.config = config;
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

module.exports = EncoreRegistryPackage;
