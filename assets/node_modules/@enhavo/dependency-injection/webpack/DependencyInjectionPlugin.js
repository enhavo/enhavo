const Loader = require('@enhavo/dependency-injection/loader/Loader');
const Validator = require('@enhavo/dependency-injection/validation/Validator');
const builderBucket = require('@enhavo/dependency-injection/builder-bucket');
const path = require('path');

class DependencyInjectionPlugin
{
    constructor(configurationPath, name = null) {
        this.configurationPath = configurationPath;
        this.name = name;
    }

    apply(compiler) {
        let builder = builderBucket.createBuilder(this.name);

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
        builderBucket.setCurrentBuilder(this.name);
        options.resolveLoader.modules = [
            'node_modules',
            path.resolve(__dirname, 'loaders')
        ];
    }

    _addEntrypoints(entry) {
        let builder = builderBucket.getBuilder(this.name);
        for (let entrypoint of builder.getEntrypoints()) {
            entry[entrypoint.getName()] = { import: [entrypoint.getPath()] };
        }
    }
}

module.exports = DependencyInjectionPlugin;
