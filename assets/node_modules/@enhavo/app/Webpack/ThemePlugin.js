const path = require('path');

class ThemePlugin
{
    constructor(config) {
        this.config = config;
    }

    apply(compiler) {
        let loader = new Loader;
        loader.loadFile(this.configurationPath, builder);
        builder.prepare();
        let validator = new Validator;
        validator.validate(builder);

        compiler.hooks.beforeRun.tap('DependencyInjectionPlugin', compiler => {
            this._addLoader(compiler.options);
        });

        compiler.hooks.watchRun.tap('DependencyInjectionPlugin', compiler => {
            this._addLoader(compiler.options);
        });
    }

    _addLoader(options) {
        options.resolveLoader.modules = [
            'node_modules',
            path.resolve(__dirname, 'Loaders')
        ];
    }
}

module.exports = ThemePlugin;
