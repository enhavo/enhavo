
class Definition
{
    /**
     * @param {string} name
     */
    constructor(name)
    {
        this.name = name;
        this.arguments = [];
        this.tags = [];
        this.calls = [];
        this.import = null;
        this.from = null;
    }

    /**
     * @param {string} argument
     */
    addArgument(argument) {
        this.arguments.push(argument);
    }

    addTag(tag) {
        this.tags.push(tag);
    }

    addCall(call) {
        this.calls.push(call);
    }

    setFrom(from) {
        this.from = from;
    }

    setImport(importName) {
        this.import = importName;
    }

    getFrom() {
        return this.from;
    }

    getImport() {
        return this.import;
    }

    getName() {
        return this.name;
    }

    getArguments() {
        return this.arguments;
    }
}

module.exports = Definition;