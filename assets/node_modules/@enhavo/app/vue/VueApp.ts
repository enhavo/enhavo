import * as Vue from "vue";
import VueRegistry from "@enhavo/app/vue/VueRegistry";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import {App} from "@vue/runtime-core";
import * as draggable from 'vuedraggable'

export default class VueApp
{
    private readonly registry: VueRegistry;
    private readonly eventDispatcher: EventDispatcher;
    private vue: Vue;

    constructor(registry: VueRegistry, eventDispatcher: EventDispatcher) {
        this.registry = registry;
        this.eventDispatcher = eventDispatcher;
    }

    init(id: string, component: object, data: object = {}) {
        const app = Vue.createApp(component, data);
        this.register(app);
        app.mount('#' + id);
    }

    getVue() {
        return this.vue;
    }

    private register(app: App)
    {
        app.provide('eventDispatcher', this.eventDispatcher);
        app.component('draggable', draggable);

        for (let config of this.registry.getConfigs()) {
            app.config[config.name] = config.value;
        }

        for (let component of this.registry.getComponents()) {
            app.component(component.name, component.value);
        }

        for (let store of this.registry.getStores()) {
            app.provide(store.name, store.value)
        }

        for (let directive of this.registry.getDirectives()) {
            app.directive(directive.name, directive.value);
        }

        for (let plugin of this.registry.getPlugins()) {
            app.use(<PluginObject<unknown>>plugin);
        }
    }
}
