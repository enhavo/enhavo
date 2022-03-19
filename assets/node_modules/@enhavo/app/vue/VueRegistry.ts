import ComponentRegistryInterface from '@enhavo/core/ComponentRegistryInterface';
import * as Vue from "vue";

export default class VueRegistry implements ComponentRegistryInterface
{
    private plugins: object[] = [];
    private data: object[] = [];
    private components: Entry[] = [];
    private directives: Entry[] = [];
    private stores: Entry[] = [];
    private configs: Entry[] = [];

    setConfig(name: string, value: string): ComponentRegistryInterface {
        this.configs.push(new Entry(name, value));
        return this;
    }

    registerComponent(name: string, component: object): ComponentRegistryInterface {
        this.components.push(new Entry(name, component));
        return this;
    }

    registerDirective(name: string, store: object): ComponentRegistryInterface {
        this.directives.push(new Entry(name, store));
        return this;
    }

    registerStore(name: string, store: object): ComponentRegistryInterface {
        this.stores.push(new Entry(name, store));
        return this;
    }

    registerData<Type>(data: Type): Type {
        if (data === null) {
            return;
        }
        data = Vue.reactive(data);
        return data;
    }

    registerPlugin(plugin: object): ComponentRegistryInterface {
        this.plugins.push(plugin);
        return this;
    }

    getComponents(): Entry[] {
        return this.components;
    }

    getDirectives(): Entry[] {
        return this.directives;
    }

    getStores(): Entry[] {
        return this.stores;
    }

    getPlugins(): object[] {
        return this.plugins;
    }

    getConfigs(): Entry[] {
        return this.configs;
    }

    getData(): object[] {
        return this.data;
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
