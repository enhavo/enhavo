import RegistryInterface from "./RegistryInterface";
import RegistryPackageInterface from "@enhavo/core/RegistryPackageInterface";

export default class Registry implements RegistryInterface
{
    private entries: Entry[] = [];

    private get(name: string): Entry
    {
        for(let entry of this.entries) {
            if(entry.name == name) {
                return entry;
            }
        }
        throw 'Entry with name "'+name+'" does not exist in registry';
    }

    getFactory(name: string): object
    {
        return this.get(name).factory;
    }

    getComponent(name: string): object
    {
        return this.get(name).component;
    }

    register(name: string, component: object, factory: object): RegistryInterface
    {
        if(this.has(name)) {
            throw 'Object with name "'+name+'" already exists in registry';
        }
        this.entries.push(new Entry(name, component, factory));
        return this;
    }

    registerPackage(registryPackage: RegistryPackageInterface)
    {
        for(let entry of registryPackage.getEntries()) {
            this.register(entry.name, entry.component, entry.factory);
        }
        return this;
    }

    has(name: string): boolean
    {
        for(let entry of this.entries) {
            if(entry.name == name) {
                return true;
            }
        }
        return false;
    }

    getComponents(): object
    {
        let components = {};
        for(let entry of this.entries) {
            components[entry.name] = entry.component;
        }
        return components;
    }
}

class Entry
{
    name: string;
    factory: object;
    component: object;

    constructor(name: string, component: object, factory: object)
    {
        this.name = name;
        this.component = component;
        this.factory = factory;
    }
}