const ContainerBuilder = require("@enhavo/dependency-injection/container/ContainerBuilder");

class ContainerBuilderBucket
{
    constructor() {
        /** @type {Entry[]} */
        this.entries = [];
        this.current = null;
    }

    createBuilder(name) {
        if (this.hasBuilder(name)) {
            return this.getBuilder(name);
        }

        this.entries.push(new Entry(name, new ContainerBuilder()));
        this.setCurrentBuilder(name);
        return this.getCurrentBuilder();
    }

    setCurrentBuilder(name) {
        this.current = name;
    }

    getCurrentBuilder() {
        return this.getBuilder(this.current);
    }

    getCurrentName() {
        return this.current;
    }

    hasBuilder(name) {
        for (let entry of this.entries) {
            if (entry.name === name) {
                return true;
            }
        }
        return false;
    }

    getBuilder(name) {
        for (let entry of this.entries) {
            if (entry.name === name) {
                return entry.builder;
            }
        }
        throw 'Can\'t find any builder'
    }
}

class Entry
{
    constructor(name, builder) {
        /** @type {string} */
        this.name = name;
        /** @type {ContainerBuilder} */
        this.builder = builder;
    }
}

module.exports = ContainerBuilderBucket;
