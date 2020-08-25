class ContainerException
{
    constructor(message) {
        this.message = message;
        this.name = "ContainerException";
    }

    toString() {
        return this.name + ": " + this.message;
    }
}


module.exports = ContainerException;