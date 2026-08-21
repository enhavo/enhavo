import {App, Component, createApp, defineAsyncComponent, Plugin, reactive} from "vue";
import {ContainerInterface} from "@enhavo/dependency-injection/container/ContainerInterface"

export class VueFactory
{
    private plugins: Plugin[] = [];
    private components: Array<RegistryComponent> = [];
    private services: Array<RegistryService> = [];
    private directives: Array<RegistryDirective> = [];

    constructor(
        private container: ContainerInterface
    ) {
    }

    registerPlugin(plugin: Plugin)
    {
        this.plugins.push(plugin);
    }

    registerComponent(name: string, component: Component)
    {
        this.deleteComponent(name);
        this.components.push(new RegistryComponent(name, component));
    }

    registerDirective(name: string, directive: any)
    {
        this.deleteDirective(name);
        this.directives.push(new RegistryDirective(name, directive));
    }

    registerService(name: string, service: string, reactive: boolean = false, lazy: boolean = false)
    {
        this.services.push(new RegistryService(name, service, reactive, lazy));
    }

    getComponent(name: string): Promise<Component|null>
    {
        for (let component of this.components) {
            if (name === component.name) {
                return this.container.get(component.component)
            }
        }
        return Promise.resolve(null);
    }

    async createApp(rootComponent: Component, rootProps?: any|null): App
    {
        const app = createApp(rootComponent, rootProps);

        for (let plugin of this.plugins) {
            app.use(plugin);
        }

        for (let directive of this.directives) {
            app.directive(directive.name, directive.directive);
        }

        for (let component of this.components) {
            app.component(component.name, defineAsyncComponent(() =>
                this.container.get(component.component)
            ));
        }

        for (let service of this.services) {
            if (service.lazy) {
                let cachedPromise: Promise<any> | null = null;
                // new Promise would load service immediately, so we need a thenable object here
                const lazyPromise = {
                    then: (onFulfilled?: any, onRejected?: any) => {
                        if (!cachedPromise) {
                            cachedPromise = this.container.get(service.service).then(innerService =>
                                service.reactive ? reactive(innerService) : innerService
                            );
                        }
                        return cachedPromise.then(onFulfilled, onRejected);
                    },
                    catch(onRejected?: any) {
                        return this.then(undefined, onRejected);
                    }
                };
                app.provide(service.name, lazyPromise);
            } else {
                const resolved = await this.container.get(service.service);
                app.provide(service.name, service.reactive ? reactive(resolved) : resolved);
            }
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

    private deleteDirective(name: string)
    {
        let foundEntry = null;
        for (let entry of this.directives) {
            if (entry.name === name) {
                foundEntry = entry;
                break;
            }
        }

        if (foundEntry) {
            this.directives.splice(this.directives.indexOf(foundEntry), 1);
        }
    }
}

class RegistryService
{
    constructor(
        public name: string,
        public service: string,
        public reactive: boolean,
        public lazy: boolean,
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

class RegistryDirective
{
    constructor(
        public name: string,
        public directive: any
    ) {
    }
}
