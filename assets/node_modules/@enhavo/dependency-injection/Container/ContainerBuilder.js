const Definition = require("@enhavo/dependency-injection/Container/Definition");

class ContainerBuilder
{
    constructor() {
        /** @type {Array<Definition>} */
        this.definitions = [];
        this.entrypoints = [];
    }

    /**
     * @param {string} name
     * @returns {Array<Definition>}
     */
    getDefinitionByTagName(name)
    {
        let definitions = [];
        for (let definition of this.definitions) {
            if(definition.hasTag(name)) {
                definitions.push(definition);
            }
        }
        return definitions;
    }

    /**
     * @param {Definition} definition
     */
    addDefinition(definition)
    {
        let index = this._getDefinitionIndex(definition.getName());
        if (index !== -1) {
            this.definitions.push(definition);
            return;
        }

        this.definitions[index] = definition;
    }

    /**
     * @returns {Array<Definition>}
     */
    getDefinitions()
    {
        return this.definitions;
    }

    /**
     * @param name
     * @returns {Definition}
     */
    getDefinition(name) {
        let index = this._getDefinitionIndex(name);
        if (index !== -1) {
            return this.definitions[index];
        }
        return null;
    }

    /**
     * @param name
     * @returns {Definition}
     */
    hasDefinition(name) {
        return this.getDefinition() !== null;
    }

    /**
     * @returns {number}
     * @private
     */
    _getDefinitionIndex(name) {
        for (let i in this.definitions) {
            if (this.definitions[i].getName() === name) {
                return i;
            }
        }
        return -1;
    }
}

module.exports = ContainerBuilder;
