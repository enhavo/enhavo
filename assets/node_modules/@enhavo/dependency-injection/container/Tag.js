
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

    getParameters() {
        return this.parameters;
    }
}
