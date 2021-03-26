import Vue, {PluginObject, VueConstructor} from "vue";
import VueRegistry from "@enhavo/app/vue/VueRegistry";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

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
        Vue.config.devtools = true;
        Vue.config.productionTip = false;

        this.register(Vue);

        Vue.prototype.eventDispatcher = this.eventDispatcher;

        this.vue = new Vue({
            el: '#' + id,
            data: data,
            render: (createElement) => {
                return createElement(component, {
                    'props': data,
                });
            }
        });
    }

    getVue() {
        return this.vue;
    }

    private register(vue: VueConstructor) {
        for (let config of this.registry.getConfigs()) {
            vue.config[config.name] = config.value;
        }

        for (let component of this.registry.getComponents()) {
            vue.component(component.name, component.value);
        }

        for (let store of this.registry.getStores()) {
            vue.prototype['$'+store.name] = store.value;
        }

        for (let directive of this.registry.getDirectives()) {
            vue.directive(directive.name, directive.value);
        }

        for (let plugin of this.registry.getPlugins()) {
            vue.use(<PluginObject<unknown>>plugin);
        }

        for (let data of this.registry.getData()) {
            vue.observable(data);
        }
    }
}
