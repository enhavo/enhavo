
export default class Tag
{
    /**
     * @param {string} name
     * @param {object} parameters
     */
    constructor(name, parameters = {})
    {
        this.name = name;
        this.parameters = parameters;
    }

    getName() {
        return this.name;
    }

    getParameter(name) {
        return this.parameters[name];
    }

    hasParameter(name) {
        return typeof this.parameters[name] !== 'undefined';
    }

    getParameters() {
        return this.parameters;
    }
}
