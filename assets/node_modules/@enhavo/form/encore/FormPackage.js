const CopyWebpackPlugin = require('copy-webpack-plugin');
const _ = require('lodash');

class FormPackage
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
        Encore.addEntry('enhavo/editor', './assets/enhavo/entrypoints/editor.ts');
    }

    initWebpackConfig(config)
    {
        config.plugins.push(new CopyWebpackPlugin([
            { from: 'node_modules/tinymce/skins', to: 'skins' },
            { from: 'node_modules/tinymce/plugins', to: 'plugins' },
            { from: 'node_modules/tinymce/icons', to: 'icons' }
        ]));
    }
}

class Config
{
    constructor() {

    }
}

module.exports = FormPackage;
