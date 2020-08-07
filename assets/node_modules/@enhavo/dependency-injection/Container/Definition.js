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
        this.mode = null;
        this.prefetch = null;
        this.preload = null;
        this.chunckName = null;
        this.include = null;
        this.exclude = null;
        this.exports = null;
    }

    getName() {
        return this.name;
    }

    /**
     * @param {string} argument
     */
    addArgument(argument) {
        this.arguments.push(argument);
    }

    addCall(call) {
        this.calls.push(call);
    }

    setFrom(from) {
        this.from = from;
    }

    getFrom() {
        if(this.from === null) {
            return this.name;
        }
        return this.from;
    }

    setImport(importName) {
        this.import = importName;
    }

    getImport() {
        return this.import;
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

    addTag(tag) {
        this.tags.push(tag);
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

    setMode(mode) {
        this.mode = mode;
    }

    getMode() {
        return this.mode;
    }
}

module.exports = Definition;
