import Vue, {VueConstructor} from "vue";
import AppInterface from "@enhavo/app/AppInterface";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import LoadedEvent from "@enhavo/app/ViewStack/Event/LoadedEvent";
import View from "@enhavo/app/View/View";
import ClickOutside from "@enhavo/app/ClickOutside";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default class VueLoader
{
    private id: string;
    private vue: Vue;
    private app: AppInterface;
    private dispatcher: EventDispatcher;
    private view: View;

    constructor(id: string, app: AppInterface, dispatcher: EventDispatcher, view: View)
    {
        this.id = id;
        this.app = app;
        this.dispatcher = dispatcher;
        this.view = view;
    }

    load(component: VueConstructor, loadOnMount: boolean = true) {
        Vue.config.devtools = true;
        Vue.config.productionTip = false;
        Vue.directive('click-outside', new ClickOutside(this.dispatcher, this.view));
        Vue.component('v-select', vSelect);

        this.vue = new Vue({
            el: '#' + this.id,
            data: this.app.getData(),
            render: (createElement) => {
                return createElement(component, {
                    'props': this.app.getData(),
                })
            },
            mounted: () => {
                if(loadOnMount) {
                    this.dispatcher.dispatch(new LoadedEvent(this.view.getId()));
                }
            }
        });
        return this.vue;
    }
}


