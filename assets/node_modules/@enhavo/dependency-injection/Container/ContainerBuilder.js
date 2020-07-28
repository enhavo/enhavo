const Definition = require("./Definition");

class ContainerBuilder
{
    constructor() {
        /** @type {Array<Definition>} */
        this.definitions = [];
    }

    /**
     * @param {Definition} definition
     */
    addDefinition(definition)
    {
        this.definitions.push(definition);
    }

    /**
     * @returns {Array<Definition>}
     */
    getDefinitions()
    {
        return this.definitions;
    }
}

module.exports = new ContainerBuilder;