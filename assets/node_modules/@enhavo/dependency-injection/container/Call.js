
export default class Call
{
    constructor(methodName, methodArguments = []) {
        this._name = methodName;
        this._arguments = methodArguments;
    }

    getName() {
        return this._name;
    }

    getArguments() {
        return this._arguments;
    }
}
