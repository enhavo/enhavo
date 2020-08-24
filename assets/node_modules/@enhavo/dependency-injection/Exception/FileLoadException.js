class FileLoadException
{
    constructor(message) {
        this.message = message;
        this.name = "FileLoadException";
    }

    toString() {
        return this.name + ": " + this.message;
    }
}


module.exports = FileLoadException;