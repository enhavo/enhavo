export default class FileLoadException
{
    constructor(message) {
        this.message = message;
        this.name = "FileLoadException";
    }

    toString() {
        return this.name + ": " + this.message;
    }
}
