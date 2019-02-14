import Vue, {VueConstructor} from "vue";
import AppInterface from "./AppInterface";

export default class VueLoader
{
    private id: string;
    private vue: Vue;
    private component: VueConstructor;
    private app: AppInterface;

    constructor(id: string, app: AppInterface, component: VueConstructor)
    {
        this.id = id;
        this.app = app;
        this.component = component;
    }

    load() {
        let self = this;
        this.vue = new Vue({
            el: '#' + this.id,
            data: this.app.getData(),
            render: function(createElement) {
                return createElement(self.component, {
                    'props': self.app.getData(),
                })
            }
        });

        return this.vue;
    }
}


