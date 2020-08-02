const YAML = require('yaml');
const ContainerBuilder = require('@enhavo/dependency-injection/Container/ContainerBuilder');
const AbstractLoader = require('@enhavo/dependency-injection/Loader/AbstractLoader');

class YamlLoader extends AbstractLoader
{
    /**
     * @param {string} content
     * @param {ContainerBuilder} builder
     * @param {object} options
     * @return {ContainerBuilder}
     */
    load(content, builder, options =  {}) {
        let data = YAML.parse(content);
        options = this._configureOptions(options);

        if(options.loadDefinition) {
            this._addDefinitions(data, builder);
        }

        if(options.loadEntrypoint) {
            this._addEntrypoints(data, builder);
        }

        return builder;
    }

    _configureOptions(options) {
        if(typeof options.loadDefinition === 'undefined') {
            options.loadDefinition = true;
        }
        if(typeof options.loadEntrypoint === 'undefined') {
            options.loadDefinition = true;
        }
        return options;
    }
}

module.exports = new YamlLoader;
