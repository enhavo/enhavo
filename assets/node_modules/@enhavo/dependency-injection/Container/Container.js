import ParameterBag from "@enhavo/dependency-injection/Container/ParameterBag";

export class Container
{
    constructor()
    {
        this._services = [];
        this._alreadyInit = false;
        this._parameters = new ParameterBag;
        this._hashes = {};
    }

    async get(name) {
        for (let service in this._services) {
            if (service.name === name) {
                return service.instance;
            }
        }

        if(typeof this._hashes[name] === 'undefined') {
            throw 'Service "'+name+'" does not exists';
        }

        let service = new Service(name, await this._call('get_' + this._hashes[name], this));
        this._services.push(service);

        return service.instance;
    }

    async _call(functionName, context, args = [])
    {
        if(typeof context[functionName] !== 'function') {
            throw functionName + ' is not a function';
        }

        return await context[functionName](args);
    }

    async init() {
        if(this._alreadyInit) {
            return;
        }
        this._alreadyInit = true;
        await this._call('_init', this)
    }

    setParameter(key, value) {
        this._parameters.set(key, value);
    }

    getParameter(key) {
        return this._parameters.get(key);
    }
}

class Service
{
    constructor(name, instance) {
        this.name = name;
        this.instance = instance;
    }
}
