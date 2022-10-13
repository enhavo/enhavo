import ParameterBag from "@enhavo/dependency-injection/container/ParameterBag";

export class Container
{
    constructor()
    {
        this._services = [];
        this._alreadyInit = false;
        this._parameters = new ParameterBag;
        this._hashes = {};
        /** @type {Array<Service>} */
        this._resolveStack = [];
        this._resolveCallStack = [];
    }

    async get(name) {
        if(typeof this._hashes[name] === 'undefined') {
            throw 'Service "'+name+'" does not exists';
        }

        let service = this._findService(name);
        if(service !== null) {
            return service.instance;
        }

        // To resolve the dependency tree we do following steps:
        // 1. Load all modules recursive (include als dependencies included by calls) and create all service objects.
        // 2. We instantiate recursive all dependencies starting by requested service, but we don't include call
        //    dependencies. We just push them to a `resolveCallStack`. We also push each service to `resolveStack`
        // 3. Now we go through all `resolveCallStack` dependencies and use them as starting point and execute step 2
        // 4. After that we go through all `resolveStack` and execute the dependencies calls for the services

        await this._load(name);
        await this._instantiate(name);
        await this._instantiate_calls();
        await this._call_calls();

        return this._findService(name).instance;
    }

    _hasMethod(methodName, context) {
        return typeof context[methodName] === 'function';
    }

    async _load(name) {
        let service = this._getService(name);
        if(service.state === 'created') {
            if (!this._hasMethod('load_' + this._hashes[name], this)) {
                service.state = 'loaded';
                return;
            }

            service.module = await this._call('load_' + this._hashes[name], this);
            service.state = 'loaded';

            let dependencies = await this._call('get_dependencies_' + this._hashes[name], this);
            for (let dependency of dependencies) {
                await this._load(dependency);
            }

            let callDependencies = await this._call('get_call_dependencies_' + this._hashes[name], this);
            for (let dependency of callDependencies) {
                await this._load(dependency);
            }
        }
    }

    async _instantiate(name) {
        let service = this._getService(name);
        if(service.state === 'loaded') {
            this._resolveStack.push(service);
            if (this._hasMethod('get_dependencies_' + this._hashes[name], this)) {
                let dependencies = await this._call('get_dependencies_' + this._hashes[name], this);
                for (let dependency of dependencies) {
                    await this._instantiate(dependency);
                }
            }

            if (this._hasMethod('get_call_dependencies_' + this._hashes[name], this)) {
                let callDependencies = await this._call('get_call_dependencies_' + this._hashes[name], this);
                for (let dependency of callDependencies) {
                    this._resolveCallStack.push(dependency);
                }
            }

            service.instance = await this._call('instantiate_' + this._hashes[service.name], this);
            service.state = 'instantiated';
        }
    }

    async _instantiate_calls() {
        while(this._resolveCallStack.length > 0) {
            let dependency = this._resolveCallStack.pop();
            await this._instantiate(dependency);
        }
    }

    async _call_calls() {
        while(this._resolveStack.length > 0) {
            let service = this._resolveStack.pop();
            if (this._hasMethod('call_' + this._hashes[service.name], this)) {
                await this._call('call_' + this._hashes[service.name], this, [service.instance]);
            }
            service.state = 'ready';
        }
    }

    async _loadCall(name, list) {
        let service = this._getService(name);
        if(service.state === 'created') {
            list.push(service);
            service.module = await this._call('load_call_' + this._hashes[name], this);
            service.state = 'loaded';
        }
    }

    async _call(functionName, context, args = [])
    {
        if(typeof context[functionName] !== 'function') {
            throw functionName + ' is not a function';
        }

        return await context[functionName].apply(context, args);
    }

    async init() {
        if(this._alreadyInit) {
            return;
        }
        this._alreadyInit = true;
        await this._call('_init', this)
    }

    /**
     * @param {string} name
     * @return {Service}
     */
    _getService(name) {
        let service = this._findService(name);
        if(service === null) {
            service = this._createService(name);
            this._services.push(service);
        }
        return service;
    }

    /**
     * @param {string} name
     * @return {Service}
     */
    _findService(name) {
        for (let service of this._services) {
            if (service.name === name) {
                return service;
            }
        }
        return null;
    }

    _createService(name) {
        let service = new Service(name);
        service.state = 'created';
        return service;
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
    constructor(name) {
        this.name = name;
        this.instance = null;
        this.module = null;
        this.state = null;
    }
}
