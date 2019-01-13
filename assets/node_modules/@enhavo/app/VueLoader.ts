import Vue from "vue";
import App from "./Components/App.vue";
export class VueLoader
{
    private selector: string;

    private vue: Vue;

    constructor(selector: string)
    {
        this.selector = selector;
    }

    load() {
        this.vue = new Vue({
            el: this.selector,
            data: {},
            render: h => h(App),
        });

        return this.vue;
    }
}


