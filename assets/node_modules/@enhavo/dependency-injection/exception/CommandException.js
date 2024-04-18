export default class CommandException
{
    constructor(message) {
        this.message = message;
        this.name = "CommandException";
    }

    toString() {
        return this.name + ": " + this.message;
    }
}
