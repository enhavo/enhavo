const sha1 = require('sha1');
const Tag = require('@enhavo/dependency-injection/container/Tag');

class Definition
{
    /**
     * @param {string} name
     */
    constructor(name)
    {
        this.name = name;
        this.arguments = [];
        /** @type {Array<Tag>} */
        this.tags = [];
        /** @type {Array<Call>} */
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
        this.ignore = null;
        this.init = false;
        this.factory = null;
        this.factoryMethod = null;
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

    /**
     * @returns {Array<Argument>}
     */
    getArguments() {
        return this.arguments;
    }

    /**
     * @param {number} index
     * @returns {Argument}
     */
    getArgument(index) {
        return this.arguments[index];
    }

    /**
     * @returns {string}
     */
    getHash() {
        return this.hash;
    }

    /**
     * @returns {boolean}
     */
    setStatic(value) {
        this.static = value;
    }

    /**
     * @returns {boolean}
     */
    isStatic() {
        return this.static;
    }

    /**
     * @param {Tag} tag
     */
    addTag(tag) {
        this.tags.push(tag);
    }

    /**
     * @param {string} name
     */
    hasTag(name) {
        return null !== this.getTag(name);
    }

    /**
     * @param {string} name
     * @return {Tag}
     */
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

    setInit(value) {
        return this.init = value;
    }

    isInit() {
        return this.init;
    }

    /**
     * @returns {Array<Call>}
     */
    getCalls() {
        return this.calls;
    }

    setPrefetch(prefetch) {
        this.prefetch = prefetch;
    }

    getPrefetch() {
        return this.prefetch
    }

    setPreload(preload) {
        this.preload = preload;
    }

    getPreload() {
        return this.preload;
    }

    setChunkName(chunkName) {
        this.chunckName = chunkName;
    }

    getChunkName() {
        return this.chunckName;
    }

    setInclude(include) {
        this.include = include;
    }

    getInclude() {
        return this.include;
    }

    setExclude(exclude) {
        this.exclude = exclude;
    }

    getExclude() {
        return this.exclude
    }

    setExports(exports) {
        this.exports = exports;
    }

    getExports() {
        return this.exports;
    }

    setIgnore(ignore) {
        this.ignore = ignore;
    }

    getIgnore() {
        return this.ignore;
    }

    getDependDefinitionNames()
    {
        let depends = [];
        for (let argument of this.arguments) {
            if(argument.getType() === 'service') {
                depends.push(argument.getValue());
            }
        }
        return depends;
    }

    getDependCallDefinitionNames()
    {
        let depends = [];
        for (let call of this.calls) {
            for (let argument of call.getArguments()) {
                if(argument.getType() === 'service') {
                    depends.push(argument.getValue());
                }
            }
        }
        return depends;
    }

    setFactory(factory) {
        this.factory = factory;
    }

    getFactory() {
        return this.factory;
    }

    setFactoryMethod(method) {
        this.factoryMethod = method;
    }

    getFactoryMethod() {
        return this.factoryMethod;
    }
}

module.exports = Definition;
