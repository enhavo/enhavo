const Loader = require('@enhavo/dependency-injection/Loader/Loader');
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

        compiler.hooks.beforeRun.tap('DependencyInjectionPlugin', compiler => {
            this.addLoaderPath(compiler.options);
        });

        compiler.hooks.entryOption.tap('DependencyInjectionPlugin', (context, entry) => {
            this.addEntrypoints(entry);
        });
    }

    addLoaderPath(options) {
        options.resolveLoader.modules = [
            'node_modules',
            path.resolve(__dirname, 'Loaders')
        ];
    }

    addEntrypoints(entry) {
        for (let entrypoint of builder.getEntrypoints()) {
            entry[entrypoint.getName()] = entrypoint.getPath();
        }
    }
}

module.exports = DependencyInjectionPlugin;
