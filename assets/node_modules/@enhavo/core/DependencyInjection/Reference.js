
class Reference
{
    constructor(name) {
        this.name = name.substring(1);
    }

    getName() {
        return this.name;
    }
}

module.exports = Reference;