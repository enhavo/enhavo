const YAML = require('yaml');
const ContainerBuilder = require('./ContainerBuilder');
const Definition = require('./Definition');
const Reference = require('./Reference');

class YamlLoader
{
    /**
     * @param {string} content
     * @return {ContainerBuilder}
     */
    load(content) {
        let data = YAML.parse(content);
        if(data.services) {
            for (let name in data.services) {
                if (!data.services.hasOwnProperty(name)) {
                    break;
                }

                let definition = new Definition(name);
                this.checkArguments(data.services[name], definition);
                this.checkImport(data.services[name], definition);
                this.checkFrom(data.services[name], definition);

                ContainerBuilder.addDefinition(definition);
            }
        }

        return ContainerBuilder;
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    checkArguments(service, definition) {
        if(service.arguments) {
            for (let argument of service.arguments) {
                if(argument.startsWith('@')) {
                    definition.addArgument(new Reference(argument));
                }
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    checkImport(service, definition) {
        if(service.import) {
            definition.setImport(service.import)
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    checkFrom(service, definition) {
        if(service.from) {
            definition.setFrom(service.from)
        }
    }
}

module.exports = new YamlLoader;
