
class CompilerPass
{
    /**
     * @param {string} name
     * @param {string} path
     * @param {object} options
     */
    constructor(name, path, options = {})
    {
        this.name = name;
        this.path = path;
        this.options = options;
    }

    getName() {
        return this.name;
    }

    getPath() {
        return this.path;
    }

    getOptions() {
        return this.options;
    }
}

module.exports = CompilerPass;