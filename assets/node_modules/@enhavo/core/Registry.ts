import {RegistryInterface} from "@enhavo/core/RegistryInterface";
import RegistryEntry from "@enhavo/core/RegistryEntry";

export default class Registry implements RegistryInterface
{
    private entries: RegistryEntry[] = [];

    private get(name: string): RegistryEntry
    {
        for(let entry of this.entries) {
            if(entry.getName() == name) {
                return entry;
            }
        }
        throw 'Entry with name "'+name+'" does not exist in registry';
    }

    getFactory(name: string): object
    {
        return this.get(name).getFactory();
    }

    getComponent(name: string): object
    {
        return this.get(name).getComponent();
    }

    register(entry: RegistryEntry): RegistryInterface
    {
        if(this.has(entry.getName())) {
            this.deleteEntry(entry.getName());
        }
        this.entries.push(entry);
        return this;
    }

    private deleteEntry(name: string)
    {
        for (let i in this.entries) {
            if(this.entries[i].getName() == name) {
                this.entries.splice(parseInt(i), 1);
                break;
            }
        }
    }

    has(name: string): boolean
    {
        for(let entry of this.entries) {
            if(entry.getName() == name) {
                return true;
            }
        }
        return false;
    }

    getComponents(): Component[]
    {
        let components = [];
        for(let entry of this.entries) {
            components.push(new Component(entry.getName(), entry.getComponent()));
        }
        return components;
    }
}

export class Component
{
    public name: string;
    public component: object;

    constructor(name: string, component: object) {
        this.name = name;
        this.component = component;
    }
}
