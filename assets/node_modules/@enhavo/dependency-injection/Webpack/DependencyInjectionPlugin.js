const Loader = require('@enhavo/dependency-injection/Loader/Loader');
const Validator = require('@enhavo/dependency-injection/Validation/Validator');
const builder = require('@enhavo/dependency-injection/builder');
const path = require('path');

class DependencyInjectionPlugin
{
    constructor(configurationPath) {
        this.configurationPath = configurationPath;
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

        compiler.hooks.entryOption.tap('DependencyInjectionPlugin', (context, entry) => {
            this._addEntrypoints(entry);
        });
    }

    _addLoader(options) {
        options.resolveLoader.modules = [
            'node_modules',
            path.resolve(__dirname, 'Loaders')
        ];
    }

    _addEntrypoints(entry) {
        for (let entrypoint of builder.getEntrypoints()) {
            entry[entrypoint.getName()] = entrypoint.getPath();
        }
    }
}

module.exports = DependencyInjectionPlugin;
