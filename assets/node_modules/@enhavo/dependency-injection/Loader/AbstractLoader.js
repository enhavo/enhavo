const ContainerBuilder = require('@enhavo/dependency-injection/Container/ContainerBuilder');
const Definition = require('@enhavo/dependency-injection/Container/Definition');
const Reference = require('@enhavo/dependency-injection/Container/Reference');
const Tag = require('@enhavo/dependency-injection/Container/Tag');

class AbstractLoader
{
    /**
     * @param {object} data
     * @param {ContainerBuilder} builder
     */
    _addDefinitions(data, builder) {
        if(data.services) {
            for (let name in data.services) {
                if (!data.services.hasOwnProperty(name)) {
                    break;
                }

                let service = data.services[name];
                if(service === null) {
                    service = {};
                }

                let definition = new Definition(name);
                this._checkArguments(service, definition);
                this._checkTags(service, definition);
                this._checkImport(service, definition);
                this._checkFrom(service, definition);

                builder.addDefinition(definition);
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    _checkArguments(service, definition) {
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
    _checkTags(service, definition) {
        if(service.tags) {
            for (let tag of service.tags) {
                if(typeof tag == 'string') {
                    definition.addTag(new Tag(tag));
                }

                if(tag.name) {
                    definition.addTag(new Tag(tag.name, tag));
                }
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    _checkImport(service, definition) {
        if(service.import) {
            definition.setImport(service.import)
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    _checkFrom(service, definition) {
        if(service.from) {
            definition.setFrom(service.from)
        }
    }

    _addEntrypoints(data, builder) {

    }
}

module.exports = AbstractLoader;