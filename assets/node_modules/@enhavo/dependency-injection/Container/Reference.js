
class Reference
{
    constructor(name) {
        /** @type {string} */
        this.name = name.substring(1);
        /** @type {string} */
        this.hash = null;
    }

    getHash() {
        return this.hash;
    }

    setHash(hash) {
        this.hash = hash;
    }

    getName() {
        return this.name;
    }
}

module.exports = Reference;
