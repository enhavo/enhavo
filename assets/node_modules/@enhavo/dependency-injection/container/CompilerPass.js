
export default class CompilerPass
{
    /**
     * @param {string} name
     * @param {string} path
     * @param {string} context
     * @param {object} options
     */
    constructor(name, path, context, priority = 100, options = {})
    {
        this.name = name;
        this.path = path;
        this.options = options;
        this.context = context;
        this.priority = priority;
    }

    getName() {
        return this.name;
    }

    getPath() {
        return this.path;
    }

    getContext() {
        return this.context;
    }

    getOptions() {
        return this.options;
    }

    getPriority() {
        return this.options;
    }
}

