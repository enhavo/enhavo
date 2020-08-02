const sha1 = require('sha1');
const Tag = require('@enhavo/dependency-injection/Container/Tag');

class Definition
{
    /**
     * @param {string} name
     */
    constructor(name)
    {
        this.name = name;
        this.arguments = [];
        /** @type {Tag} */
        this.tags = [];
        this.calls = [];
        this.import = null;
        this.from = null;
        this.static = false;
        this.hash = sha1(this.name).substring(0, 8);
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
        if(this.from === null) {
            return this.name;
        }
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

    getArgument(index) {
        return this.arguments[index];
    }

    getHash() {
        return this.hash;
    }

    isStatic() {
        return this.static;
    }

    /**
     * @param {string} name
     */
    hasTag(name) {
        return null !== this.getTag(name);
    }

    getTag(name) {
        for (let tag of this.tags) {
            if(tag.getName() === name) {
                return tag;
            }
        }
        return null;
    }
}

module.exports = Definition;
