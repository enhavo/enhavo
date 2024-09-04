import ComponentRegistryInterface from '@enhavo/core/ComponentRegistryInterface';
import * as Vue from "vue";

/** @deprecated */
export default class VueRegistry implements ComponentRegistryInterface
{
    private plugins: object[] = [];
    private data: object[] = [];
    private components: Entry[] = [];
    private directives: Entry[] = [];
    private stores: Entry[] = [];
    private configs: Entry[] = [];

    setConfig(name: string, value: string): ComponentRegistryInterface
    {
        this.deleteEntry(name, this.configs);
        this.configs.push(new Entry(name, value));
        return this;
    }

    registerComponent(name: string, component: object): ComponentRegistryInterface
    {
        this.deleteEntry(name, this.components);
        this.components.push(new Entry(name, component));
        return this;
    }

    registerDirective(name: string, store: object): ComponentRegistryInterface
    {
        this.deleteEntry(name, this.directives);
        this.directives.push(new Entry(name, store));
        return this;
    }

    registerStore(name: string, store: object): ComponentRegistryInterface
    {
        this.deleteEntry(name, this.stores);
        this.stores.push(new Entry(name, store));
        return this;
    }

    registerData<Type>(data: Type): Type
    {
        if (data === null) {
            return;
        }
        data = Vue.reactive(data);
        return data;
    }

    registerPlugin(plugin: object): ComponentRegistryInterface
    {
        this.plugins.push(plugin);
        return this;
    }

    getComponents(): Entry[]
    {
        return this.components;
    }

    getComponent(name: string): object|null
    {
        for (let entry of this.components) {
            if (entry.name === name) {
                return entry.value;
            }
        }
        return null;
    }

    getDirectives(): Entry[]
    {
        return this.directives;
    }

    getStores(): Entry[]
    {
        return this.stores;
    }

    getPlugins(): object[]
    {
        return this.plugins;
    }

    getConfigs(): Entry[]
    {
        return this.configs;
    }

    getData(): object[]
    {
        return this.data;
    }

    private deleteEntry(name: string, list: Entry[])
    {
        let foundEntry = null;
        for (let entry of list) {
            if (entry.name === name) {
                foundEntry = entry;
                break;
            }
        }

        if (foundEntry) {
            list.splice(list.indexOf(foundEntry), 1);
        }
    }
}

export class Entry
{
    name: string;
    value: any;

    constructor(name: string, value: any) {
        this.name = name;
        this.value = value;
    }
}
