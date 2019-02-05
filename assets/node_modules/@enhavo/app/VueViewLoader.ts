import Vue from "vue";
import AppComponent from "./Components/AppView.vue";
import { App } from "./App";

export class VueViewLoader
{
    private id: string;

    private vue: Vue;

    private app: App;

    constructor(id: string, app: App)
    {
        this.id = id;
        this.app = app;
    }

    load() {
        Vue.component('app', AppComponent);
        let self = this;
        this.vue = new Vue({
            el: '#' + this.id,
            data: this.app.getData(),
            render: function(createElement) {
                return createElement(AppComponent, {
                    'props': {
                        'page': self.app.getData().page,
                        'pagination': self.app.getData().pagination,
                        'pagination_steps': self.app.getData().pagination_steps
                    }
                })
            }
        });

        return this.vue;
    }
}


