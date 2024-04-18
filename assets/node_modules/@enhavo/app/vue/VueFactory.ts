import {createApp, Component, App, Plugin, reactive} from "vue";

export class VueFactory
{
    private plugins: Plugin[] = [];
    private components: Array<RegistryComponent> = [];
    private services: Array<RegistryService> = [];

    registerPlugin(plugin: Plugin)
    {
        this.plugins.push(plugin);
    }

    registerComponent(name: string, component: Component)
    {
        this.deleteComponent(name);
        this.components.push(new RegistryComponent(name, component));
    }

    registerService(name: string, service: any, reactive: boolean|null = false)
    {
        this.services.push(new RegistryService(name, service, reactive));
    }

    getComponent(name: string): Component|null
    {
        for (let component of this.components) {
            if (name === component.name) {
                return component.component;
            }
        }
        return null;
    }

    createApp(rootComponent: Component, rootProps?: any|null): App
    {
        const app = createApp(rootComponent, rootProps);

        for (let plugin of this.plugins) {
            app.use(plugin);
        }

        for (let component of this.components) {
            app.component(component.name, component.component);
        }

        for (let service of this.services) {
            app.provide(service.name, service.reactive ? reactive(service.service) : service.service);
        }

        return app;
    }

    private deleteComponent(name: string)
    {
        let foundEntry = null;
        for (let entry of this.components) {
            if (entry.name === name) {
                foundEntry = entry;
                break;
            }
        }

        if (foundEntry) {
            this.components.splice(this.components.indexOf(foundEntry), 1);
        }
    }
}

class RegistryService
{
    constructor(
        public name: string,
        public service: any,
        public reactive: boolean,
    ) {
    }
}

class RegistryComponent
{
    constructor(
        public name: string,
        public component: Component
    ) {
    }
}
