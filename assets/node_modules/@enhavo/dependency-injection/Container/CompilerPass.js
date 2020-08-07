
class CompilerPass
{
    /**
     * @param {string} name
     * @param {string} path
     */
    constructor(name, path)
    {
        this.name = name;
        this.path = path;
    }

    getName() {
        return this.name;
    }

    getPath() {
        return this.path;
    }
}

module.exports = CompilerPass;