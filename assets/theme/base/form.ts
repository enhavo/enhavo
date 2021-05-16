import Vue from "vue";
import FormComponent from "@enhavo/vue-form/components/FormFormComponent.vue"
import vueForm from "@enhavo/vue-form"
import {Form} from "@enhavo/vue-form/form/Form";

Vue.config.devtools = true;
Vue.config.productionTip = false;
Vue.use(vueForm);

let element = document.getElementById('app');
let data = Form.create(JSON.parse(element.dataset.vue));

new Vue({
    el: element,
    data: data,
    render: (createElement) => {
        return createElement(FormComponent, {
            'props': {
                form: data
            },
        });
    }
});
